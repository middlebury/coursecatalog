<?php

/**
 * A helper to provide access to the CourseManager OSID and OSID configuration.
 *
 * @since 6/9/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalog_View_Helper_GetOsidIdFromString
	extends Catalog_Action_Helper_AbstractOsidIdentifier
{

	/**
	 * Get and OSID id object from a string.
	 *
	 * @param string $idString
	 * @return osid_id_Id
	 * @access public
	 * @since 4/21/09
	 */
	public function getOsidIdFromString ($idString) {
		return Zend_Controller_Action_HelperBroker::getStaticHelper('OsidId')->fromString($idString);
	}
}
