<?php
/**
 * @since 2/23/16
 * @package CatalogSync
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This interface defines an API for Insert statements.
 *
 * @since 2/23/16
 * @package CatalogSync
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface CatalogSync_Database_Statement_Insert
{

	/**
	 * Insert all rows found in the SelectStatement
	 *
	 * @param CatalogSync_Database_Statement_Select $select
	 * @param callable|NULL $rowPrepCallback
	 *   A callback that will receive a reference to the currently selected row
	 *   and will be able to modify the row before it is inserted.
	 * @return null
	 * @access public
	 */
	public function insertAll (CatalogSync_Database_Statement_Select $select, callable $rowPrepCallback = NULL);

}
