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
			$catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));
			$lookupSession = $this->_helper->osid->getCourseManager()->getTopicLookupSessionForCatalog($catalogId);
			$this->view->title = 'Topics in '.$lookupSession->getCourseCatalog()->getDisplayName();
		} else {
			$lookupSession = $this->_helper->osid->getCourseManager()->getTopicLookupSession();
			$this->view->title = 'Topics in All Catalogs';
		}
		$lookupSession->useFederatedCourseCatalogView();
		
		if ($this->_getParam('type')) {
			$genusType = $this->_helper->osidType->fromString($this->_getParam('type'));
			$topics = $lookupSession->getTopicsByGenusType($genusType);
			$this->view->title .= ' of type '.$this->_getParam('type');
		} else {
			$topics = $lookupSession->getTopics();
		}
		
		$this->loadTopics($topics);
		
		$this->setSelectedCatalogId($lookupSession->getCourseCatalogId());
		$this->view->headTitle($this->view->title);
	}
	
	/**
     * Print out an XML list of all catalogs
     * 
     * @return void
     */
    public function listxmlAction () {
    	$this->_helper->layout->disableLayout();
		$this->getResponse()->setHeader('Content-Type', 'text/xml');
		
    	$this->listAction();
    }
	
	/**
	 * View a catalog details
	 * 
	 * @return void
	 * @access public
	 * @since 4/21/09
	 */
	public function viewAction () {
		$id = $this->_helper->osidId->fromString($this->_getParam('topic'));
		$lookupSession = $this->_helper->osid->getCourseManager()->getTopicLookupSession();
		$lookupSession->useFederatedCourseCatalogView();
		$this->view->topic = $lookupSession->getTopic($id);
		
		$lookupSession = $this->_helper->osid->getCourseManager()->getCourseOfferingLookupSession();
		$lookupSession->useFederatedCourseCatalogView();
		if ($this->_getParam('term')) {
			$termId = $this->_helper->osidId->fromString($this->_getParam('term'));
			$this->view->offerings = $lookupSession->getCourseOfferingsByTermByTopic($termId, $id);
			
			$termLookupSession = $this->_helper->osid->getCourseManager()->getTermLookupSession();
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
			$this->setSelectedCatalogId($this->_helper->osidId->fromString($this->_getParam('catalog')));
		}
		
		// Set the title
		$this->view->title = $this->view->topic->getDisplayName();
		$this->view->headTitle($this->view->title);
		
		$this->view->offeringsTitle = "Sections";
		
		$allParams = array();
		$allParams['topic'] = $this->_getParam('topic');
		if ($this->getSelectedCatalogId())
			$allParams['catalog'] = $this->_helper->osidId->toString($this->getSelectedCatalogId());
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
	public function listsubjectstxtAction () {
		$this->renderTextList(new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/subject"));
	}
	
	/**
	 * List all department topics as a text file with each line being Id|DisplayName
	 * 
	 * @return void
	 * @access public
	 * @since 10/20/09
	 */
	public function listrequirementstxtAction () {
		$this->renderTextList(new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/requirement"));
	}
	
	/**
	 * List all level topics as a text file with each line being Id|DisplayName
	 * 
	 * @return void
	 * @access public
	 * @since 1/15/10
	 */
	public function listlevelstxtAction () {
		$this->renderTextList(new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/level"));
	}
	
	/**
	 * List all department topics as a text file with each line being Id|DisplayName
	 * 
	 * @return void
	 * @access public
	 * @since 10/20/09
	 */
	public function listdepartmentstxtAction () {
		$this->renderTextList(new phpkit_type_URNInetType("urn:inet:middlebury.edu:genera:topic/department"));
	}
	
	/**
	 * Render a text feed for a given topic type.
	 * 
	 * @param osid_type_Type $genusType
	 * @return void
	 * @access private
	 * @since 11/17/09
	 */
	private function renderTextList (osid_type_Type $genusType) {
		header('Content-Type: text/plain');
		
		if ($this->_getParam('catalog')) {
			$catalogId = $this->_helper->osidId->fromString($this->_getParam('catalog'));
			$lookupSession = $this->_helper->osid->getCourseManager()->getTopicLookupSessionForCatalog($catalogId);
			$this->view->title = 'Topics in '.$lookupSession->getCourseCatalog()->getDisplayName();
		} else {
			$lookupSession = $this->_helper->osid->getCourseManager()->getTopicLookupSession();
			$this->view->title = 'Topics in All Catalogs';
		}
		$lookupSession->useFederatedCourseCatalogView();
		
		$topics = $lookupSession->getTopicsByGenusType($genusType);
		
		while ($topics->hasNext()) {
			$topic = $topics->getNextTopic();
			print $this->_helper->osidId->toString($topic->getId())."|".$this->_helper->osidId->toString($topic->getId())." - ".$topic->getDisplayName()."\n";
		}
		
		exit;
	}
	
}

?>