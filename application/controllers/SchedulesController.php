<?php

/** Zend_Controller_Action */
class SchedulesController 
	extends AbstractCatalogController
{
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
		
		// Term
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
		if ($this->_getParam('term') == 'ANY') {
			// Don't set a term
		} else if (!$this->_getParam('term') || $this->_getParam('term') == 'CURRENT') {
			$this->view->selectedTermId = $this->_helper->osidTerms->getCurrentTermId($catalogId);
		} else {
			$this->view->selectedTermId = $this->_helper->osidId->fromString($this->_getParam('term'));
		}		
		
		
		
    }
}
