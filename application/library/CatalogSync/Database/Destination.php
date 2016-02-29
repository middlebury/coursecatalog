<?php
/**
 * @since 2/23/16
 * @package CatalogSync
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This interface defines the requirements of destination databases.
 *
 * @since 2/23/16
 * @package CatalogSync
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface CatalogSync_Database_Destination
	extends CatalogSync_Database
{

	/**
	 * Begin a transaction.
	 *
	 * @return boolean
	 * @access public
	 */
	public function beginTransaction ();

	/**
	 * Commit a transaction.
	 *
	 * @return boolean
	 * @access public
	 */
	public function commit ();

	/**
	 * Roll back an open transaction.
	 *
	 * @return boolean
	 * @access public
	 */
	public function rollBack ();

	/**
	 * Truncate a table.
	 *
	 * @param string $table
	 * @return null
	 * @access public
	 */
	public function truncate ($table);

	/**
	 * Prepare an insert statement.
	 *
	 * @param string $table
	 * @param array $columns
	 * @return CatalogSync_Database_Statement_Insert
	 * @access public
	 */
	public function prepareInsert ($table, array $columns);

}
