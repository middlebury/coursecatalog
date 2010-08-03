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
		
		// Dummy data for testing.
    	$sectionSets = 
    		array(
    			array(
    				array(	'id' => 'section%2F201090%2F91440', 
    						'name' => 'AMST0104A-F10', 
    						'type' => 'Lecture', 
    						'instructor' => 'Mittell', 
    						'location' => 'Axinn Center 232(AXN 232)', 
    						'schedule' => '11:00am-12:15pm on Tuesday, Thursday',
    						'selected' => true,
    				),
    			),
    			array(
    				array(	'id' => 'section%2F201090%2F91441', 
    						'name' => 'AMST0104X-F10', 
    						'type' => 'Discussion', 
    						'instructor' => 'Mittell', 
    						'location' => 'Axinn Center 104(AXN 104)', 
    						'schedule' => '9:05am-9:55am on Friday',
    				),
    				array(	'id' => 'section%2F201090%2F91442', 
    						'name' => 'AMST0104Y-F10', 
    						'type' => 'Discussion', 
    						'instructor' => 'Mittell', 
    						'location' => 'Axinn Center 104(AXN 104)', 
    						'schedule' => '10:10am-11:00am on Friday',
    						'selected' => true,
    				),
    				array(	'id' => 'section%2F201090%2F91443', 
    						'name' => 'AMST0104Z-F10', 
    						'type' => 'Discussion', 
    						'instructor' => 'Mittell', 
    						'location' => 'Axinn Center 104(AXN 104)', 
    						'schedule' => '11:15am-12:05pm on Friday',
    				),
    			),
    		);
    	
    	print json_encode($sectionSets);
    }
}
