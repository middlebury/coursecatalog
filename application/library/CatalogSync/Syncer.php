<?php
/**
 * @since 2/22/16
 * @package CatalogSync
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This interface defines the requirements of Sync classes. Classes SHOULD throw
 *
 *
 * @since 2/22/16
 * @package CatalogSync
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface CatalogSync_Syncer
{

	/**
	 * Configure this sync instance
	 *
	 * @param Zend_Config $config
	 * @return void
	 * @access public
	 */
	public function configure (Zend_Config $config);

	/**
	 * Roll back any changes to the destination.
	 *
	 * @return void
	 * @access public
	 */
	public function rollback ();

	/**
	 * Set up connections to our source and destination.
	 *
	 * @return void
	 * @access public
	 */
	public function connect ();

	/**
	 * Take actions before copying data.
	 *
	 * @return void
	 * @access public
	 */
	public function preCopy ();

	/**
	 * Copy data.
	 *
	 * @return void
	 * @access public
	 */
	public function copy ();

	/**
	 * Take actions after copying data.
	 *
	 * @return void
	 * @access public
	 */
	public function postCopy ();

	/**
	 * Update derived data in the destination database.
	 *
	 * @return void
	 * @access public
	 */
	public function updateDerived ();

	/**
	 * Disconnect from our databases
	 *
	 * @return void
	 * @access public
	 */
	public function disconnect ();

	/**
	 * Answer an array of non-fatal errors that should be mailed.
	 *
	 * @return array
	 *   An array of error messages.
	 * @access public
	 */
	public function getNonFatalErrors ();
}
