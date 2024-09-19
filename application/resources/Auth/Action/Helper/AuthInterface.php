<?php
/**
 * @since 6/14/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * An interface for authentication helpers.
 *
 * @since 6/14/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface Auth_Action_Helper_AuthInterface
{
    /**
     * Answer true if this authentication method allows login.
     *
     * @return bool
     */
    public function isAuthenticationEnabled();

    /**
     * Log in. Throw an exception if isAuthenticationEnabled is false.
     *
     * @param optional string $returnUrl A url to return to after successful login
     *
     * @return bool TRUE on successful login
     */
    public function login($returnUrl = null);

    /**
     * Log out. Throw an exception if isAuthenticationEnabled is false.
     *
     * @param optional string $returnUrl A url to return to after successful logout
     *
     * @return void
     */
    public function logout($returnUrl = null);

    /**
     * Answer true if a user is currently authenticated.
     *
     * @return bool
     */
    public function isAuthenticated();

    /**
     * Answer the user id if a user is currently authenticated or throw an Exception
     * if isAuthenticated is false.
     *
     * @return string
     */
    public function getUserId();

    /**
     * Answer a name for the user if a user is currently authenticated or throw an Exception
     * if isAuthenticated is false.
     *
     * @return string
     */
    public function getUserDisplayName();

    /**
     * Answer an email address for the user if a user is currently authenticated or throw an Exception
     * if isAuthenticated is false.
     *
     * @return string
     */
    public function getUserEmail();

    /**
     * Answer an array of groups for the user if a user is currently authenticated or throw an Exception
     * if isAuthenticated is false.
     *
     * @return array
     */
    public function getUserGroups();
}
