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
			$catalogId = self::getOsidIdFromString($this->_getParam('catalog'));
			$lookupSession = self::getCourseManager()->getCourseLookupSessionForCatalog($catalogId);
			$this->view->title = 'Courses in '.$lookupSession->getCourseCatalog()->getDisplayName();
		} else {
			$lookupSession = self::getCourseManager()->getCourseLookupSession();
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
		$id = self::getOsidIdFromString($this->_getParam('course'));
		$lookupSession = self::getCourseManager()->getCourseLookupSession();
		$lookupSession->useFederatedCourseCatalogView();
		$this->view->course = $lookupSession->getCourse($id);
		
		// Load the topics into our view
 		$this->loadTopics($this->view->course->getTopics());
		
		// Set the selected Catalog Id.
		$catalogSession = self::getCourseManager()->getCourseCatalogSession();
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
		
		$this->render();
		
		// Term
		if ($this->_getParam('term')) {
			$termId = self::getOsidIdFromString($this->_getParam('term'));
			$termLookupSession = self::getCourseManager()->getTermLookupSession();
			$termLookupSession->useFederatedCourseCatalogView();
			$this->view->term = $termLookupSession->getTerm($termId);
			
			$allParams = array();
			$allParams['course'] = $this->_getParam('course');
			if ($this->getSelectedCatalogId())
				$allParams['catalog'] = self::getStringFromOsidId($this->getSelectedCatalogId());
			$this->view->offeringsForAllTermsUrl = $this->_helper->url('view', 'courses', null, $allParams);
		}
		
		// offerings
		$this->view->offeringsTitle = "Sections";
		$offeringLookupSession = self::getCourseManager()->getCourseOfferingLookupSession();
		$offeringLookupSession->useFederatedCourseCatalogView();
		if (isset($this->view->term)) {
			$this->view->offerings = $offeringLookupSession->getCourseOfferingsByTermForCourse(
				$this->view->term->getId(),
				$id
			);
		} else {
			$this->view->offerings = $offeringLookupSession->getCourseOfferingsForCourse($id);
		}
 		$this->render('offerings', null, true);
	}
	
	/**
	 * Search for courses
	 * 
	 * @return void
	 * @access public
	 * @since 6/15/09
	 */
	public function searchxmlAction () {
		$this->_helper->viewRenderer->setNoRender();
		
		if (!$this->_getParam('catalog')) {
			header('HTTP/1.1 400 Bad Request');
			print "A catalog must be specified.";
			exit;
		}
		try {
			$catalogId = self::getOsidIdFromString($this->_getParam('catalog'));
			$searchSession = self::getCourseManager()->getCourseOfferingSearchSessionForCatalog($catalogId);
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
		$searchUrl = $this->getAsAbsolute($this->_helper->url('search', 'offerings', null, array('catalog' => $this->_getParam('catalog'), 'keywords' => $keywords, 'submit' => 'Search')));
		
		header('Content-Type: text/xml');
		print '<?xml version="1.0" encoding="utf-8" ?>
<rss version="2.0">
	<channel>
		<title>Course Search: "'.$keywords.'"</title>
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
				$courseIdString = self::getStringFromOsidId($offering->getCourseId());
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
			print $course->getDisplayName().' - '.$course->getTitle();
			print "</title>";
			
			print "\n\t\t\t<link>";
			print $this->getAsAbsolute($this->_helper->url('view', 'courses', null, array('catalog' => $this->_getParam('catalog'), 'course' => $courseIdString)));
			print "</link>";
			
			print "\n\t\t\t<guid isPermaLink='true'>";
			print $this->getAsAbsolute($this->_helper->url('view', 'courses', null, array('catalog' => $this->_getParam('catalog'), 'course' => $courseIdString)));
			print "</guid>";
			
			print "\n\t\t\t<description><![CDATA[";
			print $course->getDescription();
			print "]]></description>";
			
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
		$this->_helper->viewRenderer->setNoRender();
		
		if (!$this->_getParam('catalog')) {
			header('HTTP/1.1 400 Bad Request');
			print "A catalog must be specified.";
			exit;
		}
		try {
			$catalogId = self::getOsidIdFromString($this->_getParam('catalog'));
			$searchSession = self::getCourseManager()->getCourseSearchSessionForCatalog($catalogId);
		} catch (osid_InvalidArgumentException $e) {
			header('HTTP/1.1 400 Bad Request');
			print "The catalog id specified was not of the correct format.";
			exit;
		} catch (osid_NotFoundException $e) {
			header('HTTP/1.1 404 Not Found');
			print "The catalog id specified was not found.";
			exit;
		}
		
		if (!$this->_getParam('topic') || !strlen($this->_getParam('topic'))) {
			header('HTTP/1.1 400 Bad Request');
			print "A topic must be specified.";
			exit;
		}
		
		$topicId = self::getOsidIdFromString($this->_getParam('topic'));
		
		$searchUrl = $this->getAsAbsolute($this->_helper->url('search', 'offerings', null, array('catalog' => $this->_getParam('catalog'), 'topic' => $this->_getParam('topic'), 'submit' => 'Search')));
		
		// Fetch courses
		$query = $searchSession->getCourseQuery();
		
		$topicRecord = $query->getCourseQueryRecord(new phpkit_type_URNInetType("urn:inet:middlebury.edu:record:topic"));
		$topicRecord->matchTopicId($topicId, true);
		
		$courses = $searchSession->getCoursesByQuery($query);
		
		$topicLookup = self::getCourseManager()->getTopicLookupSession();
		$topicLookup->useFederatedView();
		$topic = $topicLookup->getTopic($topicId);
		
		$this->outputCourseFeed($courses, htmlentities('Courses in  '.$topic->getDisplayName()), $searchUrl, array($this, 'getAllCourseTerms'));
		
	}
	
	/**
	 * Search for courses
	 * 
	 * @return void
	 * @access public
	 * @since 6/15/09
	 */
	public function instructorxmlAction () {
		$this->_helper->viewRenderer->setNoRender();
		
		if (!$this->_getParam('catalog')) {
			$searchSession = self::getCourseManager()->getCourseSearchSession();
			$searchSession->useFederatedView();
			$offeringSearchSession = self::getCourseManager()->getCourseOfferingSearchSession();
			$offeringSearchSession->useFederatedView();
		} else {
			try {
				$catalogId = self::getOsidIdFromString($this->_getParam('catalog'));
				$searchSession = self::getCourseManager()->getCourseSearchSessionForCatalog($catalogId);
				$offeringSearchSession = self::getCourseManager()->getCourseOfferingSearchSessionForCatalog($catalogId);
			} catch (osid_InvalidArgumentException $e) {
				header('HTTP/1.1 400 Bad Request');
				print "The catalog id specified was not of the correct format.";
				exit;
			} catch (osid_NotFoundException $e) {
				header('HTTP/1.1 404 Not Found');
				print "The catalog id specified was not found.";
				exit;
			}
		}
		
		$instructor = trim($this->_getParam('instructor'));
		
		if (!$instructor || !strlen($instructor)) {
			header('HTTP/1.1 400 Bad Request');
			print "An instructor must be specified.";
			exit;
		}
		
		$instructorId = self::getOsidIdFromString('resource/person/'.$instructor);
		$searchUrl = $this->getAsAbsolute($this->_helper->url('view', 'resources', null, array('catalog' => $this->_getParam('catalog'), 'resource' => 'resouce/person/'.$instructor)));
		
		// Fetch courses
		$query = $searchSession->getCourseQuery();
		
		$instrctorRecord = $query->getCourseQueryRecord(new phpkit_type_URNInetType("urn:inet:middlebury.edu:record:instructors"));
		$instrctorRecord->matchInstructorId($instructorId, true);
		
		$courses = $searchSession->getCoursesByQuery($query);
		
		$resourceLookup = self::getCourseManager()->getResourceManager()->getResourceLookupSession();
		$instructorResource = $resourceLookup->getResource($instructorId);
		
		$this->outputCourseFeed($courses, 'Courses taught by '.$instructorResource->getDisplayName(), $searchUrl, array($this, 'getInstructorCourseTerms'), array($offeringSearchSession, $instructorId));
		
	}
	
	/**
	 * Output an RSS feed of courses from results
	 * 
	 * @param osid_course_CourseSearchResults $courses
	 * @param string $title
	 * @param string $url
	 * @return void
	 * @access protected
	 * @since 10/19/09
	 */
	protected function outputCourseFeed (osid_course_CourseSearchResults $courses, $title, $url, $termsCallback, $additionalCallbackParams = array()) {
		ob_start();
		print '<?xml version="1.0" encoding="utf-8" ?>
<rss version="2.0" xmlns:catalog="http://www.middlebury.edu/course_catalog">
	<channel>
		<title>'.$title.'</title>
		<link>'.$url.'</link>
		<description></description>
		<lastBuildDate>'.date('r').'</lastBuildDate>
		<generator>Course Catalog</generator>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>
		
';
		
// 		print "<description><![CDATA[";
// 		print ($courses->debug());
// 		print "]]></description>";
		
		$catalogSession = self::getCourseManager()->getCourseCatalogSession();
		$termsType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:record:terms");
		
		while ($courses->hasNext() && count($courses) <= 20) {
			$course = $courses->getNextCourse();
			$courseIdString = self::getStringFromOsidId($course->getId());
			
			// Define a cutoff date after which courses will be included in the feed.
			// Currently set to 4 years. Would be good to have as a configurable time.
			$now = new DateTime;
			$cutOff = $this->DateTime_getTimestamp($now) - (60 * 60 * 24 * 365 * 4);
			
			$recentTerms = array();
			$params = array();
			$params[] = $course;
			$params = array_merge($params, $additionalCallbackParams);
			$allTerms = call_user_func_array($termsCallback, $params);
			foreach ($allTerms as $term) {
				if ($this->DateTime_getTimestamp($term->getEndTime()) > $cutOff) {
					$recentTerms[] = $term;
				}
			}
			
			if (count($recentTerms) || !$course->hasRecordType($termsType)) {
			
				print "\n\t\t<item>";
				
				print "\n\t\t\t<title>";
				print htmlspecialchars($course->getDisplayName().' - '.$course->getTitle());
				print "</title>";
				
				print "\n\t\t\t<link>";
				$catalog = $catalogSession->getCatalogIdsByCourse($course->getId());
				if ($catalog->hasNext())
					$catalogIdString = self::getStringFromOsidId($catalog->getNextId());
				else
					$catalogIdString = null;
				print $this->getAsAbsolute($this->_helper->url('view', 'courses', null, array('catalog' => $catalogIdString, 'course' => $courseIdString)));
				print "</link>";
				
				print "\n\t\t\t<guid isPermaLink='true'>";
				print $this->getAsAbsolute($this->_helper->url('view', 'courses', null, array('catalog' => $catalogIdString, 'course' => $courseIdString)));
				print "</guid>";
				
				print "\n\t\t\t<description><![CDATA[";
				print $course->getDescription();
				
// 				if (count($recentTerms)) {
// 					$termStrings = array();
// 					foreach ($recentTerms as $term) {
// 						$termStrings[] = $term->getDisplayName();
// 					}
// 					print "<p class='terms_taught'>".implode(', ', $termStrings)."</p>";
// 				}
				
				print "]]></description>";
				
				if (count($recentTerms)) {
					$termStrings = array();
					foreach ($recentTerms as $term) {
						print "\n\t\t\t<catalog:term id=\"".self::getStringFromOsidId($term->getId())."\" >".$term->getDisplayName()."</catalog:term>";
					}
				}
				
				print "\n\t\t</item>";
			}
		}
		
		print '
	</channel>
</rss>';
		
		header('Content-Type: text/xml');
		ob_end_flush();
		exit;
	}
	
	/**
	 * Answer all terms for a course
	 * 
	 * @param osid_course_Course $course
	 * @return array
	 * @access private
	 * @since 10/20/09
	 */
	private function getAllCourseTerms (osid_course_Course $course) {
		$termsType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:record:terms");
		$allTerms = array();
		if ($course->hasRecordType($termsType)) {
			$termsRecord = $course->getCourseRecord($termsType);
			try {
				$terms = $termsRecord->getTerms();
				while ($terms->hasNext()) {
					$allTerms[] = $terms->getNextTerm();
				}
			} catch (osid_OperationFailedException $e) {
			}
		}
		return $allTerms;
	}
	
	/**
	 * Answer the terms in which an instructor taught a course
	 * 
	 * @param osid_course_Course $course
	 * @return array
	 * @access private
	 * @since 10/20/09
	 */
	private function getInstructorCourseTerms (osid_course_Course $course, osid_course_CourseOfferingSearchSession $session, osid_id_Id $instructorId) {
		$instructorsType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:record:instructors");
		$allTerms = array();
		
		$query = $session->getCourseOfferingQuery();
		$query->matchCourseId($course->getId(), true);
		$instructorsRecord = $query->getCourseOfferingQueryRecord($instructorsType);
		$instructorsRecord->matchInstructorId($instructorId, true);
		
		$search = $session->getCourseOfferingSearch();
		$order = $session->getCourseOfferingSearchOrder();
		$order->orderByTerm();
		$order->ascend();
		$search->orderCourseOfferingResults($order);
		
		$offerings = $session->getCourseOfferingsBySearch($query, $search);
		
// 		print $offerings->debug();
		
		$seen = array();
		while ($offerings->hasNext()) {
			$term = $offerings->getNextCourseOffering()->getTerm();
			$termIdString = self::getStringFromOsidId($term->getId());
// 			print $termIdString."\n";
			if (!in_array($termIdString, $seen)) {
				$allTerms[] = $term;
				$seen[] = $termIdString;
			}
		}
		return $allTerms;
	}
	
	/**
	 * Answer an absolute URL from a relative string.
	 * 
	 * @param string $url
	 * @return string
	 * @access private
	 * @since 6/15/09
	 */
	private function getAsAbsolute ($url) {
		$parts = split('/', $_SERVER['SERVER_PROTOCOL']);
		return strtolower(trim(array_shift($parts)))
			. '://' . $_SERVER['HTTP_HOST'] . $url;
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
	
}

?>