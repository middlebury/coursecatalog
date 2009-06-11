<?php
/**
 * @since 4/20/09
 * @package catalog.controllers
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * A controller for accessing catalogs
 * 
 * @since 4/20/09
 * @package catalog.controllers
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class CatalogsController
	extends AbstractCatalogController
{
	public function indexAction () {
		$this->_forward('list');
	}
	
	/**
	 * Print out a list of all catalogs
	 * 
	 * @return void
	 * @access public
	 * @since 4/21/09
	 */
	public function listAction() {
		$lookupSession = self::getCourseManager()->getCourseCatalogLookupSession();
		$this->view->catalogs = $lookupSession->getCourseCatalogs();
		
		$this->view->title = 'Available Catalogs';
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
		$catalogId = self::getOsidIdFromString($this->_getParam('catalog'));
		if ($this->_getParam('term')) {
			try {
				// Verify that the term is valid in this catalog
				$termLookup = self::getCourseManager()->getTermLookupSession();
				$termLookup->useFederatedCourseCatalogView();
				$termId = $termLookup->getTerm(self::getOsidIdFromString($this->_getParam('term')))->getId();
			} catch (osid_NotFoundException $e) {
			}
		}
		if (!isset($termId)) {
			$termId = self::getCurrentTermId($catalogId);
		}
		
		$this->_forward('search', 'Offerings', null, array(
				'catalog'	=> self::getStringFromOsidId($catalogId),
				'term' 		=> self::getStringFromOsidId($termId)
			));
	}
}

?>