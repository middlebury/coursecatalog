<?php
/**
 * @since 4/13/09
 * @package banner.resource
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This interface defines the methods that a resource Manager in this package must
 * implement to give needed access to its sessions.
 *
 * @since 4/13/09
 * @package banner.resource
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface banner_resource_ResourceManagerInterface
	extends banner_ManagerInterface
{
	/**
	 * Answer the Id of the 'All'/'Combined' resource bin.
	 *
	 * @return osid_id_Id
	 * @access public
	 * @since 4/20/09
	 */
	public function getCombinedBinId ();
}
