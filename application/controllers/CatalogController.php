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
class CatalogController
	extends AbstractCatalogController
{
		
	public function listAction() {
		$lookupSession = $this->getCourseManager()->getCourseCatalogLookupSession();
		$this->view->catalogs = $lookupSession->getCourseCatalogs();
    }
	
}

?>