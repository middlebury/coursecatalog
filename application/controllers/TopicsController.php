<?php
/**
 * @since 4/21/09
 * @package catalog.controllers
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * A controller for working with terms
 * 
 * @since 4/21/09
 * @package catalog.controllers
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class TopicsController
	extends AbstractCatalogController
{
		
	/**
	 * Print out a list of all topics
	 * 
	 * @return void
	 * @access public
	 * @since 4/21/09
	 */
	public function listAction () {
		if ($this->_getParam('catalog')) {
			$catalogId = self::getOsidIdFromString($this->_getParam('catalog'));
			$lookupSession = self::getCourseManager()->getTopicLookupSessionForCatalog($catalogId);
			$this->view->title = 'Topics in '.$lookupSession->getCourseCatalog()->getDisplayName();
		} else {
			$lookupSession = self::getCourseManager()->getTopicLookupSession();
			$this->view->title = 'Topics in All Catalogs';
		}
		$lookupSession->useFederatedCourseCatalogView();
		
		$this->loadTopics($lookupSession->getTopics());
		
		$this->setSelectedCatalogId($lookupSession->getCourseCatalogId());
		$this->view->headTitle($this->view->title);
	}
	
	/**
	 * View a catalog details
	 * 
	 * @return void
	 * @access public
	 * @since 4/21/09
	 */
	public function viewAction () {
		$id = self::getOsidIdFromString($this->_getParam('topic'));
		$lookupSession = self::getCourseManager()->getTopicLookupSession();
		$lookupSession->useFederatedCourseCatalogView();
		$this->view->topic = $lookupSession->getTopic($id);
		
		$lookupSession = self::getCourseManager()->getCourseOfferingLookupSession();
		$lookupSession->useFederatedCourseCatalogView();
		if ($this->_getParam('term')) {
			$termId = self::getOsidIdFromString($this->_getParam('term'));
			$this->view->offerings = $lookupSession->getCourseOfferingsByTermByTopic($termId, $id);
			
			$termLookupSession = self::getCourseManager()->getTermLookupSession();
			$termLookupSession->useFederatedCourseCatalogView();
			$this->view->term = $termLookupSession->getTerm($termId);
		} else {
			$this->view->offerings = $lookupSession->getCourseOfferingsByTopic($id);
		}
		
		// Don't do the work to display instructors if we have a very large number of
		// offerings.
		if ($this->view->offerings->available() > 200)
			$this->view->hideOfferingInstructors = true;
		
		// Set the selected Catalog Id.
		if ($this->_getParam('catalog')) {
			$this->setSelectedCatalogId(self::getOsidIdFromString($this->_getParam('catalog')));
		}
		
		// Set the title
		$this->view->title = $this->view->topic->getDisplayName();
		$this->view->headTitle($this->view->title);
		
		$this->view->offeringsTitle = "Sections";
		
		$allParams = array();
		$allParams['topic'] = $this->_getParam('topic');
		if ($this->getSelectedCatalogId())
			$allParams['catalog'] = self::getStringFromOsidId($this->getSelectedCatalogId());
		$this->view->offeringsForAllTermsUrl = $this->_helper->url('view', 'topics', null, $allParams);
		
 		$this->render('offerings', null, true);
	}
	
	/**
	 * List all department topics as a text file with each line being Id|DisplayName
	 * 
	 * @return void
	 * @access public
	 * @since 10/20/09
	 */
	public function listdepartmentstxtAction () {
		header('Content-Type: text/plain');
		
		if ($this->_getParam('catalog')) {
			$catalogId = self::getOsidIdFromString($this->_getParam('catalog'));
			$lookupSession = self::getCourseManager()->getTopicLookupSessionForCatalog($catalogId);
			$this->view->title = 'Topics in '.$lookupSession->getCourseCatalog()->getDisplayName();
		} else {
			$lookupSession = self::getCourseManager()->getTopicLookupSession();
			$this->view->title = 'Topics in All Catalogs';
		}
		$lookupSession->useFederatedCourseCatalogView();
		
		$topics = $lookupSession->getTopicsByGenusType(new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/department"));
		
		while ($topics->hasNext()) {
			$topic = $topics->getNextTopic();
			print self::getStringFromOsidId($topic->getId())."|".self::getStringFromOsidId($topic->getId())." - ".$topic->getDisplayName()."\n";
		}
		
		exit;
	}
	
}

?>