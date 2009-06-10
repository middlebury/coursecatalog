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
		$this->weeklyScheduleType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:weekly_schedule');

		parent::init();
		
		$this->subjectType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/subject");
        $this->departmentType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/department");
        $this->divisionType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/division");
        $this->requirementType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/requirement");
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
			$lookupSession = self::getCourseManager()->getCourseOfferingLookupSessionForCatalog($catalogId);
			$this->view->title = 'Courses in '.$lookupSession->getCourseCatalog()->getDisplayName();
		} else {
			$lookupSession = self::getCourseManager()->getCourseOfferingLookupSession();
			$this->view->title = 'Courses in All Catalogs';
		}
		$lookupSession->useFederatedCourseCatalogView();
		
		$this->view->offerings = $lookupSession->getCourseOfferings();
		
		$this->setSelectedCatalogId($lookupSession->getCourseCatalogId());
		$this->view->headTitle($this->view->title);
		
		$this->view->menuIsOfferings = true;
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
			$catalogId = self::getOsidIdFromString($this->_getParam('catalog'));
			$offeringSearchSession = self::getCourseManager()->getCourseOfferingSearchSessionForCatalog($catalogId);
			$topicLookupSession = self::getCourseManager()->getTopicLookupSessionForCatalog($catalogId);
			$termLookupSession = self::getCourseManager()->getTermLookupSessionForCatalog($catalogId);
			$this->view->title = 'Search in '.$offeringSearchSession->getCourseCatalog()->getDisplayName();
		} else {
			$offeringSearchSession = self::getCourseManager()->getCourseOfferingSearchSession();
			$topicLookupSession = self::getCourseManager()->getTopicLookupSession();
			$termLookupSession = self::getCourseManager()->getTermLookupSession();
			$this->view->title = 'Search in All Catalogs';
		}
		$topicLookupSession->useFederatedCourseCatalogView();
		$termLookupSession->useFederatedCourseCatalogView();
		$offeringSearchSession->useFederatedCourseCatalogView();
		
		
	/*********************************************************
	 * Build option lists for the search form
	 *********************************************************/
	 	$this->view->terms = $termLookupSession->getTerms();
		$this->view->departments = $topicLookupSession->getTopicsByGenusType($this->departmentType);
		$this->view->subjects = $topicLookupSession->getTopicsByGenusType($this->subjectType);
		$this->view->divisions = $topicLookupSession->getTopicsByGenusType($this->divisionType);
		$this->view->requirements = $topicLookupSession->getTopicsByGenusType($this->requirementType);
		
		
	/*********************************************************
	 * Set up and run our search query.
	 *********************************************************/
	
		$query = $offeringSearchSession->getCourseOfferingQuery();
		$search = $offeringSearchSession->getCourseOfferingSearch();
		$this->view->searchParams = array();
		
		// Add our parameters to the search query
		if ($this->_getParam('term')) {
			$termId = self::getOsidIdFromString($this->_getParam('term'));
			$this->view->searchParams['term'] = $this->_getParam('term');
			
			$query->matchTermId($termId, true);
			
			$termLookupSession = self::getCourseManager()->getTermLookupSession();
			$termLookupSession->useFederatedCourseCatalogView();
			$this->view->term = $termLookupSession->getTerm($termId);
			$this->view->selectedTermId = $termId;

			$this->view->title .= " ".$this->view->term->getDisplayName();
		}
		
		if ($this->_getParam('department')) {
			$query->matchTopicId(self::getOsidIdFromString($this->_getParam('department')), true);
			$this->view->selectedDepartmentId = self::getOsidIdFromString($this->_getParam('department'));
			$this->view->searchParams['department'] = $this->_getParam('department');
		}
		
		if ($this->_getParam('subject')) {
			$query->matchTopicId(self::getOsidIdFromString($this->_getParam('subject')), true);
			$this->view->selectedSubjectId = self::getOsidIdFromString($this->_getParam('subject'));
			$this->view->searchParams['subject'] = $this->_getParam('subject');
		}
		
		if ($this->_getParam('division')) {
			$query->matchTopicId(self::getOsidIdFromString($this->_getParam('division')), true);
			$this->view->selectedDivisionId = self::getOsidIdFromString($this->_getParam('division'));
			$this->view->searchParams['division'] = $this->_getParam('division');
		}
			
		$this->view->selectedRequirementIds = array();
		if ($this->_getParam('requirement') && count($this->_getParam('requirement'))) {
			if (is_array($this->_getParam('requirement')))
				$requirements = $this->_getParam('requirement');
			else
				$requirements = array($this->_getParam('requirement'));
			
			foreach ($requirements as $reqIdString) {
				$reqId = self::getOsidIdFromString($reqIdString);
				$query->matchTopicId($reqId, true);
				$this->view->selectedRequirementIds[] = $reqId;
			}
			
			$this->view->searchParams['requirement'] = $requirements;
		}
		
		if ($query->hasRecordType($this->weeklyScheduleType)) {
			$queryRecord = $query->getCourseOfferingQueryRecord($this->weeklyScheduleType);
			
			if ($this->_getParam('days') && count($this->_getParam('days'))) {
				if (is_array($this->_getParam('days')))
					$days = $this->_getParam('days');
				else
					$days = array($this->_getParam('days'));
					
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
				
				$this->view->searchParams['days'] = $days;
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
				$queryRecord->matchInstructorId(self::getOsidIdFromString($this->_getParam('instructor')), true);
			}
			$this->view->searchParams['instructor'] = $this->_getParam('instructor');
		}
		
		// Run the query if submitted.
		if ($this->_getParam('submit')) {
			$this->view->searchParams['submit'] = $this->_getParam('submit');
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
		$id = self::getOsidIdFromString($this->_getParam('offering'));
		$lookupSession = self::getCourseManager()->getCourseOfferingLookupSession();
		$lookupSession->useFederatedCourseCatalogView();
		$this->view->offering = $lookupSession->getCourseOffering($id);
 		
 		// Load the topics into our view
 		$this->loadTopics($this->view->offering->getTopics());
		
		// Set the selected Catalog Id.
		$catalogSession = self::getCourseManager()->getCourseOfferingCatalogSession();
		$catalogIds = $catalogSession->getCatalogIdsByCourseOffering($id);
		if ($catalogIds->hasNext()) {
			$this->setSelectedCatalogId($catalogIds->getNextId());
		}
		
		// Set the title
		$this->view->title = $this->view->offering->getDisplayName();
		$this->view->headTitle($this->view->title);
		
		$this->render();
		
		// Term
		$this->view->term = $this->view->offering->getTerm();
		
		// Other offerings
		$this->view->offeringsTitle = "All Sections";
 		$this->view->offerings = $lookupSession->getCourseOfferingsByTermForCourse(
 			$this->view->offering->getTermId(),
 			$this->view->offering->getCourseId()
 		);
 		$this->render('offerings', null, true);
 		
 		$this->view->menuIsOfferings = true;
	}
	
}

?>