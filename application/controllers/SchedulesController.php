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
			$this->_forward('login', 'auth');
		}		
	}
	
    public function indexAction()
    {
    	// Set up data for the menu rendering
    	if ($this->_getParam('catalog')) {
			$catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));	
			$termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
		} else {
			$termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSession();
			$catalogId = $termLookupSession->getCourseCatalogId();
		}
		$this->setSelectedCatalogId($catalogId);
		
		// Catalogs
		$catalogLookupSession = $this->_helper->osid->getCourseManager()->getCourseCatalogLookupSession();
		$this->view->catalogs = $catalogLookupSession->getCourseCatalogs();
		
		// Load all terms for our selection control
		$termLookupSession->useFederatedCourseCatalogView();
		$terms = $termLookupSession->getTerms();
		$termCatalogSession = $this->_helper->osid->getCourseManager()->getTermCatalogSession();
		$this->view->terms = array();
		while ($terms->hasNext()) {
			$term = $terms->getNextTerm();
			$termCatalogId = $termCatalogSession->getCatalogIdsByTerm($term->getId())->getNextId();
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
		if ($this->_getParam('term') == 'ANY') {
			// Don't set a term
		} else if (!$this->_getParam('term') || $this->_getParam('term') == 'CURRENT') {
			$this->view->selectedTermId = $this->_helper->osidTerms->getCurrentTermId($catalogId);
		} else {
			$this->view->selectedTermId = $this->_helper->osidId->fromString($this->_getParam('term'));
		}
		$this->view->termIdString = $this->_helper->osidId->toString($this->view->selectedTermId);
		
		
		// Load the bookmarks for the selected catalog/terms
		$bookmarks = new Bookmarks(Zend_Registry::get('db'),  $this->_helper->auth->getHelper()->getUserId(), $this->_helper->osid->getCourseManager());
		if (isset($this->view->selectedTermId)) {
			$this->view->bookmarked_courses = $bookmarks->getBookmarkedCoursesInCatalogForTerm($catalogId, $this->view->selectedTermId);
		} else {
			$this->view->bookmarked_courses = $bookmarks->getAllBookmarkedCourses();
		}
		
		// Load the Schedules for the selected catalog/terms
		$schedules = new Schedules(Zend_Registry::get('db'),  $this->_helper->auth->getHelper()->getUserId(), $this->_helper->osid->getCourseManager());
		$this->view->schedules = $schedules->getSchedulesByTerm($this->view->selectedTermId);
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
    	$lookupSession->useFederatedView();
		$linkType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:link');
		
		$offering = $lookupSession->getCourseOffering($offeringIds[0]);
    	$course = $offering->getCourse();
    	$termId = $offering->getTermId();
    	
    	$requiredLinkIds = $course->getRequiredLinkIdsForTerm($termId);
    	$requiredLinkInfo = array();
    	while ($requiredLinkIds->hasNext()) {
    		$requiredLinkInfo[] = array(
    			'id' => $requiredLinkIds->getNextId(),
    			'found' => false,
    		);
    	}
    	
    	foreach ($offeringIds as $id) {
    		$offering = $lookupSession->getCourseOffering($id);
    		
    		// Check that we are adding a single section from each link-group.
    		$linkRecord = $offering->getCourseOfferingRecord($linkType);
    		$linkId = $linkRecord->getLinkId();
    		$checked = false;
    		foreach ($requiredLinkInfo as $key => $info) {
    			if ($info['id']->isEqual($linkId)) {
    				$checked = true;
    				if ($info['found'])
    					throw new Exception('A second section from the same link-group is selected.');
    				else
    					$requiredLinkInfo[$key]['found'] = true;
    			}
    		}
    		if (!$checked)
    			throw new Exception("The link-group id of the offering '".$linkId->getIdentifier()."' wasn't in the required list.");
    		
    		// Also check that the sections are from the same course and term.
    		if (!$offering->getTermId()->isEqual($termId))
    			throw new Exception("Trying to add offerings from multiple terms.");
    		if (!$offering->getCourseId()->isEqual($course->getId()))
    			throw new Exception("Trying to add offerings from multiple courses.");
   		}
   		// Check that we are adding a section for each link-group.
   		foreach ($requiredLinkInfo as $info) {
   			if (!$info['found'])
   				throw new Exception("No offering was added for the link-group ".$info['id']->getIdentifier());
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
    	$lookupSession->useFederatedView();
		
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
		
		$groups = array();
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
			
			$info = array(
				'id' 			=> $this->_helper->osidId->toString($offering->getId()),
				'name'			=> $offering->getDisplayName(),
				'type'			=> $offering->getGenusType()->getDisplayName(),
				'instructor'	=> $instructorString,
				'location' 		=> $offering->getLocationInfo(), 
				'schedule' 		=> $offering->getScheduleInfo(),
			);
			
			
			// Get the link id and ensure that we have a group for it.
			$linkRecord = $offering->getCourseOfferingRecord($linkType);
			$linkIdString = $this->_helper->osidId->toString($linkRecord->getLinkId());
			if (!isset($groups[$linkIdString]))
				$groups[$linkIdString] = array();
			
			// To start with, enable the first section in each group.
			// Later, we may want to check if the target schedule already has
			// this course added and select the already-added versions so that 
			// a second addition will update that course's sections rather than
			// add them again.
			if (!count($groups[$linkIdString]))
				$info['selected'] = true;
			
			if ($schedule->includes($offering->getId())) {
				if (count($groups[$linkIdString]))
					$groups[$linkIdString][0]['selected'] = false;
				$info['selected'] = true;
			}
			
			// Add the info to the appropriate group.
			$groups[$linkIdString][] = $info;
		}
		
		$groups = array_values($groups);
    	
    	print json_encode($groups);
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
			
			$day = $thisWeek->asDateAndTime();
			if ($event['dayOfWeek'])
				$day = $day->plus(Duration::withDays($event['dayOfWeek']));
			
			$event['start'] = $day->plus(Duration::withSeconds($event['startTime']))->printableString();
			$event['end'] = $day->plus(Duration::withSeconds($event['endTime']))->printableString();
			
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
    	
		$schedules = new Schedules(Zend_Registry::get('db'),  $this->_helper->auth->getHelper()->getUserId(), $this->_helper->osid->getCourseManager());
		$schedule = $schedules->getSchedule($this->_getParam('schedule_id'));
		
		$this->view->events = $schedule->getWeeklyEvents();
		
		$this->view->minTime = $schedule->getEarliestTime();
		if ($schedule->getLatestTime()) {
			$this->view->maxTime = $schedule->getLatestTime();
		} else {
			$this->view->minTime = 9 * 3600;
			$this->view->maxTime = 17 * 3600;
		}
		
		$this->getResponse()->setHeader('Content-Type', 'image/png');
    }
}
