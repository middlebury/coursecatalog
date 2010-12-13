<?php

/**
 * A helper to provide access to the CourseManager OSID and OSID configuration.
 * 
 * @since 6/9/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalog_View_Helper_GetStringFromOsidId
	extends Catalog_Action_Helper_AbstractOsidIdentifier
{
	/**
	 * Answer a string representation of an OSID id object
	 * 
	 * @param osid_id_Id $id
	 * @return string
	 * @access public
	 * @since 4/21/09
	 */
	public function getStringFromOsidId (osid_id_Id $id) {
		return Zend_Controller_Action_HelperBroker::getStaticHelper('OsidId')->toString($id);
	}
}

?>