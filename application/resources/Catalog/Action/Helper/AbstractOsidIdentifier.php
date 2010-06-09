<?php

/**
 * A helper to provide access to the CourseManager OSID and OSID configuration.
 * 
 * @since 6/9/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class Catalog_Action_Helper_AbstractOsidIdentifier
	extends Zend_Controller_Action_Helper_Abstract
{
	private static $idAuthorityToShorten;
	
	/**
	 * Answer the id-authority for whom ids should be shortened.
	 * 
	 * @return mixed string or false if none should be shortened.
	 * @access protected
	 * @since 6/16/09
	 */
	protected function getIdAuthorityToShorten () {
		if (!isset(self::$idAuthorityToShorten)) {
			try {
				$authority = phpkit_configuration_ConfigUtil::getSingleValuedValue(
    								Zend_Controller_Action_HelperBroker::getStaticHelper('Osid')->getRuntimeManager()->getConfiguration(), 
    								new phpkit_id_URNInetId('urn:inet:middlebury.edu:config:catalog/shorten_ids_for_authority'),
    								new phpkit_type_Type('urn', 'middlebury.edu', 'Primitives/String'));
    			if (strlen($authority))
	    			self::$idAuthorityToShorten = $authority;
	    		else
	    			self::$idAuthorityToShorten = false;
    		} catch (osid_NotFoundException $e) {
    			self::$idAuthorityToShorten = false;
    		} catch (osid_ConfigurationErrorException $e) {
    			self::$idAuthorityToShorten = false;
    		}
		}
		return self::$idAuthorityToShorten;
	}
}

?>