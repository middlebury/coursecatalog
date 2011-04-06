<?php
/**
 * @since 6/14/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * An interface for masquerade authentication helpers
 * 
 * @since 6/14/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface Auth_Action_Helper_MasqueradeInterface {
	   
    /**
     * Change to a different user account. Clients are responsible for checking that 
     * the current user is authorized before calling this method. Clients are also
     * responsible for logging.
     *
     * Throws an exception if unsupported.
     * 
     * @param string $userId
     * @return void
     */
    public function changeUser ($userId);
	
}

?>