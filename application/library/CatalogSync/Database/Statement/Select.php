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
interface CatalogSync_Database_Statement_Select
{

	/**
	 * Fetch the next row of values
	 *
	 * @return object
	 * @access public
	 */
	public function fetch ();

	/**
	 * Close this query's cursor if open.
	 *
	 * @return null
	 * @access public
	 */
	public function closeCursor ();

	/**
	 * Configure conversion for a column value
	 *
	 * @param string $column
	 * @return null
	 * @access public
	 */
	public function convertDate ($column);

	/**
	 * Configure conversion for a column value
	 *
	 * @param string $column
	 * @return null
	 * @access public
	 */
	public function convertText ($column);

	/**
	 * Configure conversion for a column value
	 *
	 * @param string $column
	 * @return null
	 * @access public
	 */
	public function convertBin2Hex ($column);

}
