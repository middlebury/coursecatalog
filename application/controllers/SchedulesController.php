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
			
			// Add the info to the appropriate group.
			$groups[$linkIdString][] = $info;
		}
		
		$groups = array_values($groups);
    	
    	print json_encode($groups);
    }
}
