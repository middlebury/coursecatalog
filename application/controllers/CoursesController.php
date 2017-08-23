<?php
/**
 * @since 4/21/09
 * @package catalog.controllers
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * A controller for working with courses
 *
 * @since 4/21/09
 * @package catalog.controllers
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class CoursesController
	extends AbstractCatalogController
{

	/**
	 * Initialize object
	 *
	 * Called from {@link __construct()} as final step of object instantiation.
	 *
	 * @return void
	 */
	public function init() {
		$this->alternateType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:alternates');

		parent::init();
	}

	/**
	 * Print out a list of all courses
	 *
	 * @return void
	 * @access public
	 * @since 4/21/09
	 */
	public function listAction () {
		if ($this->_getParam('catalog')) {
			$catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));
			$lookupSession = $this->_helper->osid->getCourseManager()->getCourseLookupSessionForCatalog($catalogId);
			$this->view->title = 'Courses in '.$lookupSession->getCourseCatalog()->getDisplayName();
		} else {
			$lookupSession = $this->_helper->osid->getCourseManager()->getCourseLookupSession();
			$this->view->title = 'Courses in All Catalogs';
		}
		$lookupSession->useFederatedCourseCatalogView();

		$this->view->courses = $lookupSession->getCourses();

		$this->setSelectedCatalogId($lookupSession->getCourseCatalogId());
		$this->view->headTitle($this->view->title);

		$this->view->menuIsCourses = true;
	}

	/**
	 * View a catalog details
	 *
	 * @return void
	 * @access public
	 * @since 4/21/09
	 */
	public function viewAction () {
		$id = $this->_helper->osidId->fromString($this->_getParam('course'));
		$lookupSession = $this->_helper->osid->getCourseManager()->getCourseLookupSession();
		$lookupSession->useFederatedCourseCatalogView();
		$this->view->course = $lookupSession->getCourse($id);

		// Load the topics into our view
		 $this->loadTopics($this->view->course->getTopics());

		// Set the selected Catalog Id.
		$catalogSession = $this->_helper->osid->getCourseManager()->getCourseCatalogSession();
		$catalogIds = $catalogSession->getCatalogIdsByCourse($id);
		if ($catalogIds->hasNext()) {
			$this->setSelectedCatalogId($catalogIds->getNextId());
		}

		// Set the title
		$this->view->title = $this->view->course->getDisplayName();
		$this->view->headTitle($this->view->title);

		$this->view->menuIsCourses = true;

		// Alternates
		 if ($this->view->course->hasRecordType($this->alternateType)) {
			 $record = $this->view->course->getCourseRecord($this->alternateType);
			 if ($record->hasAlternates()) {
				 $this->view->alternates = $record->getAlternates();
			 }
		 }

		// Term
		if ($this->_getParam('term')) {
			$termId = $this->_helper->osidId->fromString($this->_getParam('term'));
			$termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSession();
			$termLookupSession->useFederatedCourseCatalogView();
			$this->view->term = $termLookupSession->getTerm($termId);

			$allParams = array();
			$allParams['course'] = $this->_getParam('course');
			if ($this->getSelectedCatalogId())
				$allParams['catalog'] = $this->_helper->osidId->toString($this->getSelectedCatalogId());
			$this->view->offeringsForAllTermsUrl = $this->_helper->url('view', 'courses', null, $allParams);
		} else {
			$this->view->linkTermId = $this->_helper->osidTerms->getNextOrLatestTermId($this->getSelectedCatalogId());
		}

		// Bookmarked Courses and Schedules
		 $this->view->bookmarks_CourseId = $this->view->course->getId();

		// offerings
		$this->view->offeringsTitle = "Sections";
		$offeringLookupSession = $this->_helper->osid->getCourseManager()->getCourseOfferingLookupSession();
		$offeringLookupSession->useFederatedCourseCatalogView();
		if (isset($this->view->term)) {
			$this->view->offerings = $offeringLookupSession->getCourseOfferingsByTermForCourse(
				$this->view->term->getId(),
				$id
			);
		} else {
			$this->view->offerings = $offeringLookupSession->getCourseOfferingsForCourse($id);
		}
	}

	/**
	 * Get an XML view of a course.
	 *
	 * @return void
	 */
	public function viewxmlAction () {
		$this->_helper->layout->disableLayout();
		$this->getResponse()->setHeader('Content-Type', 'text/xml');

		$this->viewAction();
	}

	/**
	 * Search for courses
	 *
	 * @return void
	 * @access public
	 * @since 6/15/09
	 */
	public function searchxmlAction () {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		if (!$this->_getParam('catalog')) {
			header('HTTP/1.1 400 Bad Request');
			print "A catalog must be specified.";
			exit;
		}
		try {
			$catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));
			$searchSession = $this->_helper->osid->getCourseManager()->getCourseOfferingSearchSessionForCatalog($catalogId);
		} catch (osid_InvalidArgumentException $e) {
			header('HTTP/1.1 400 Bad Request');
			print "The catalog id specified was not of the correct format.";
			exit;
		} catch (osid_NotFoundException $e) {
			header('HTTP/1.1 404 Not Found');
			print "The catalog id specified was not found.";
			exit;
		}

		$keywords = trim($this->_getParam('keywords'));
		$searchUrl = $this->_helper->pathAsAbsoluteUrl($this->_helper->url('search', 'offerings', null, array('catalog' => $this->_getParam('catalog'), 'keywords' => $keywords, 'submit' => 'Search')));

		header('Content-Type: text/xml');
		print '<?xml version="1.0" encoding="utf-8" ?>
<rss version="2.0" xmlns:catalog="http://www.middlebury.edu/course_catalog">
	<channel>
		<title>Course Search: "'.htmlspecialchars($keywords).'"</title>
		<link>'.$searchUrl.'</link>
		<description></description>
		<lastBuildDate>'.date('r').'</lastBuildDate>
		<generator>Course Catalog</generator>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>

';
		$courses = array();
		// Fetch courses
		if (strlen($keywords)) {
			// For now we will do an offering search and return courses
			// only from it. If a course search session is available, it would
			// be preferable to use that.
			$query = $searchSession->getCourseOfferingQuery();
			$query->matchKeyword(
				$keywords,
				new phpkit_type_URNInetType("urn:inet:middlebury.edu:search:wildcard"),
				true);
			$offerings = $searchSession->getCourseOfferingsByQuery($query);

			while ($offerings->hasNext() && count($courses) <= 20) {
				$offering = $offerings->getNextCourseOffering();
				$courseIdString = $this->_helper->osidId->toString($offering->getCourseId());
				if (!isset($courses[$courseIdString])) {
					try {
						$courses[$courseIdString] = $offering->getCourse();
					} catch (osid_OperationFailedException $e) {
// 						print "\n<item><title>Failure on ".$offering->getDisplayName()."</title><description><![CDATA[<pre>OfferingId:\n".print_r($offering->getId(), true)."\n\nCourseId:\n".print_r($offering->getCourseId(), true)."</pre>]]></description></item>";
					}
				}
			}
		}

		// Print out courses as items.
		foreach ($courses as $courseIdString => $course) {
			print "\n\t\t<item>";

			print "\n\t\t\t<title>";
			print htmlspecialchars($course->getDisplayName().' - '.$course->getTitle());
			print "</title>";

			print "\n\t\t\t<link>";
			print $this->_helper->pathAsAbsoluteUrl($this->_helper->url('view', 'courses', null, array('catalog' => $this->_getParam('catalog'), 'course' => $courseIdString)));
			print "</link>";

			print "\n\t\t\t<guid isPermaLink='true'>";
			print $this->_helper->pathAsAbsoluteUrl($this->_helper->url('view', 'courses', null, array('catalog' => $this->_getParam('catalog'), 'course' => $courseIdString)));
			print "</guid>";

			print "\n\t\t\t<description><![CDATA[";
			print $course->getDescription();
			print "]]></description>";
			print "\n\t\t\t<catalog:id>".$courseIdString."</catalog:id>";

			print "\n\t\t</item>";
		}

		print '
	</channel>
</rss>';

		exit;
	}


	/**
	 * Search for courses
	 *
	 * @return void
	 * @access public
	 * @since 6/15/09
	 */
	public function topicxmlAction () {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		if (!$this->_getParam('catalog')) {
			header('HTTP/1.1 400 Bad Request');
			print "A catalog must be specified.";
			exit;
		}
		try {
			$catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));
			$searchSession = $this->_helper->osid->getCourseManager()->getCourseSearchSessionForCatalog($catalogId);

			$this->termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
		} catch (osid_InvalidArgumentException $e) {
			header('HTTP/1.1 400 Bad Request');
			print "The catalog id specified was not of the correct format.";
			exit;
		} catch (osid_NotFoundException $e) {
			header('HTTP/1.1 404 Not Found');
			print "The catalog id specified was not found.";
			exit;
		}

		if (!$this->_getParam('topic')) {
			header('HTTP/1.1 400 Bad Request');
			print "A topic must be specified.";
			exit;
		}

		$topicsIds = array();
		if (is_array($this->_getParam('topic'))) {
			foreach ($this->_getParam('topic') as $idString) {
				$topicIds[] = $this->_helper->osidId->fromString($idString);
			}
		} else {
			$topicIds[] = $this->_helper->osidId->fromString($this->_getParam('topic'));
		}

		$searchUrl = $this->_helper->pathAsAbsoluteUrl($this->_helper->url('search', 'offerings', null, array()));

		// Fetch courses
		$query = $searchSession->getCourseQuery();

		$topicRecord = $query->getCourseQueryRecord(new phpkit_type_URNInetType("urn:inet:middlebury.edu:record:topic"));
		foreach ($topicIds as $topicId) {
			$topicRecord->matchTopicId($topicId, true);
		}

		// Limit by location
		$locationIds = array();
		if (is_array($this->_getParam('location'))) {
			foreach ($this->_getParam('location') as $idString) {
				$locationIds[] = $this->_helper->osidId->fromString($idString);
			}
		} else if ($this->_getParam('location')) {
			$locationIds[] = $this->_helper->osidId->fromString($this->_getParam('location'));
		}
		$locationRecord = $query->getCourseQueryRecord(new phpkit_type_URNInetType("urn:inet:middlebury.edu:record:location"));
		foreach ($locationIds as $locationId) {
			$locationRecord->matchLocationId($locationId, true);
		}

		// Limit to just active courses
		$query->matchGenusType(new phpkit_type_URNInetType("urn:inet:middlebury.edu:status-active"), true);

		$courses = $searchSession->getCoursesByQuery($query);

		$topicLookup = $this->_helper->osid->getCourseManager()->getTopicLookupSession();
		$topicLookup->useFederatedCourseCatalogView();
		$topic = $topicLookup->getTopic($topicId);

		$recentCourses = new Helper_RecentCourses_Department($courses);
		if ($this->_getParam('cutoff')) {
			$recentCourses->setRecentInterval(new DateInterval($this->_getParam('cutoff')));
		}
		$this->outputCourseFeed($recentCourses, htmlentities('Courses in  '.$topic->getDisplayName()), $searchUrl);

	}

	/**
	 * Build a list of courses associated with an instructor.
	 *
	 * The process is:
	 *   1. Find sections taught by the instructor in the time-frame (default is past 4 years).
	 *   3. For each section...
	 *      a. Get the cross-listed sections from SSB_XLST
	 *      b. Take the section plus its cross-listed sections, get their course
	 *         entries and merge them into a single result.
	 *
	 * @return void
	 * @access public
	 * @since 6/15/09
	 */
	public function instructorxmlAction () {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		if (!$this->_getParam('catalog')) {
			$offeringSearchSession = $this->_helper->osid->getCourseManager()->getCourseOfferingSearchSession();
			$offeringSearchSession->useFederatedCourseCatalogView();
			$courseLookupSession = $this->_helper->osid->getCourseManager()->getCourseLookupSession();
			$courseLookupSession->useFederatedCourseCatalogView();

			// Allow term current/past to be limited to a certain catalog while courses are fetched from many
			if ($this->_getParam('term_catalog')) {
				$catalogId = $this->_helper->osidId->fromString($this->_getParam('term_catalog'));
				$this->termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
			}
			// fall back to terms from any catalog.
			else {
				$this->termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSession();
				$this->termLookupSession->useFederatedCourseCatalogView();
			}
		} else {
			try {
				$catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));
				$offeringSearchSession = $this->_helper->osid->getCourseManager()->getCourseOfferingSearchSessionForCatalog($catalogId);
				$courseLookupSession = $this->_helper->osid->getCourseManager()->getCourseLookupSessionForCatalog($catalogId);

				$this->termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
			} catch (osid_InvalidArgumentException $e) {
				throw new osid_InvalidArgumentException("The catalog id specified was not of the correct format.");
			} catch (osid_NotFoundException $e) {
				throw new osid_NotFoundException("The catalog id specified was not found.");
				exit;
			}
		}

		$instructor = trim($this->_getParam('instructor'));

		if (!$instructor || !strlen($instructor)) {
			// Make sure that this error response is cacheable.
			$this->setCacheControlHeaders();
			$this->getResponse()->sendHeaders();

			throw new InvalidArgumentException("An instructor must be specified.");
		}

		$instructorId = $this->_helper->osidId->fromString('resource/person/'.$instructor);
		$searchUrl = $this->_helper->pathAsAbsoluteUrl($this->_helper->url('view', 'resources', null, array('catalog' => $this->_getParam('catalog'), 'resource' => 'resouce/person/'.$instructor)));

		$resourceLookup = $this->_helper->osid->getCourseManager()->getResourceManager()->getResourceLookupSession();
		try {
			$instructorResource = $resourceLookup->getResource($instructorId);
		} catch (osid_NotFoundException $e) {
			// Make sure that this error response is cacheable.
			$this->setCacheControlHeaders();
			$this->getResponse()->sendHeaders();

			throw $e;
		}

		// Fetch Offerings
		$query = $offeringSearchSession->getCourseOfferingQuery();

		$instructorRecord = $query->getCourseOfferingQueryRecord(new phpkit_type_URNInetType("urn:inet:middlebury.edu:record:instructors"));
		$instructorRecord->matchInstructorId($instructorId, true);

		$courseOfferings = $offeringSearchSession->getCourseOfferingsByQuery($query);

		$recentCourses = new Helper_RecentCourses_Instructor($courseOfferings, $courseLookupSession);
		if ($this->_getParam('cutoff')) {
			$recentCourses->setRecentInterval(new DateInterval($this->_getParam('cutoff')));
		}
		$this->outputCourseFeed($recentCourses, 'Courses taught by '.$instructorResource->getDisplayName(), $searchUrl);

	}

	/**
	 * Output an RSS feed of courses from results
	 *
	 * @param Helper_RecentCourses_Abstract $recentCourses
	 * @param string $title
	 * @param string $url
	 * @return void
	 * @access protected
	 * @since 10/19/09
	 */
	protected function outputCourseFeed (Helper_RecentCourses_Interface $recentCourses, $title, $url) {
		// Set our cache-control headers since we will be flushing content soon.
		$this->setCacheControlHeaders();
		$this->getResponse()->sendHeaders();

		// Close the session before we send headers and content.
		session_write_close();


		header('Content-Type: text/xml');
		print '<?xml version="1.0" encoding="utf-8" ?>
<rss version="2.0" xmlns:catalog="http://www.middlebury.edu/course_catalog">
	<channel>
		<title>'.htmlspecialchars($title).'</title>
		<link>'.$url.'</link>
		<description></description>
		<lastBuildDate>'.date('r').'</lastBuildDate>
		<generator>Course Catalog</generator>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>

';

		while (ob_get_level()) {
			ob_end_flush();
		}
		flush();

		// Set the next and previous terms
		$currentTermId = $this->_helper->osidTerms->getCurrentTermId($this->termLookupSession->getCourseCatalogId());
		$currentTerm = $this->termLookupSession->getTerm($currentTermId);
		$currentEndTime = $this->DateTime_getTimestamp($currentTerm->getEndTime());

// 		print "<description><![CDATA[";
// 		print ($courses->debug());
// 		print "]]></description>";

		$catalogSession = $this->_helper->osid->getCourseManager()->getCourseCatalogSession();
		$termsType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:record:terms");

// 		foreach ($groups as $key => $group) {
// 			print "\n$key";
// 			foreach ($group as $course) {
// 				print "\n\t".$this->_helper->osidId->toString($course->getId());
// 			}
// 		}

		foreach ($recentCourses->getPrimaryCourses() as $course) {
			$courseIdString = $this->_helper->osidId->toString($course->getId());

			print "\n\t\t<item>";

			print "\n\t\t\t<title>";
			$alternates = $recentCourses->getAlternatesForCourse($course);
			$name = $course->getDisplayName();
			foreach ($alternates as $alt) {
				$name .= ' / '. $alt->getDisplayName();
			}
			print htmlspecialchars($name.' - '.$course->getTitle());
			print "</title>";

			print "\n\t\t\t<link>";
			$catalog = $catalogSession->getCatalogIdsByCourse($course->getId());
			if ($catalog->hasNext())
				$catalogIdString = $this->_helper->osidId->toString($catalog->getNextId());
			else
				$catalogIdString = null;
			print $this->_helper->pathAsAbsoluteUrl($this->_helper->url('view', 'courses', null, array('catalog' => $catalogIdString, 'course' => $courseIdString)));
			print "</link>";

			print "\n\t\t\t<guid isPermaLink='true'>";
			print $this->_helper->pathAsAbsoluteUrl($this->_helper->url('view', 'courses', null, array('catalog' => $catalogIdString, 'course' => $courseIdString)));
			print "</guid>";

			print "\n\t\t\t<description><![CDATA[";
			print $course->getDescription();
			print "]]></description>";
			print "\n\t\t\t<catalog:id>".$courseIdString."</catalog:id>";

			$recentTerms = $recentCourses->getTermsForCourse($course);
			if (count($recentTerms)) {
				$termStrings = array();
				foreach ($recentTerms as $term) {
					print "\n\t\t\t<catalog:term id=\"".$this->_helper->osidId->toString($term->getId())."\"";
					if ($term->getId()->isEqual($currentTermId)) {
						print ' type="current"';
					} else if ($currentEndTime < $this->DateTime_getTimestamp($term->getEndTime())) {
						print ' type="future"';
					} else {
						print ' type="past"';
					}
					print ">".$term->getDisplayName()."</catalog:term>";
				}
			}

			$allTopics = $this->_helper->topics->topicListAsArray($course->getTopics());
			$topicType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/department");
			$topicTypeString = $this->_helper->osidType->toString($topicType);
			$topics = $this->_helper->topics->filterTopicsByType($allTopics, $topicType);
			foreach ($topics as $topic) {
				$topicParams['topic'] = $this->_helper->osidId->toString($topic->getId());
				print "\n\t\t\t<catalog:topic type=\"".$topicTypeString."\" id=\"".$this->_helper->osidId->toString($topic->getId())."\" href=\"".$this->_helper->pathAsAbsoluteUrl($this->view->url($topicParams))."\">";
				print $this->view->escape($topic->getDisplayName());
				print "</catalog:topic> ";
			}

			$topicType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/requirement");
			$topicTypeString = $this->_helper->osidType->toString($topicType);
			$topics = $this->_helper->topics->filterTopicsByType($allTopics, $topicType);
			foreach ($topics as $topic) {
				$topicParams['topic'] = $this->_helper->osidId->toString($topic->getId());
				print "\n\t\t\t<catalog:topic type=\"".$topicTypeString."\" id=\"".$this->_helper->osidId->toString($topic->getId())."\" href=\"".$this->_helper->pathAsAbsoluteUrl($this->view->url($topicParams))."\">";
				print $this->view->escape($topic->getDisplayName());
				print "</catalog:topic> ";
			}

			$topicType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/level");
			$topicTypeString = $this->_helper->osidType->toString($topicType);
			$topics = $this->_helper->topics->filterTopicsByType($allTopics, $topicType);
			foreach ($topics as $topic) {
				$topicParams['topic'] = $this->_helper->osidId->toString($topic->getId());
				print "\n\t\t\t<catalog:topic type=\"".$topicTypeString."\" id=\"".$this->_helper->osidId->toString($topic->getId())."\" href=\"".$this->_helper->pathAsAbsoluteUrl($this->view->url($topicParams))."\">";
				print $this->view->escape($topic->getDisplayName());
				print "</catalog:topic> ";
			}

			print "\n\t\t</item>";
			flush();
		}

		print '
	</channel>
</rss>';
		exit;
	}

	/**
	 * Answer a list of all recent courses
	 *
	 * @return void
	 * @access public
	 * @since 6/15/09
	 */
	public function allrecentcoursesAction () {
		if (!$this->_getParam('catalog')) {
			header('HTTP/1.1 400 Bad Request');
			print "A catalog must be specified.";
			exit;
		}

		$config = Zend_Registry::getInstance()->config;
		if ($config->catalog->print_password && !$this->_getParam('password')) {
			header('HTTP/1.1 400 Bad Request');
			print "A password must be specified.";
			exit;
		}
		if ($config->catalog->print_password && $this->_getParam('password') != $config->catalog->print_password) {
			header('HTTP/1.1 400 Bad Request');
			print "Invalid password specified.";
			exit;
		}

		if ($config->catalog->print_max_exec_time)
			ini_set('max_execution_time', $config->catalog->print_max_exec_time);

		try {
			$catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));
			$this->courseSearchSession = $this->_helper->osid->getCourseManager()->getCourseSearchSessionForCatalog($catalogId);
			$this->offeringSearchSession = $this->_helper->osid->getCourseManager()->getCourseOfferingSearchSessionForCatalog($catalogId);

			$this->termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
		} catch (osid_InvalidArgumentException $e) {
			header('HTTP/1.1 400 Bad Request');
			print "The catalog id specified was not of the correct format.";
			exit;
		} catch (osid_NotFoundException $e) {
			header('HTTP/1.1 404 Not Found');
			print "The catalog id specified was not found.";
			exit;
		}

		try {
			$this->selectedTerms = array();
			// Get all offerings in the terms
			foreach ($this->_getParam('term') as $termIdString) {
				$termId = $this->_helper->osidId->fromString($termIdString);
				$this->selectedTerms[] = $termId;
			}
		} catch (osid_InvalidArgumentException $e) {
			header('HTTP/1.1 400 Bad Request');
			print "The term id specified was not of the correct format.";
			exit;
		}

		$sections = array();
		foreach ($config->catalog->print_sections as $i => $sectionConf) {
			$section = array('type' => $sectionConf->type);
			if ($sectionConf->type == 'h1') {
				if (strlen(trim($sectionConf->text)))
					$section['text'] = $sectionConf->text;
				else
					throw new InvalidArgumentException("catalog.print_sections.$i.text is missing.");
			} else if ($sectionConf->type == 'h2') {
				if (strlen(trim($sectionConf->text)))
					$section['text'] = $sectionConf->text;
				else
					throw new InvalidArgumentException("catalog.print_sections.$i.text is missing.");
			} else if ($sectionConf->type == 'page_content') {
				if (strlen(trim($sectionConf->url)))
					$section['url'] = $sectionConf->url;
				else
					throw new InvalidArgumentException("catalog.print_sections.$i.url is missing.");
			} else if ($sectionConf->type == 'courses') {
				if (strlen(trim($sectionConf->id)))
					$section['id'] = $this->_helper->osidId->fromString($sectionConf->id);
				else
					throw new InvalidArgumentException("catalog.print_sections.$i.id is missing.");
			} else {
				throw new InvalidArgumentException("catalog.print_sections.$i.type is '".$sectionConf->type."'. Must be one of h1, h2, page_content, or courses.");
			}

			$sections[] = $section;
		}

		$title = 'Course Catalog - ';
		$title .= $this->courseSearchSession->getCourseCatalog()->getDisplayName();
		$termNames = array();
		foreach ($this->selectedTerms as $termId) {
			$termNames[] = $this->termLookupSession->getTerm($termId)->getDisplayName();
		}
		if (count($termNames)) {
			$title .= ' - '.implode(', ', $termNames);
		}
		$this->view->title = $title;
		$this->view->sections = $sections;
		foreach ($this->view->sections as $key => &$section) {
			switch ($section['type']) {
				case 'h1':
					break;
				case 'h2':
					break;
				case 'text':
					break;
				case 'page_content':
					$section['content'] = $this->getRequirements($section['url']);
					break;
				case 'courses':
					$section['courses'] = $this->getCourses($section['id']);
					break;
				default:
					throw new Exception("Unknown section type ".$section['type']);
			}
		}

		$this->_helper->layout()->setLayout('minimal');
	}

	/**
	 * Answer requirements text
	 *
	 * @param $url
	 * @return string
	 * @access protected
	 * @since 4/26/10
	 */
	protected function getRequirements ($url) {
		$feedUrl = $url.'/feed';
		$feedDoc = new DOMDocument;
		$feedDoc->load($feedUrl);
		$xpath = new DOMXPath($feedDoc);
		$descriptions = $xpath->query('/rss/channel/item/description');
		ob_start();
		foreach ($descriptions as $description) {
			$body = $description->nodeValue;
			$body = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $body); // Strip out none-printable characters.
			print $body;
		}
		return ob_get_clean();
	}

	/**
	 * Print out the courses for a topic
	 *
	 * @param osid_id_Id $topicId
	 * @return void
	 * @access protected
	 * @since 4/26/10
	 */
	protected function getCourses (osid_id_Id $topicId) {
		$topic_courses = array();
		$offeringQuery = $this->offeringSearchSession->getCourseOfferingQuery();
		$offeringQuery->matchTopicId($topicId, true);
		foreach ($this->selectedTerms as $termId) {
			$offeringQuery->matchTermId($termId, true);
		}
		$offerings = $this->offeringSearchSession->getCourseOfferingsByQuery($offeringQuery);

		// Limit Courses to those offerings in the terms
		$query = $this->courseSearchSession->getCourseQuery();
		if ($offerings->hasNext()) {
			while ($offerings->hasNext()) {
				$query->matchCourseOfferingId($offerings->getNextCourseOffering()->getId(), true);
			}
		} else {
			return;
		}
		$search = $this->courseSearchSession->getCourseSearch();
		$order = $this->courseSearchSession->getCourseSearchOrder();
		$order->orderByDisplayName();
		$order->ascend();
		$search->orderCourseResults($order);
		$courses = $this->courseSearchSession->getCoursesBySearch($query, $search);

		$i = 0;
		while ($courses->hasNext()) {
			$course = $courses->getNextCourse();
			$i++;

			$courseIdString = $this->_helper->osidId->toString($course->getId());
			$this->printedCourseIds[] = $courseIdString;

			$topic_courses[] = $this->getCourseData($course);

// 			if ($i > 10)
// 				break;
		}
		return $topic_courses;
	}

	/**
	 * Print out a single course
	 *
	 * @param osid_course_Course $course
	 * @return void
	 * @access protected
	 * @since 4/28/10
	 */
	protected function getCourseData (osid_course_Course $course) {
		$data = new stdClass();
		$data->id = $this->_helper->osidId->toString($course->getId());
		$data->anchor = str_replace('/','_', $data->id);
		$data->sections = array();
		$data->display_name = $course->getDisplayName();
		$data->description = $course->getDescription();
		if (preg_match('#^<strong>([^\n\r]+)</strong>(?:\s*<br />(.*)|\s*)$#sm', $data->description, $matches)) {
			$data->title = $matches[1];
			if (isset($matches[2]))
				$data->description = trim($matches[2]);
			else
				$data->description = '';
		} else {
			$data->title = htmlspecialchars($course->getTitle());
		}

		$termsType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:record:terms");
		$termStrings = array();
		if ($course->hasRecordType($termsType)) {
			$termsRecord = $course->getCourseRecord($termsType);
			try {
				$terms = $termsRecord->getTerms();
				while ($terms->hasNext()) {
					$term = $terms->getNextTerm();
					// See if the term is in one of our chosen terms
					foreach ($this->selectedTerms as $selectedTermId) {
						if ($selectedTermId->isEqual($term->getId())) {
							$termStrings[$this->_helper->osidId->toString($term->getId())] = $term->getDisplayName();
						}
					}
				}
			} catch (osid_OperationFailedException $e) {
			}
		}
		$data->term_strings = $termStrings;

		$allTopics = $this->_helper->topics->topicListAsArray($course->getTopics());

		$reqs = array();
		$topicType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/requirement");
		$topicTypeString = $this->_helper->osidType->toString($topicType);
		$topics = $this->_helper->topics->filterTopicsByType($allTopics, $topicType);
		foreach ($topics as $topic) {
			$reqs[] = $this->view->escape($topic->getDisplayName());
		}
		$data->requirements = $reqs;

		/*********************************************************
		 * Section descriptions
		 *********************************************************/
		// Look for different Section Descriptions
		$offeringQuery = $this->offeringSearchSession->getCourseOfferingQuery();
		$offeringQuery->matchCourseId($course->getId(), true);
		$offeringQuery->matchGenusType(new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:offering/LCT"), true);
		$offeringQuery->matchGenusType(new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:offering/SEM"), true);
		foreach ($this->selectedTerms as $termId) {
			$offeringQuery->matchTermId($termId, true);
		}
		$order = $this->offeringSearchSession->getCourseOfferingSearchOrder();
		$order->orderByTerm();
		$order->ascend();
		$search = $this->offeringSearchSession->getCourseOfferingSearch();
		$search->orderCourseOfferingResults($order);
		$offerings = $this->offeringSearchSession->getCourseOfferingsBySearch($offeringQuery, $search);

		$sectionTerms = array();
		$sectionDescriptions = array();
		$sectionInstructors = array();
		$instructorsType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors');
		$namesType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:person_names');
		while ($offerings->hasNext()) {
			$offering = $offerings->getNextCourseOffering();
			$term = $offering->getTerm();
			$termIdString = $this->_helper->osidId->toString($term->getId());
			$sectionTerms[$termIdString] = $term->getDisplayName();
			if ($offering->getDescription() && $offering->getDescription() != $course->getDescription()) {
				$sectionDescriptions[$termIdString] = $offering->getDescription();
			}
			if ($offering->hasRecordType($instructorsType)) {
				$instructorsRecord = $offering->getCourseOfferingRecord($instructorsType);
				$instructors = $instructorsRecord->getInstructors();
				if (!isset($sectionInstructors[$termIdString]))
					$sectionInstructors[$termIdString] = array(
						'label' => $term->getDisplayName(),
						'instructors' => array(),
					);
				while($instructors->hasNext()) {
					$instructor = $instructors->getNextResource();
					$instructorIdString = $this->_helper->osidId->toString($instructor->getId());
					$sectionInstructors[$termIdString]['instructors'][$instructorIdString] = $instructor->getDisplayName();
					if ($instructor->hasRecordType($namesType)) {
						$nameRecord = $instructor->getResourceRecord($namesType);
						$sectionInstructors[$termIdString]['instructors'][$instructorIdString] = substr($nameRecord->getGivenName(), 0, 1).'. '.$nameRecord->getSurname();
					}
				}
			}
		}
		$data->instructors = $this->getInstructorText($sectionInstructors);

		$sectionDescriptionsText = '';
		// Replace the description with the one from the section if there is only one section.
		if (count($sectionDescriptions) == 1 && count($termStrings) == 1) {
			reset($sectionDescriptions);
			$data->description = current($sectionDescriptions);

		}
		// If there are multiple section descriptions, print them separately
		else if (count($sectionDescriptions)) {
			$data->description = '';
			foreach ($termStrings as $i => $desc) {
				$section_data = new stdClass;
				if (empty($sectionDescriptions[$i]))
					$section_data->description = $course->getDescription();
				else
					$section_data->description = $sectionDescriptions[$i];
				$section_data->term = $sectionTerms[$i];
				$section_data->instructors = $this->getInstructorText($sectionInstructors, $i);
				$data->sections[] = $section_data;
			}
		}

		/*********************************************************
		 * Crosslists
		 *********************************************************/
		$data->alternates = array();
		$alternateType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:record:terms");
		try {
			if ($course->hasRecordType($this->alternateType)) {
				$record = $course->getCourseRecord($this->alternateType);
				if ($record->hasAlternates()) {
					$alternates = $record->getAlternates();
					while ($alternates->hasNext()) {
						$alternate = $alternates->getNextCourse();

						$altInSelectedTerms = false;
						if ($alternate->hasRecordType($termsType)) {
							$termsRecord = $alternate->getCourseRecord($termsType);
							try {
								$terms = $termsRecord->getTerms();
								while ($terms->hasNext() && !$altInSelectedTerms) {
									$term = $terms->getNextTerm();
									// See if the term is in one of our chosen terms
									foreach ($this->selectedTerms as $selectedTermId) {
										if ($selectedTermId->isEqual($term->getId())) {
											$altInSelectedTerms = true;
											break;
										}
									}
								}
							} catch (osid_OperationFailedException $e) {
							}
						}
						if ($altInSelectedTerms) {
							$alt_data = new stdClass();
							$alt_data->display_name = $alternate->getDisplayName();
							$alt_data->id = $this->_helper->osidId->toString($alternate->getId());
							$alt_data->anchor = str_replace('/','_', $alt_data->id);
							if ($alternate->hasRecordType($this->alternateType)) {
								$alt_record = $alternate->getCourseRecord($this->alternateType);
								$alt_data->is_primary = $alt_record->isPrimary();
							}
							$data->alternates[] = $alt_data;
						}
					}
				}
			}
		} catch (osid_NotFoundException $e) {
		}

		return $data;
	}

	/**
	 * Answer an instructor listing string
	 *
	 * @param array $sectionInstructors
	 * @param string $termIdString
	 * @return string
	 */
	protected function getInstructorText (array $sectionInstructors, $termIdString = null) {
		if (empty($sectionInstructors))
			return '';
		foreach ($sectionInstructors as $termId => &$termInfo) {
			$ids = array_keys($termInfo['instructors']);
			sort($ids);
			$termInfo['hash'] = implode(':', $ids);
			$termInfo['instructorString'] = implode(', ', $termInfo['instructors']);
		}

		// Use just the instructors of the term passed.
		if ($termIdString) {
			if (empty($sectionInstructors[$termIdString]['instructorString']))
				return '';
			else
				return ' ('.$sectionInstructors[$termIdString]['instructorString'].')';
		}

		// For a course with just a single term, use that term's instructors
		if (count($sectionInstructors) === 1) {
			reset($sectionInstructors);
			$info = current($sectionInstructors);
			if (empty($info['instructorString']))
				return '';
			else
				return ' ('.$info['instructorString'].')';
		}

		// For courses with multiple terms, first find out if the instructor list is always the same.
		reset($sectionInstructors);
		$firstTerm = current($sectionInstructors);
		$firstHash = $firstTerm['hash'];
		$instructorListConstant = true;
		foreach ($sectionInstructors as $termId => $info) {
			if ($info['hash'] != $firstHash)
				$instructorListConstant = false;
		}
		// If we have the same instructor list each term, just use the first string.
		if ($instructorListConstant) {
			if (empty($firstTerm['instructorString']))
				return '';
			else
				return ' ('.$firstTerm['instructorString'].')';
		}
		// If we have a different instructor list each term, identify them.
		else {
			$termStrings = array();
			foreach ($sectionInstructors as $termId => $info) {
				if (!empty($info['instructorString']))
					$termStrings[] = $info['label'].': '.$info['instructorString'];
			}
			return ' ('.implode('; ', $termStrings).')';
		}
	}

	function DateTime_getTimestamp($dt) {
		$dtz_original = $dt -> getTimezone();
		$dtz_utc = new DateTimeZone("UTC");
		$dt -> setTimezone($dtz_utc);
		$year = intval($dt -> format("Y"));
		$month = intval($dt -> format("n"));
		$day = intval($dt -> format("j"));
		$hour = intval($dt -> format("G"));
		$minute = intval($dt -> format("i"));
		$second = intval($dt -> format("s"));
		$dt -> setTimezone($dtz_original);
		return gmmktime($hour,$minute,$second,$month,$day,$year);
	}

	function _textToLink($text) {
		return preg_replace('/[^a-z0-9.:]+/i', '-', $text);
	}
}
