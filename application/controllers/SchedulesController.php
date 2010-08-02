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
     * Create a new schedule
     * 
     * @return void
     * @access public
     * @since 8/2/10
     */
    public function createAction () {
    	if (!$this->_request->isPost())
    		throw new PermissionDeniedException("Create Schedules must be submitted as a POST request.");
    	$this->_request->setParamSources(array('_POST'));
    	
    	// Verify our CSRF key
 		if (!$this->_getParam('csrf_key') == $this->_helper->csrfKey())
 			throw new PermissionDeniedException('Invalid CSRF Key. Please log in again.');
    	
    	$catalogIdString = $this->_getParam('catalog');
    	$termIdString = $this->_getParam('term');
    	
    	
    	$schedules = new Schedules(Zend_Registry::get('db'),  $this->_helper->auth->getHelper()->getUserId(), $this->_helper->osid->getCourseManager());
    	
    	$schedule = $schedules->createSchedule($this->_helper->osidId->fromString($termIdString));
    	
    	$url = $this->view->url(array('action' => 'index', 'controller' => 'schedules', 'catalog' => $catalogIdString, 'term' => $termIdString));
    	$this->_redirect($url, array('exit' => true, 'prependBase' => false));
    }
}
