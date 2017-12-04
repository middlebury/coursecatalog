<?php

/** Zend_Controller_Action */
class SchedulesController
	extends AbstractCatalogController
{

	/**
	 * Make decisions about whether or not the requested action should be dispatched
	 *
	 * @return void
	 * @access public
	 * @since 8/2/10
	 */
	public function preDispatch () {
		if (!$this->_helper->auth->getHelper()->isAuthenticated()) {
			$this->_redirect(
				$this->view->url(array('controller' => 'auth', 'action' => 'login', 'return' => $this->view->url())),
				array('exit' => true, 'prependBase' => false));
		}
	}

	/**
	 * Initialize the catalog and term we are working with.
	 *
	 * Sets the following member properties:
	 * 		catalogId				osid_id_Id or NULL
	 *		termLookupSession		osid_course_TermLookupSession
	 *		termId					osid_id_Id or NULL
	 *
	 * @return void
	 */
	protected function initializeCatalogAndTerm () {
		// Select the catalog.
		if ($this->_getParam('catalog')) {
			$this->catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));
			$this->setSavedCatalogId($this->catalogId);
		} else {
			// Check for a saved catalog id.
			$this->catalogId = $this->getSavedCatalogId();
		}

		// Load the termLookupSession
		if ($this->catalogId) {
			$this->termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSessionForCatalog($this->catalogId);
		} else {
			$this->termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSession();
			$this->catalogId = $this->termLookupSession->getCourseCatalogId();
		}


		// Select the term
		if ($this->_getParam('term') == 'ANY') {
			// Don't set a term
			$this->termId = null;
		} else if (!$this->_getParam('term') || $this->_getParam('term') == 'CURRENT') {
			$this->termId = $this->_helper->osidTerms->getNextOrLatestTermId($this->catalogId);
		} else {
			$this->termId = $this->_helper->osidId->fromString($this->_getParam('term'));
		}
	}

	/**
	 * Answer a saved catalog Id or null
	 *
	 * @return osid_id_Id or NULL
	 */
	protected function getSavedCatalogId () {
		if (!isset($this->savedCatalogId)) {
			$stmt = Zend_Registry::get('db')->prepare("SELECT * FROM user_catalog WHERE user_id = ?");
			$stmt->execute(array($this->_helper->auth()->getUserId()));
			$row = $stmt->fetch();
			$stmt->closeCursor();
			if ($row) {
				$this->savedCatalogId = new phpkit_id_Id($row['catalog_id_authority'], $row['catalog_id_namespace'], $row['catalog_id_keyword']);
			} else {
				$this->savedCatalogId = null;
			}
		}
		return $this->savedCatalogId;
	}

	/**
	 * Set the saved catalog id.
	 *
	 * @param osid_id_Id $catalogId
	 * @return void
	 */
	protected function setSavedCatalogId (osid_id_Id $catalogId) {
		if (!is_null($this->getSavedCatalogId()) && $catalogId->isEqual($this->getSavedCatalogId()))
			return;

		$db = Zend_Registry::get('db');
		$insert = $db->prepare("INSERT INTO user_catalog (user_id, catalog_id_authority, catalog_id_namespace, catalog_id_keyword) VALUES (?, ?, ?, ?);");
		try {
			$insert->execute(array($this->_helper->auth()->getUserId(), $catalogId->getAuthority(), $catalogId->getIdentifierNamespace(), $catalogId->getIdentifier()));
		} catch (Zend_Db_Statement_Exception $e) {
			// Already exists
			if ($e->getCode() == 23000) {
				$update = $db->prepare("UPDATE user_catalog SET catalog_id_authority = ?, catalog_id_namespace = ?, catalog_id_keyword = ? WHERE user_id = ?");
				$update->execute(array($catalogId->getAuthority(), $catalogId->getIdentifierNamespace(), $catalogId->getIdentifier(), $this->_helper->auth()->getUserId()));
			} else {
				throw $e;
			}
		}

		$this->savedCatalogId = $catalogId;
	}

	public function indexAction()
	{
		$this->initializeCatalogAndTerm();

		// Set up data for the menu rendering
		$this->setSelectedCatalogId($this->catalogId);

		$this->view->emailEnabled = $this->emailEnabled();

		// Catalogs
		$catalogLookupSession = $this->_helper->osid->getCourseManager()->getCourseCatalogLookupSession();
		$this->view->catalogs = $catalogLookupSession->getCourseCatalogs();

		// Load all terms for our selection control
		$this->termLookupSession->useFederatedCourseCatalogView();
		$terms = $this->termLookupSession->getTerms();
		$termCatalogSession = $this->_helper->osid->getCourseManager()->getTermCatalogSession();
		$this->view->terms = array();
		while ($terms->hasNext()) {
			$term = $terms->getNextTerm();
			$termCatalogId = $this->catalogId;
			$this->view->terms[] = array(
				'name'	=> $term->getDisplayName(),
				'url'	=> $this->view->url(array(
							'catalog' => $this->_helper->osidId->toString($termCatalogId),
							'term' => $this->_helper->osidId->toString($term->getId()),
							)),
				'id'	=> $term->getId(),
			);
		}
		// Set the selected term
		if ($this->termId) {
			$this->view->selectedTermId = $this->termId;
			$this->view->termIdString = $this->_helper->osidId->toString($this->termId);
		}


		// Load the bookmarks for the selected catalog/terms
		$bookmarks = $this->_helper->bookmarks();
		if (isset($this->view->selectedTermId)) {
			$this->view->bookmarked_courses = $bookmarks->getBookmarkedCoursesInCatalogForTerm($this->catalogId, $this->view->selectedTermId);
		} else {
			$this->view->bookmarked_courses = $bookmarks->getAllBookmarkedCourses();
		}

		// Load the Schedules for the selected catalog/terms
		$schedules = new Schedules(Zend_Registry::get('db'),  $this->_helper->auth->getHelper()->getUserId(), $this->_helper->osid->getCourseManager());
		if ($this->view->selectedTermId)
			$this->view->schedules = $schedules->getSchedulesByTerm($this->view->selectedTermId);
		else
			$this->view->schedules = $schedules->getSchedules();

		$this->view->leftText = "
		<p class='notice'><strong>Important:</strong> This tool is for planning purposes only. It does <strong>not</strong> register you for classes.</p>

		<div class='help'><strong>Basic Usage:</strong>
			<ol>
				<li>Search for courses in the catalog and save interesting ones.</li>
				<li>Create one or more schedules in the Planner.</li>
				<li>Add courses to schedules.</li>
				<li>Print or email your schedules.</li>
			</ol>

			<p><strong>For more help see:</strong> <a href='http://go.middlebury.edu/catalog-help' target='_blank'>go/catalog-help</a></p>
			<p><strong>Issues or Feedback?</strong> <a href='http://go.middlebury.edu/webbugs/Catalog/Planner' target='_blank'>go/webbugs/Catalog/Planner</a></p>
		</div>
		";
	}

	/**
	 * Verify change actions
	 *
	 * @return void
	 * @access protected
	 * @since 8/2/10
	 */
	protected function verifyChangeRequest () {
		if (!$this->_request->isPost())
			throw new PermissionDeniedException("Create Schedules must be submitted as a POST request.");
		$this->_request->setParamSources(array('_POST'));

		// Verify our CSRF key
		if (!$this->_getParam('csrf_key') == $this->_helper->csrfKey())
			throw new PermissionDeniedException('Invalid CSRF Key. Please log in again.');
	}

	/**
	 * Return to the index action
	 *
	 * @return void
	 * @access protected
	 * @since 8/2/10
	 */
	protected function returnToIndex () {
		$catalogIdString = $this->_getParam('catalog');
		$termIdString = $this->_getParam('term');

		// Forward us back to the listing.
		$url = $this->view->url(array('action' => 'index', 'controller' => 'schedules', 'catalog' => $catalogIdString, 'term' => $termIdString));
		$this->_redirect($url, array('exit' => true, 'prependBase' => false));
	}

	/**
	 * Create a new schedule
	 *
	 * @return void
	 * @access public
	 * @since 8/2/10
	 */
	public function createAction () {
		$this->verifyChangeRequest();

		$schedules = new Schedules(Zend_Registry::get('db'),  $this->_helper->auth->getHelper()->getUserId(), $this->_helper->osid->getCourseManager());

		$schedule = $schedules->createSchedule($this->_helper->osidId->fromString($this->_getParam('term')));


		$this->returnToIndex();
	}

	/**
	 * Update a schedule
	 *
	 * @return void
	 * @access public
	 * @since 8/2/10
	 */
	public function updateAction () {
		$this->verifyChangeRequest();

		$schedules = new Schedules(Zend_Registry::get('db'),  $this->_helper->auth->getHelper()->getUserId(), $this->_helper->osid->getCourseManager());

		$schedule = $schedules->getSchedule($this->_getParam('schedule_id'));
		$schedule->setName($this->_getParam('name'));

		$this->returnToIndex();
	}

	/**
	 * Delete a schedule
	 *
	 * @return void
	 * @access public
	 * @since 8/2/10
	 */
	public function deleteAction () {
		$this->verifyChangeRequest();

		$schedules = new Schedules(Zend_Registry::get('db'),  $this->_helper->auth->getHelper()->getUserId(), $this->_helper->osid->getCourseManager());

		$schedules->deleteSchedule($this->_getParam('schedule_id'));

		$this->returnToIndex();
	}

	/**
	 * Add sections to a schedule
	 *
	 * @return void
	 * @access public
	 * @since 8/2/10
	 */
	public function addAction () {
		$this->verifyChangeRequest();

		$schedules = new Schedules(Zend_Registry::get('db'),  $this->_helper->auth->getHelper()->getUserId(), $this->_helper->osid->getCourseManager());

		$schedule = $schedules->getSchedule($this->_getParam('schedule_id'));

		// Get our ids from the POST
		$offeringIds = array();
		foreach ($_POST as $key => $val) {
			if (preg_match('/^section_group_[0-9]+$/', $key)) {
				$offeringIds[] = $this->_helper->osidId->fromString($val);
			}
		}
		if (!count($offeringIds))
			throw new InvalidArgumentException('No Sections selected.');

		/*********************************************************
		 * Validate the set of offerings chosen
		 *********************************************************/
		$lookupSession = $this->_helper->osid->getCourseManager()->getCourseOfferingLookupSession();
		$lookupSession->useFederatedCourseCatalogView();
		$linkType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:link');

		$offering = $lookupSession->getCourseOffering($offeringIds[0]);
		$course = $offering->getCourse();
		$termId = $offering->getTermId();

		$selectedLinkSet = $this->_helper->osidId->fromString($this->_getParam('section_set'));
		$linkTypes = $course->getLinkTypeIdsForTermAndSet($termId, $selectedLinkSet);
		$requiredLinkTypes = array();
		while ($linkTypes->hasNext()) {
			$requiredLinkTypes[] = array(
			'id' => $linkTypes->getNextId(),
				'found' => false,
			);
		}

		foreach ($offeringIds as $id) {
			$offering = $lookupSession->getCourseOffering($id);

			// Verify that the offering is part of the selected link-set.
			$linkRecord = $offering->getCourseOfferingRecord($linkType);
			if (!$selectedLinkSet->isEqual($linkRecord->getLinkSetId()))
				throw new Exception('The offering chosen is not part of the link-set selected.');

			// Check that we are adding a single section from each link-type.
			$linkTypeId = $linkRecord->getLinkTypeId();
			$checked = false;
			foreach ($requiredLinkTypes as $key => $info) {
				if ($info['id']->isEqual($linkTypeId)) {
					$checked = true;
					if ($info['found'])
						throw new Exception('A second section from the same link-group is selected.');
					else
						$requiredLinkTypes[$key]['found'] = true;
				}
			}
			if (!$checked)
				throw new Exception("The link-group id of the offering '".$linkTypeId->getIdentifier()."' wasn't in the required list.");

			// Also check that the sections are from the same course and term.
			if (!$offering->getTermId()->isEqual($termId))
				throw new Exception("Trying to add offerings from multiple terms.");
			if (!$offering->getCourseId()->isEqual($course->getId()))
				throw new Exception("Trying to add offerings from multiple courses.");
		}
		// Check that we are adding a section for each link-group.
		foreach ($requiredLinkTypes as $info) {
			if (!$info['found'])
				throw new Exception("No offering was added for the link-group ".$info['id']->getIdentifier()." when adding sections for ".$course->getDisplayName().". POST: ".print_r($_POST, true));
		}

		/*********************************************************
		 * Remove any offerings for the course already added.
		 *********************************************************/
		foreach ($schedule->getOfferings() as $oldOffering) {
			if ($oldOffering->getCourseId()->isEqual($course->getId())) {
				$schedule->remove($oldOffering->getId());
			}
		}

		/*********************************************************
		 * Add the offerings to the Schedule
		 *********************************************************/
		foreach ($offeringIds as $offeringId) {
			try {
				$schedule->add($offeringId);
			} catch (Exception $e) {
				if ($e->getCode() != 23000)
					throw $e;
			}
		}

		$this->returnToIndex();
	}

	/**
	 * Remove an offering from a schedule
	 *
	 * @return void
	 * @access public
	 * @since 8/4/10
	 */
	public function removeAction () {
		$this->verifyChangeRequest();

		$schedules = new Schedules(Zend_Registry::get('db'),  $this->_helper->auth->getHelper()->getUserId(), $this->_helper->osid->getCourseManager());

		$schedule = $schedules->getSchedule($this->_getParam('schedule_id'));

		$lookupSession = $this->_helper->osid->getCourseManager()->getCourseOfferingLookupSession();
		$lookupSession->useFederatedCourseCatalogView();

		$offering = $lookupSession->getCourseOffering($this->_helper->osidId->fromString($this->_getParam('offering')));
		$courseId = $offering->getCourseId();

		// Remove the selected offering.
		$schedule->remove($offering->getId());

		// Remove all other offerings for the course.
		foreach ($schedule->getOfferings() as $offering) {
			if ($offering->getCourse()->getId()->isEqual($courseId))
				$schedule->remove($offering->getId());
		}

		$this->returnToIndex();
	}

	/**
	 * Answer a JSON list of sections information for a course.
	 *
	 * @return void
	 * @access public
	 * @since 8/3/10
	 */
	public function sectionsforcourseAction () {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$this->getResponse()->setHeader('Content-Type', 'text/plain');


		$offeringSearchSession = $this->_helper->osid->getCourseManager()->getCourseOfferingSearchSession();
		$offeringSearchSession->useFederatedCourseCatalogView();

		$query = $offeringSearchSession->getCourseOfferingQuery();
		$query->matchCourseId($this->_helper->osidId->fromString($this->_getParam('course')), true);
		$query->matchTermId($this->_helper->osidId->fromString($this->_getParam('term')), true);

		$results = $offeringSearchSession->getCourseOfferingsByQuery($query);

		$schedules = new Schedules(Zend_Registry::get('db'),  $this->_helper->auth->getHelper()->getUserId(), $this->_helper->osid->getCourseManager());
		$schedule = $schedules->getSchedule($this->_getParam('schedule_id'));

		$sets = array();
		$linkType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:link');
		$instructorType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors');
		while ($results->hasNext()) {
			$offering = $results->getNextCourseOffering();

			$instructors = $offering->getInstructors();
			$instructorString = '';
			while ($instructors->hasNext()) {
				$instructorString .= $instructors->getNextResource()->getDisplayName().", ";
			}
			$instructorString = trim($instructorString, ', ');

			$conflicts = $schedule->conflicts($offering);
			if ($conflicts) {
				$conflictString = '';
				$conflictingNames = array();
				foreach ($schedule->getConflictingEvents($offering) as $event) {
					$conflictingNames[] = $event['name'];
				}
				$conflictString = 'Conflicts with: '.implode(', ', array_unique($conflictingNames));
			} else {
				$conflictString = '';
			}
			$info = array(
				'id' 			=> $this->_helper->osidId->toString($offering->getId()),
				'name'			=> $offering->getDisplayName(),
				'type'			=> $offering->getGenusType()->getDisplayName(),
				'instructor'	=> $instructorString,
				'location' 		=> $offering->getLocationInfo(),
				'availability'	=> $this->view->getAvailabilityLink($offering),
				'schedule' 		=> $this->view->formatScheduleInfo($offering->getScheduleInfo()),
				'conflicts'		=> $conflicts,
				'conflictString'	=> $conflictString,
			);


			// Get the link id and ensure that we have a set and type-group for it.
			$linkRecord = $offering->getCourseOfferingRecord($linkType);
			$linkSetIdString = $this->_helper->osidId->toString($linkRecord->getLinkSetId());
			$info['link_set'] = $linkSetIdString;
			if (!isset($sets[$linkSetIdString]))
				$sets[$linkSetIdString] = array();
			if (!isset($sets[$linkSetIdString]['types']))
				$sets[$linkSetIdString]['types'] = array();

			$linkTypeIdString = $this->_helper->osidId->toString($linkRecord->getLinkTypeId());
			$info['link_type'] = $linkTypeIdString;
			if (!isset($sets[$linkSetIdString]['types'][$linkTypeIdString]))
				$sets[$linkSetIdString]['types'][$linkTypeIdString] = array();

			// To start with, enable the first section in each group.
			// Later, we may want to check if the target schedule already has
			// this course added and select the already-added versions so that
			// a second addition will update that course's sections rather than
			// add them again.
			if (!count($sets[$linkSetIdString]['types'][$linkTypeIdString]))
				$info['selected'] = true;

			if ($schedule->includes($offering->getId())) {
				if (count($sets[$linkSetIdString]['types'][$linkTypeIdString]))
					$sets[$linkSetIdString]['types'][$linkTypeIdString][0]['selected'] = false;
				$info['selected'] = true;
				$sets[$linkSetIdString]['selected'] = true;
			}

			// Add the info to the appropriate set.
			$sets[$linkSetIdString]['types'][$linkTypeIdString][] = $info;
		}
		// Use indexted arrays.
// 		foreach ($sets as $key => $types) {
// 			$sets[$key] = array_values($types);
// 		}
// 		$sets = array_values($sets);

	print json_encode($sets);
	}

	/**
	 * Answer an array of events for the schedule in JSON format
	 *
	 * @return void
	 * @access public
	 * @since 8/5/10
	 */
	public function eventsjsonAction () {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$this->getResponse()->setHeader('Content-Type', 'text/plain');


		$schedules = new Schedules(Zend_Registry::get('db'),  $this->_helper->auth->getHelper()->getUserId(), $this->_helper->osid->getCourseManager());
		$schedule = $schedules->getSchedule($this->_getParam('schedule_id'));

		$thisWeek = Week::current();

		$events = $schedule->getWeeklyEvents();
		foreach ($events as $i => &$event) {
			$event['title'] = $event['name'];

			if ($event['location'])
				$event['title'] .= '<br/>'.$event['location'];

			if ($event['crn'])
				$event['title'] .= " - CRN: ".$event['crn'];

			$day = $thisWeek->asDateAndTime();
			if ($event['dayOfWeek'])
				$day = $day->plus(Duration::withDays($event['dayOfWeek']));

			$dateTime = $day->plus(Duration::withSeconds($event['startTime']));
			$event['start'] = $dateTime->ymdString().' '.$dateTime->hmsString();
			$dateTime = $day->plus(Duration::withSeconds($event['endTime']));
			$event['end'] = $dateTime->ymdString().' '.$dateTime->hmsString();

			$event['id'] = $i;
		}

		print json_encode($events);
	}

	/**
	 * Answer a PNG Image of the schedule
	 *
	 * @return void
	 * @access public
	 * @since 8/5/10
	 */
	public function pngAction () {
		$this->_helper->layout->disableLayout();

		$this->initializeScheduleImage();

		$this->getResponse()->setHeader('Content-Type', 'image/png');
	}

	/**
	 * Intitialize our schedule with the ID passed.
	 *
	 * @return void
	 */
	protected function initializeSchedule () {
		if (!isset($this->view->schedule)) {
			$schedules = new Schedules(Zend_Registry::get('db'),  $this->_helper->auth->getHelper()->getUserId(), $this->_helper->osid->getCourseManager());
			$this->view->schedule = $schedules->getSchedule($this->_getParam('schedule_id'));
		}
	}

	/**
	 * Initialize a schedule image.
	 *
	 * @return void
	 */
	protected function initializeScheduleImage () {
		$this->initializeSchedule();

		$config = Zend_Registry::getInstance()->config;
		$this->view->fontFile = $config->schedules->image->font_file;

		$this->view->events = $this->view->schedule->getWeeklyEvents();

		$this->view->minTime = $this->view->schedule->getEarliestTime();
		if ($this->view->schedule->getLatestTime()) {
			$this->view->maxTime = $this->view->schedule->getLatestTime();
		} else {
			$this->view->minTime = 9 * 3600;
			$this->view->maxTime = 17 * 3600;
		}

		$this->view->height = 600;
	}

	/**
	 * Answer a print-view of the schedule
	 *
	 * @return void
	 * @access public
	 * @since 8/5/10
	 */
	public function printAction () {
		$this->_helper->layout->disableLayout();

		$this->initializeSchedule();
	}

	/**
	 * Answer true if sending email is enabled.
	 *
	 * @return boolean
	 */
	protected function emailEnabled () {
		$config = Zend_Registry::getInstance()->config;
		if (!isset($config->schedules->email->enabled) ||  !$config->schedules->email->enabled) {
			return false;
		}

		// Allow enabling email for only some users
		if (!empty($config->schedules->email->allowed_groups)) {
			$userGroups = $this->_helper->auth()->getUserGroups();
			foreach ($config->schedules->email->allowed_groups as $group) {
				if (in_array($group, $userGroups))
					return true;
			}
			return false;
		}

		return true;
	}

	/**
	 * Answer the email address to send mail from.
	 *
	 * @return string
	 */
	protected function getFromEmail () {
		$config = Zend_Registry::getInstance()->config;
		$name = $this->_helper->auth()->getUserDisplayName();

		if (isset($config->schedules->email->send_mail_as_user) && $config->schedules->email->send_mail_as_user) {
			return $name.' <'.$this->_helper->auth()->getUserEmail().'>';
		} else if (isset($config->schedules->email->send_mail_as) && $config->schedules->email->send_mail_as) {
			return $name.' - Catalog <'.$config->schedules->email->send_mail_as.'>';
		} else {
			throw new Exception ('schedules.email.send_mail_as_user is false, but schedules.email.send_mail_as is not set (in frontend_config.ini).');
		}
	}

	/**
	 * Email a schedule to one or more addresses.
	 *
	 * @return void
	 */
	public function emailAction () {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		$this->getResponse()->setHeader('Content-Type', 'text/plain');

		try {
			if (!$this->emailEnabled()) {
				throw new Exception("Emailing of schedules is not enabled in frontend_config.ini.");
			}
			$this->verifyChangeRequest();

			$this->initializeSchedule();
			$this->view->messageBody = $this->_getParam('message');

			// Generate the text version of the email.
			$this->render('email-text');
			$text = $this->getResponse()->getBody();
			$this->getResponse()->setBody('');

			// Generate the html version of the email.
			$this->render('email-html');
			$html = $this->getResponse()->getBody();
			$this->getResponse()->setBody('');

			// Generate the Schedule image.
			$this->initializeScheduleImage();
			$this->render('generate-image');
			ob_start();
			imagepng($this->view->image, null, 5);
			imagedestroy($this->view->image);
			$imageData = ob_get_clean();

			// To
			$to = $this->_helper->auth()->getUserEmail();
			if (strlen(trim($this->_getParam('to'))) && trim($this->_getParam('to')) != $to) {
				$to .= ', '.trim($this->_getParam('to'));
			}

			// Build the email
			$mime = new Mail_mime();
			$headers = array(
				'From'		=> $this->getFromEmail(),
				'Reply-To'	=> $this->_helper->auth()->getUserEmail(),
				'CC'		=> $this->_helper->auth()->getUserEmail(),
				'Subject'	=> preg_replace('/[^\w \'"&-_.,\/*%#$@!()=+:;<>?]/', '', $this->_getParam('subject')),
			);
			$mime->setTXTBody($text);
			$mime->setHTMLBody($html);
			$mime->addHTMLImage($imageData, 'image/png', 'schedule_image.png', false);
			$mime->addAttachment($imageData, 'image/png', 'schedule_image.png', false);


			// Send the email
			$body = $mime->get();
			$headers = $mime->headers($headers);

			$mail = Mail::factory('mail');
			$result = $mail->send($to, $headers, $body);

			if ($result === true) {
				print "Email sent.";
			} else {
				throw $result;
			}
		} catch (Exception $e) {
			error_log($e->getMessage());

			$this->getResponse()->setHttpResponseCode(500);
			$this->getResponse()->setBody($e->getMessage());
		}
	}
}
