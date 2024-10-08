<?php

/**
 * A helper to work with the currently configured authentication method.
 *
 * @since 6/9/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalog_View_Helper_GetUserEmail extends Catalog_Action_Helper_AbstractOsidIdentifier
{
    /**
     * Answer the email address if a user is currently authenticated.
     *
     * @return string
     *
     * @since 4/21/09
     */
    public function getUserEmail()
    {
        return Zend_Controller_Action_HelperBroker::getStaticHelper('Auth')->getHelper()->getUserEmail();
    }
}
