<?php

/**
 * A helper to work with the currently configured authentication method.
 *
 * @since 6/9/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalog_View_Helper_IsAuthenticationEnabled
	extends Catalog_Action_Helper_AbstractOsidIdentifier
{

	/**
	 * Answer TRUE if a user is currently authenticated.
	 *
	 * @return boolean
	 * @access public
	 * @since 4/21/09
	 */
	public function isAuthenticationEnabled () {
		try {
			return Zend_Controller_Action_HelperBroker::getStaticHelper('Auth')->getHelper()->isAuthenticationEnabled();
		} catch (Exception $e) {
			if ($e->getCode() == 450)
				return false;
			else
				throw $e;
		}
	}
}
