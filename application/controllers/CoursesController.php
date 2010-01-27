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
		} else {
			$this->view->linkTermId = self::getCurrentTermId($this->getSelectedCatalogId());
		}
		
		$this->render();
		
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
			print htmlspecialchars($course->getDisplayName().' - '.$course->getTitle());
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
			
			$this->termLookupSession = self::getCourseManager()->getTermLookupSessionForCatalog($catalogId);
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
				$topicIds[] = self::getOsidIdFromString($idString);
			}
		} else {
			$topicIds[] = self::getOsidIdFromString($this->_getParam('topic'));
		}
		
		$searchUrl = $this->getAsAbsolute($this->_helper->url('search', 'offerings', null, array()));
		
		// Fetch courses
		$query = $searchSession->getCourseQuery();
		
		$topicRecord = $query->getCourseQueryRecord(new phpkit_type_URNInetType("urn:inet:middlebury.edu:record:topic"));
		foreach ($topicIds as $topicId) {
			$topicRecord->matchTopicId($topicId, true);
		}
		
		$courses = $searchSession->getCoursesByQuery($query);
		
		$topicLookup = self::getCourseManager()->getTopicLookupSession();
		$topicLookup->useFederatedView();
		$topic = $topicLookup->getTopic($topicId);
		
		$recentCourses = new Helper_RecentCourses_All($courses);
		$this->outputCourseFeed($recentCourses, htmlentities('Courses in  '.$topic->getDisplayName()), $searchUrl);
		
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
			
			// Allow term current/past to be limited to a certain catalog while courses are fetched from many
			if ($this->_getParam('term_catalog')) {
				$catalogId = self::getOsidIdFromString($this->_getParam('term_catalog'));
				$this->termLookupSession = self::getCourseManager()->getTermLookupSessionForCatalog($catalogId);
			} 
			// fall back to terms from any catalog.
			else {
				$this->termLookupSession = self::getCourseManager()->getTermLookupSession();
				$this->termLookupSession->useFederatedView();
			}
		} else {
			try {
				$catalogId = self::getOsidIdFromString($this->_getParam('catalog'));
				$searchSession = self::getCourseManager()->getCourseSearchSessionForCatalog($catalogId);
				$offeringSearchSession = self::getCourseManager()->getCourseOfferingSearchSessionForCatalog($catalogId);
				
				$this->termLookupSession = self::getCourseManager()->getTermLookupSessionForCatalog($catalogId);
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
		
		$recentCourses = new Helper_RecentCourses_Instructor($courses, $offeringSearchSession, $instructorId);
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
	protected function outputCourseFeed (Helper_RecentCourses_Abstract $recentCourses, $title, $url) {		
		ob_start();
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

		// Set the next and previous terms
		$currentTermId = self::getCurrentTermId($this->termLookupSession->getCourseCatalogId());
		$currentTerm = $this->termLookupSession->getTerm($currentTermId);
		$currentEndTime = $this->DateTime_getTimestamp($currentTerm->getEndTime());
		
// 		print "<description><![CDATA[";
// 		print ($courses->debug());
// 		print "]]></description>";
		
		$catalogSession = self::getCourseManager()->getCourseCatalogSession();
		$termsType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:record:terms");
		
// 		foreach ($groups as $key => $group) {
// 			print "\n$key";
// 			foreach ($group as $course) {
// 				print "\n\t".self::getStringFromOsidId($course->getId());
// 			}
// 		}
		
		foreach ($recentCourses->getPrimaryCourses() as $course) {
			$courseIdString = self::getStringFromOsidId($course->getId());
						
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
			
			print "]]></description>";
			
			$recentTerms = $recentCourses->getTermsForCourse($course);
			if (count($recentTerms)) {
				$termStrings = array();
				foreach ($recentTerms as $term) {
					print "\n\t\t\t<catalog:term id=\"".self::getStringFromOsidId($term->getId())."\"";
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
			
			$allTopics = AbstractCatalogController::topicListAsArray($course->getTopics());
			$topicType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/department");
			$topicTypeString = AbstractCatalogController::getStringFromOsidType($topicType);
			$topics = AbstractCatalogController::filterTopicsByType($allTopics, $topicType);
			foreach ($topics as $topic) {
				$topicParams['topic'] = AbstractCatalogController::getStringFromOsidId($topic->getId());
				print "\n\t\t\t<catalog:topic type=\"".$topicTypeString."\" id=\"".AbstractCatalogController::getStringFromOsidId($topic->getId())."\" href=\"".$this->getAsAbsolute($this->view->url($topicParams))."\">";
				print $this->view->escape($topic->getDisplayName());
				print "</catalog:topic> ";
			}
			
			$topicType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/requirement");
			$topicTypeString = AbstractCatalogController::getStringFromOsidType($topicType);
			$topics = AbstractCatalogController::filterTopicsByType($allTopics, $topicType);
			foreach ($topics as $topic) {
				$topicParams['topic'] = AbstractCatalogController::getStringFromOsidId($topic->getId());
				print "\n\t\t\t<catalog:topic type=\"".$topicTypeString."\" id=\"".AbstractCatalogController::getStringFromOsidId($topic->getId())."\" href=\"".$this->getAsAbsolute($this->view->url($topicParams))."\">";
				print $this->view->escape($topic->getDisplayName());
				print "</catalog:topic> ";
			}
			
			print "\n\t\t</item>";
		}
		
		print '
	</channel>
</rss>';
		
		header('Content-Type: text/xml');
		ob_end_flush();
		exit;
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
}

?>