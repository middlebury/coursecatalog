<?php

/**
 * A helper to provide access to type information.
 * 
 * @since 6/9/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalog_Action_Helper_OsidTypes
	extends Catalog_Action_Helper_AbstractOsidIdentifier
{
	
	/**
	 * Answer the course offering genus types that should be searched by default
	 * for a catalog
	 * 
	 * @return array of osid_type_Types
	 * @access public
	 * @since 6/16/09
	 */
	public function getDefaultGenusTypes () {
		try {
			$types = array();
			$typeStrings = phpkit_configuration_ConfigUtil::getMultiValuedValue(
				Zend_Controller_Action_HelperBroker::getStaticHelper('Osid')->getRuntimeManager()->getConfiguration(), 
				new phpkit_id_URNInetId('urn:inet:middlebury.edu:config:catalog/default_offering_genus_types_to_search'),
				new phpkit_type_Type('urn', 'middlebury.edu', 'Primitives/String'));
			foreach ($typeStrings as $typeString) {
				$types[] = new phpkit_type_URNInetType($typeString);
			}
			return $types;
		} catch (osid_NotFoundException $e) {
		} catch (osid_ConfigurationErrorException $e) {
		}
		
		return array();
	}
}

?>