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
		
		$this->render();
		
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
		}
		
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
	
}

?>