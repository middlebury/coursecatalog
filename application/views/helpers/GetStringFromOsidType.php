<?php

/**
 * A helper to convert OSID Types to strings.
 * 
 * @since 6/9/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalog_View_Helper_GetStringFromOsidType
	extends Catalog_Action_Helper_AbstractOsidIdentifier
{
	/**
	 * Answer a string representation of an OSID type object
	 * 
	 * @param osid_type_Type $type
	 * @return string
	 * @access public
	 * @since 4/21/09
	 */
	public function getStringFromOsidType (osid_type_Type $type) {
		return Zend_Controller_Action_HelperBroker::getStaticHelper('OsidType')->toString($type);
	}
}

?>