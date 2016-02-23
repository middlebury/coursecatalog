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
	 * @return null
	 * @access public
	 */
	public function insertAll (CatalogSync_Database_Statement_Select $select);

}
