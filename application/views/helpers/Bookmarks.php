<?php
/**
 * @since 7/30/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * A helper to return the bookmarks model.
 * 
 * @since 7/30/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalog_View_Helper_Bookmarks
	extends Zend_View_Helper_Abstract
{
		
	/**
	 * Answer the Bookmarks.
	 * 
	 * @return Bookmarks
	 * @access public
	 * @since 7/30/10
	 */
	public function bookmarks () {
		return Zend_Controller_Action_HelperBroker::getStaticHelper('Bookmarks')->direct();
	}
	
}

?>