<?php
/**
 * @since 7/30/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * A helper to return the per-session key used to prevent cross-site request forgery attacks.
 *
 * @since 7/30/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalog_View_Helper_CsrfKey extends Zend_View_Helper_Abstract
{
    /**
     * Answer the CSRF key.
     *
     * @return string
     *
     * @since 7/30/10
     */
    public function csrfKey()
    {
        return Zend_Controller_Action_HelperBroker::getStaticHelper('CsrfKey')->direct();
    }
}
