<?php
/**
 * @since 2/23/16
 * @package CatalogSync
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This interface defines the requirements of source databases.
 *
 * @since 2/23/16
 * @package CatalogSync
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface CatalogSync_Database_Source
	extends CatalogSync_Database
{

	/**
	 * Select results from a table
	 *
	 * @param string $table
	 * @param optional array $columns
	 * @param optional string $where
	 * @return CatalogSync_Database_Statement_Select
	 * @access public
	 */
	public function query ($table, array $columns = array(), $where = '');

	/**
	 * Count results in a table
	 *
	 * @param string $table
	 * @param optional string $where
	 * @return int
	 * @access public
	 */
	public function count ($table, $where = '');

}
