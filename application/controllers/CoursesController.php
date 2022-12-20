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

		$courses = $searchSession->getCoursesByQuery($query)->getCourses();

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
	 * Search for courses
	 *
	 * @return void
	 * @access public
	 * @since 6/15/09
	 */
	public function byidxmlAction () {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		if (!$this->_getParam('catalog')) {
			header('HTTP/1.1 400 Bad Request');
			print "A catalog must be specified.";
			exit;
		}
		try {
			$catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));
			$lookupSession = $this->_helper->osid->getCourseManager()->getCourseLookupSessionForCatalog($catalogId);
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

		if (!$this->_getParam('id')) {
			header('HTTP/1.1 400 Bad Request');
			print "'id[]' must be specified.";
			exit;
		}

		$courseIds = array();
		if (is_array($this->_getParam('id'))) {
			foreach ($this->_getParam('id') as $idString) {
				$courseIds[] = $this->_helper->osidId->fromString($idString);
			}
		} else {
			$courseIds[] = $this->_helper->osidId->fromString($this->_getParam('id'));
		}

		// Use Comparative view to include any found courses, ignoring missing ids.
		$lookupSession->useComparativeCourseView();

		$courses = $lookupSession->getCoursesByIds(new phpkit_id_ArrayIdList($courseIds));

		$recentCourses = new Helper_RecentCourses_Department($courses);
		if ($this->_getParam('cutoff')) {
			$recentCourses->setRecentInterval(new DateInterval($this->_getParam('cutoff')));
		}

		$searchUrl = $this->_helper->pathAsAbsoluteUrl($this->_helper->url('byidxml', 'courses', null, [
			'catalog' => $this->_getParam('catalog'),
			'id' => $this->_getParam('id'),
			'cuttoff' => $this->_getParam('cutoff'),
		]));
		$this->outputCourseFeed($recentCourses, 'Courses by Id', $searchUrl);
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

		$order = $offeringSearchSession->getCourseOfferingSearchOrder();
		$order->orderByDisplayName();
		$search = $offeringSearchSession->getCourseOfferingSearch();
		$search->orderCourseOfferingResults($order);

		$courseOfferings = $offeringSearchSession->getCourseOfferingsBySearch($query, $search);

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

		$now = $this->DateTime_getTimestamp(new DateTime);

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
			print "\n\t\t\t<catalog:display_name>".htmlspecialchars($course->getDisplayName())."</catalog:display_name>";
			print "\n\t\t\t<catalog:title>".htmlspecialchars($course->getTitle())."</catalog:title>";

			foreach ($alternates as $alt) {
				print "\n\t\t\t<catalog:alternate>";
				print "\n\t\t\t\t<catalog:id>".$this->_helper->osidId->toString($alt->getId())."</catalog:id>";
				print "\n\t\t\t\t<catalog:display_name>".htmlspecialchars($alt->getDisplayName())."</catalog:display_name>";
				print "\n\t\t\t\t<catalog:title>".htmlspecialchars($alt->getTitle())."</catalog:title>";
				print "\n\t\t\t</catalog:alternate>";
			}

			$recentTerms = $recentCourses->getTermsForCourse($course);
			if (count($recentTerms)) {
				$termStrings = array();
				foreach ($recentTerms as $term) {
					print "\n\t\t\t<catalog:term id=\"".$this->_helper->osidId->toString($term->getId())."\"";
					if ($term->getId()->isEqual($currentTermId)) {
						print ' type="current"';
					} else if ($currentEndTime < $this->DateTime_getTimestamp($term->getEndTime())) {
						print ' type="future"';
					} else if ($now > $this->DateTime_getTimestamp($term->getStartTime()) && $now < $this->DateTime_getTimestamp($term->getEndTime())) {
						print ' type="current"';
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
