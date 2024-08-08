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
class OfferingsController
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
		$this->wildcardStringMatchType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:search:wildcard");
		$this->booleanStringMatchType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:search:boolean");
		$this->instructorType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors');
		$this->enrollmentType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:enrollment');
		$this->locationType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:location');
		$this->alternateType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:alternates');
		$this->weeklyScheduleType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:weekly_schedule');

		parent::init();

		$this->subjectType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/subject");
		$this->departmentType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/department");
		$this->divisionType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/division");
		$this->requirementType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/requirement");
		$this->levelType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/level");
		$this->blockType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/block");
		$this->instructionMethodType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/instruction_method");

		$this->termType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:terms');

		$this->campusType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:resource/place/campus");
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
			$lookupSession = $this->_helper->osid->getCourseManager()->getCourseOfferingLookupSessionForCatalog($catalogId);
			$this->view->title = $lookupSession->getCourseCatalog()->getDisplayName();
		} else {
			$lookupSession = $this->_helper->osid->getCourseManager()->getCourseOfferingLookupSession();
			$this->view->title = 'All Catalogs';
		}
		$lookupSession->useFederatedCourseCatalogView();

		// Add our parameters to the search query
		if ($this->_getParam('term')) {
			if ($this->_getParam('term') == 'CURRENT') {
				$termId = $this->_helper->osidTerms->getNextOrLatestTermId();
			} else {
				$termId = $this->_helper->osidId->fromString($this->_getParam('term'));
			}

			$termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSession();
			$termLookupSession->useFederatedCourseCatalogView();

			$this->view->term = $termLookupSession->getTerm($termId);

			$this->view->offerings = $lookupSession->getCourseOfferingsByTerm($this->view->term->getId());
		} else {
			$this->view->offerings = $lookupSession->getCourseOfferings();
		}

		$this->setSelectedCatalogId($lookupSession->getCourseCatalogId());
		$this->view->headTitle($this->view->title);

		$this->view->menuIsOfferings = true;

		$this->view->offeringsTitle = "Sections";

		// Don't do the work to display instructors if we have a very large number of
		// offerings.
		if ($this->view->offerings->available() > 200)
			$this->view->hideOfferingInstructors = true;

		$this->render('offerings', null, true);
	}

	/**
	 * Answer search results as an xml feed.
	 *
	 * @return void
	 * @access public
	 * @since 10/21/09
	 */
	public function searchxmlAction () {
		$this->_helper->layout->disableLayout();
		$this->getResponse()->setHeader('Content-Type', 'text/xml');

		$this->searchAction();
		$this->view->sections = $this->searchSession->getCourseOfferingsByQuery($this->query);

		// Set the next and previous terms
		if (isset($this->view->term)) {
			$terms = $this->termLookupSession->getTerms();
			while ($terms->hasNext()) {
				$term = $terms->getNextTerm();
				if ($term->getId()->isEqual($this->view->term->getId())) {
					if (isset($lastTerm))
						$this->view->nextTerm = $lastTerm;
					if ($terms->hasNext())
						$this->view->previousTerm = $terms->getNextTerm();
					break;
				}
				$lastTerm = $term;
			}
		}
		// Reset the terms list as due to caching, we will have just wiped out the statement above.
		//
		// It would be better to fix this in the banner_course_CachingPdoQueryList, but
		// I haven't yet figured out how to determine if a result cursor
		// has been closed or not. See:
		//
		// http://stackoverflow.com/questions/1608427/how-can-i-determine-if-a-pdo-statement-cursor-is-closed
		$this->view->terms = $this->termLookupSession->getTerms();

		$this->view->feedTitle = 'Course Offering Results';
		$this->view->feedLink = $this->_helper->pathAsAbsoluteUrl($this->view->url($this->view->searchParams));
		$this->postDispatch();
	}

	/**
	 * Display a search form and search results
	 *
	 * @return void
	 * @access public
	 * @since 6/1/09
	 */
	public function searchAction () {
		if ($this->_getParam('catalog')) {
			$catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));
			$offeringSearchSession = $this->_helper->osid->getCourseManager()->getCourseOfferingSearchSessionForCatalog($catalogId);
			$offeringLookupSession = $this->_helper->osid->getCourseManager()->getCourseOfferingLookupSessionForCatalog($catalogId);
			$topicSearchSession = $this->_helper->osid->getCourseManager()->getTopicSearchSessionForCatalog($catalogId);
			$termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSessionForCatalog($catalogId);
			$resourceLookupSession = $this->_helper->osid->getCourseManager()->getResourceManager()->getResourceLookupSessionForBin($catalogId);
			$this->view->title = 'Search in '.$offeringSearchSession->getCourseCatalog()->getDisplayName();
		} else {
			$offeringSearchSession = $this->_helper->osid->getCourseManager()->getCourseOfferingSearchSession();
			$offeringLookupSession = $this->_helper->osid->getCourseManager()->getCourseOfferingLookupSession();
			$topicSearchSession = $this->_helper->osid->getCourseManager()->getTopicSearchSession();
			$termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSession();
			$resourceLookupSession = $this->_helper->osid->getCourseManager()->getResourceManager()->getResourceLookupSession();
			$this->view->title = 'Search in All Catalogs';
		}
		$termLookupSession->useFederatedCourseCatalogView();
		$offeringSearchSession->useFederatedCourseCatalogView();


	/*********************************************************
	 * Build option lists for the search form
	 *********************************************************/

		// Term
		if ($this->_getParam('term') == 'ANY') {
			// Don't set a term
		} else if (!$this->_getParam('term') || $this->_getParam('term') == 'CURRENT') {
			// When accessing the "current" term via xml, use the term we are in.
			// When displaying the search interface, use the next upcoming term.
			if ($this->_getParam('action') == 'searchxml') {
				$termId = $this->_helper->osidTerms->getCurrentTermId($offeringSearchSession->getCourseCatalogId());
			} else {
				$termId = $this->_helper->osidTerms->getNextOrLatestTermId($offeringSearchSession->getCourseCatalogId());
			}
		} else {
			$termId = $this->_helper->osidId->fromString($this->_getParam('term'));
		}


		// Topics
		$topicQuery = $topicSearchSession->getTopicQuery();
		$topicQuery->matchGenusType($this->departmentType, true);
		// if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
		// 	$record = $topicQuery->getTopicQueryRecord($this->termType);
		// 	$record->matchTermId($termId, true);
		// }
		$search = $topicSearchSession->getTopicSearch();
		$order = $topicSearchSession->getTopicSearchOrder();
		$order->orderByDisplayName();
		$search->orderTopicResults($order);
		$searchResults = $topicSearchSession->getTopicsBySearch($topicQuery, $search);
		$this->view->departments = $searchResults->getTopics();

		$topicQuery = $topicSearchSession->getTopicQuery();
		$topicQuery->matchGenusType($this->subjectType, true);
		// if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
		// 	$record = $topicQuery->getTopicQueryRecord($this->termType);
		// 	$record->matchTermId($termId, true);
		// }
		$search = $topicSearchSession->getTopicSearch();
		$order = $topicSearchSession->getTopicSearchOrder();
		$order->orderByDisplayName();
		$search->orderTopicResults($order);
		$searchResults = $topicSearchSession->getTopicsBySearch($topicQuery, $search);
		$this->view->subjects = $searchResults->getTopics();

		$topicQuery = $topicSearchSession->getTopicQuery();
		$topicQuery->matchGenusType($this->divisionType, true);
		if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
			$record = $topicQuery->getTopicQueryRecord($this->termType);
			$record->matchTermId($termId, true);
		}
		$this->view->divisions = $topicSearchSession->getTopicsByQuery($topicQuery);

		$topicQuery = $topicSearchSession->getTopicQuery();
		$topicQuery->matchGenusType($this->requirementType, true);
		if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
			$record = $topicQuery->getTopicQueryRecord($this->termType);
			$record->matchTermId($termId, true);
		}
		$this->view->requirements = $topicSearchSession->getTopicsByQuery($topicQuery);

		$topicQuery = $topicSearchSession->getTopicQuery();
		$topicQuery->matchGenusType($this->levelType, true);
		if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
			$record = $topicQuery->getTopicQueryRecord($this->termType);
			$record->matchTermId($termId, true);
		}
		$this->view->levels = $topicSearchSession->getTopicsByQuery($topicQuery);

		$topicQuery = $topicSearchSession->getTopicQuery();
		$topicQuery->matchGenusType($this->blockType, true);
		if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
			$record = $topicQuery->getTopicQueryRecord($this->termType);
			$record->matchTermId($termId, true);
		}
		$this->view->blocks = $topicSearchSession->getTopicsByQuery($topicQuery);

		$topicQuery = $topicSearchSession->getTopicQuery();
		$topicQuery->matchGenusType($this->instructionMethodType, true);
		if (isset($termId) && $topicQuery->hasRecordType($this->termType)) {
			$record = $topicQuery->getTopicQueryRecord($this->termType);
			$record->matchTermId($termId, true);
		}
		$this->view->instructionMethods = $topicSearchSession->getTopicsByQuery($topicQuery);

		$this->view->genusTypes = $offeringLookupSession->getCourseOfferingGenusTypes();

		// Campuses -- only include if we have more than one.
		$campuses = $resourceLookupSession->getResourcesByGenusType($this->campusType);
		if ($campuses->hasNext() && $campuses->getNextResource() && $campuses->hasNext()) {
			$this->view->campuses = $resourceLookupSession->getResourcesByGenusType($this->campusType);
		}

	/*********************************************************
	 * Set up and run our search query.
	 *********************************************************/

		$query = $offeringSearchSession->getCourseOfferingQuery();
		$search = $offeringSearchSession->getCourseOfferingSearch();
		$this->view->searchParams = array();

		// Make our session and query available to the XML version of this action.
		$this->termLookupSession = $termLookupSession;
		$this->view->terms = $termLookupSession->getTerms();

		// Add our parameters to the search query
		if ($this->_getParam('term')) {
			$this->view->searchParams['term'] = $this->_getParam('term');

			if (isset($termId)) {
				$query->matchTermId($termId, true);

				$termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSession();
				$termLookupSession->useFederatedCourseCatalogView();
				$this->view->term = $termLookupSession->getTerm($termId);
				$this->view->selectedTermId = $termId;

				$this->view->title .= " ".$this->view->term->getDisplayName();
			}
		}

		if ($this->_getParam('department')) {
			if (is_array($this->_getParam('department')))
				$departments = $this->_getParam('department');
			else
				$departments = array($this->_getParam('department'));

			if (count($departments)) {
				foreach ($departments as $idString) {
					$id = $this->_helper->osidId->fromString($idString);
					$query->matchTopicId($id, true);
					// set the first as the selected one if multiple.
					if (!isset($this->view->selectedDepartmentId))
						$this->view->selectedDepartmentId = $id;
				}
				$this->view->searchParams['department'] = $departments;
			}
		}

		if ($this->_getParam('subject')) {
			if (is_array($this->_getParam('subject')))
				$subjects = $this->_getParam('subject');
			else
				$subjects = array($this->_getParam('subject'));

			if (count($subjects)) {
				foreach ($subjects as $idString) {
					$id = $this->_helper->osidId->fromString($idString);
					$query->matchTopicId($id, true);
					// set the first as the selected one if multiple.
					if (!isset($this->view->selectedSubjectId))
						$this->view->selectedSubjectId = $id;
				}
				$this->view->searchParams['subject'] = $subjects;
			}
		}

		if ($this->_getParam('division')) {
			if (is_array($this->_getParam('division')))
				$divisions = $this->_getParam('division');
			else
				$divisions = array($this->_getParam('division'));

			if (count($divisions)) {
				foreach ($divisions as $idString) {
					$id = $this->_helper->osidId->fromString($idString);
					$query->matchTopicId($id, true);
					// set the first as the selected one if multiple.
					if (!isset($this->view->selectedDivisionId))
						$this->view->selectedDivisionId = $id;
				}
				$this->view->searchParams['division'] = $divisions;
			}
		}

		$this->view->selectedRequirementIds = array();
		if ($this->_getParam('requirement')) {
			if (is_array($this->_getParam('requirement')))
				$requirements = $this->_getParam('requirement');
			else
				$requirements = array($this->_getParam('requirement'));

			if (count($requirements)) {
				foreach ($requirements as $idString) {
					$id = $this->_helper->osidId->fromString($idString);
					$query->matchTopicId($id, true);
					$this->view->selectedRequirementIds[] = $id;
				}
				$this->view->searchParams['requirement'] = $requirements;
			}
		}

		$this->view->selectedLevelIds = array();
		if ($this->_getParam('level')) {
			if (is_array($this->_getParam('level')))
				$levels = $this->_getParam('level');
			else
				$levels = array($this->_getParam('level'));

			if (count($levels)) {
				foreach ($levels as $idString) {
					$id = $this->_helper->osidId->fromString($idString);
					$query->matchTopicId($id, true);
					$this->view->selectedLevelIds[] = $id;
				}
				$this->view->searchParams['level'] = $levels;
			}
		}

		$this->view->selectedBlockIds = array();
		if ($this->_getParam('block')) {
			if (is_array($this->_getParam('block')))
				$blocks = $this->_getParam('block');
			else
				$blocks = array($this->_getParam('block'));

			if (count($blocks)) {
				foreach ($blocks as $idString) {
					$id = $this->_helper->osidId->fromString($idString);
					$query->matchTopicId($id, true);
					$this->view->selectedBlockIds[] = $id;
				}
				$this->view->searchParams['block'] = $blocks;
			}
		}

		$this->view->selectedInstructionMethodIds = array();
		if ($this->_getParam('instruction_method')) {
			if (is_array($this->_getParam('instruction_method')))
				$instructionMethods = $this->_getParam('instruction_method');
			else
				$instructionMethods = array($this->_getParam('instruction_method'));

			if (count($instructionMethods)) {
				foreach ($instructionMethods as $idString) {
					$id = $this->_helper->osidId->fromString($idString);
					$query->matchTopicId($id, true);
					$this->view->selectedInstructionMethodIds[] = $id;
				}
				$this->view->searchParams['instruction_method'] = $instructionMethods;
			}
		}

		$this->view->selectedGenusTypes = array();
		if ($this->_getParam('type')) {
			if (is_array($this->_getParam('type')))
				$genusTypes = $this->_getParam('type');
			else
				$genusTypes = array($this->_getParam('type'));

			if (count($genusTypes)) {
				foreach ($genusTypes as $typeString) {
					$genusType = $this->_helper->osidType->fromString($typeString);
					$query->matchGenusType($genusType, true);
					$this->view->selectedGenusTypes[] = $genusType;
				}
				$this->view->searchParams['type'] = $genusTypes;
			}
		}

		// Campuses
		$this->view->selectedCampusIds = array();
		if ($this->_getParam('location')) {
			if (is_array($this->_getParam('location')))
				$campuses = $this->_getParam('location');
			else
				$campuses = array($this->_getParam('location'));

			if (count($campuses)) {
				foreach ($campuses as $idString) {
					$id = $this->_helper->osidId->fromString($idString);
					$query->matchLocationId($id, true);
					$this->view->selectedCampusIds[] = $id;
				}
				$this->view->searchParams['location'] = $campuses;
			}
		}


		// Set the default selection to lecture/seminar if the is a new search
		if (!$this->_getParam('search') && !count($this->view->selectedGenusTypes)) {
			$this->view->selectedGenusTypes = $this->_helper->osidTypes->getDefaultGenusTypes();
		}

		if ($query->hasRecordType($this->weeklyScheduleType)) {
			$queryRecord = $query->getCourseOfferingQueryRecord($this->weeklyScheduleType);

			if ($this->_getParam('days')) {
				if (is_array($this->_getParam('days')))
					$days = $this->_getParam('days');
				else
					$days = array($this->_getParam('days'));

				if (count($days)) {
					if ($this->_getParam('days_mode') == 'exclusive') {
						$this->view->searchParams['days_mode'] = 'exclusive';

						if (!in_array('sunday', $days))
							$queryRecord->matchMeetsSunday(false);

						if (!in_array('monday', $days))
							$queryRecord->matchMeetsMonday(false);

						if (!in_array('tuesday', $days))
							$queryRecord->matchMeetsTuesday(false);

						if (!in_array('wednesday', $days))
							$queryRecord->matchMeetsWednesday(false);

						if (!in_array('thursday', $days))
							$queryRecord->matchMeetsThursday(false);

						if (!in_array('friday', $days))
							$queryRecord->matchMeetsFriday(false);

						if (!in_array('saturday', $days))
							$queryRecord->matchMeetsSaturday(false);
					}
					// Inclusive search.
					else {
						$this->view->searchParams['days_mode'] = 'inclusive';

						if (in_array('sunday', $days))
							$queryRecord->matchMeetsSunday(true);

						if (in_array('monday', $days))
							$queryRecord->matchMeetsMonday(true);

						if (in_array('tuesday', $days))
							$queryRecord->matchMeetsTuesday(true);

						if (in_array('wednesday', $days))
							$queryRecord->matchMeetsWednesday(true);

						if (in_array('thursday', $days))
							$queryRecord->matchMeetsThursday(true);

						if (in_array('friday', $days))
							$queryRecord->matchMeetsFriday(true);

						if (in_array('saturday', $days))
							$queryRecord->matchMeetsSaturday(true);
					}

					$this->view->searchParams['days'] = $days;
				}
			} else {
				$this->view->searchParams['days'] = array();
			}

			if ($this->_getParam('time_start') || $this->_getParam('time_end')) {
				$start = intval($this->_getParam('time_start'));
				$end = intval($this->_getParam('time_end'));
				if (!$end) {
					$end = 86400;
				}
				if ($start > 0 || $end < 86400)
					$queryRecord->matchMeetingTime($start, $end, true);

				$this->view->timeStart = $start;
				$this->view->timeEnd = $end;
				$this->view->searchParams['time_start'] = $start;
				$this->view->searchParams['time_end'] = $end;
			} else {
				$this->view->timeStart = 0;
				$this->view->timeEnd = 86400;
			}
		}

		if ($this->_getParam('keywords')) {
			$query->matchKeyword($this->_getParam('keywords'), $this->booleanStringMatchType, true);
			$this->view->keywords = $this->_getParam('keywords');
			$this->view->searchParams['keywords'] = $this->_getParam('keywords');
		} else {
			$this->view->keywords = '';
		}

		if ($this->_getParam('instructor')) {
			if ($query->hasRecordType($this->instructorType)) {
				$queryRecord = $query->getCourseOfferingQueryRecord($this->instructorType);
				$queryRecord->matchInstructorId($this->_helper->osidId->fromString($this->_getParam('instructor')), true);
			}
			$this->view->searchParams['instructor'] = $this->_getParam('instructor');
		}

		if ($this->_getParam('enrollable')) {
			if ($query->hasRecordType($this->enrollmentType)) {
				$queryRecord = $query->getCourseOfferingQueryRecord($this->enrollmentType);
				$queryRecord->matchEnrollable(true);
			}
			$this->view->searchParams['enrollable'] = $this->_getParam('enrollable');
		}

		// Make our session and query available to the XML version of this action.
		$this->searchSession = $offeringSearchSession;
		$this->query = $query;

		// Run the query if submitted.
		if ($this->_getParam('search')) {
			$this->view->searchParams['search'] = $this->_getParam('search');
			$this->view->paginator = new Zend_Paginator(new Paginator_Adaptor_CourseOfferingSearch($offeringSearchSession, $query));
			$this->view->paginator->setCurrentPageNumber($this->_getParam('page'));
		}

	/*********************************************************
	 * Options for output
	 *********************************************************/


		$this->setSelectedCatalogId($offeringSearchSession->getCourseCatalogId());
		$this->view->headTitle($this->view->title);

		$this->view->menuIsSearch = true;

	}

	/**
	 * View a catalog details
	 *
	 * @return void
	 * @access public
	 * @since 4/21/09
	 */
	public function viewAction () {
		$this->viewBase();

		// Bookmarked Courses and Schedules
		$this->view->bookmarks_CourseId = $this->view->offering->getCourseId();

		$this->view->menuIsOfferings = true;

		$this->render();
		$this->render('offerings', null, true);
	}


	protected function viewBase() {
		$id = $this->_helper->osidId->fromString($this->_getParam('offering'));
		$lookupSession = $this->_helper->osid->getCourseManager()->getCourseOfferingLookupSession();
		$lookupSession->useFederatedCourseCatalogView();
		$this->view->offering = $lookupSession->getCourseOffering($id);

		// Load the topics into our view
		$this->loadTopics($this->view->offering->getTopics());

		// Set the selected Catalog Id.
		$catalogSession = $this->_helper->osid->getCourseManager()->getCourseOfferingCatalogSession();
		$catalogIds = $catalogSession->getCatalogIdsByCourseOffering($id);
		if ($catalogIds->hasNext()) {
			$this->setSelectedCatalogId($catalogIds->getNextId());
		}

		// Set the title
		$this->view->title = $this->view->offering->getDisplayName();
		$this->view->headTitle($this->view->title);

		// Term
		$this->view->term = $this->view->offering->getTerm();

		// Other offerings
		$this->view->offeringsTitle = "All Sections";
		$this->view->offerings = $lookupSession->getCourseOfferingsByTermForCourse(
			$this->view->offering->getTermId(),
			$this->view->offering->getCourseId()
		);

		// Alternates
		if ($this->view->offering->hasRecordType($this->alternateType)) {

			$record = $this->view->offering->getCourseOfferingRecord($this->alternateType);
			if ($record->hasAlternates()) {
				$this->view->alternates = $record->getAlternates();
			}
		}
	}

	/**
	 * Answer search results as an xml feed.
	 *
	 * @return void
	 * @access public
	 * @since 10/21/09
	 */
	public function viewxmlAction () {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setRender('searchxml');
		$this->getResponse()->setHeader('Content-Type', 'text/xml');

		$this->viewBase();

		$this->view->feedTitle = $this->view->title;
		$this->view->feedLink = $this->_helper->pathAsAbsoluteUrl('/offerings/view/'.$this->_getParam('catalog').'/offering/'.$this->_getParam('offering'));
		$this->view->sections = new phpkit_course_ArrayCourseOfferingList([$this->view->offering]);
		$this->postDispatch();
	}

}
