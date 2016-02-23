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
class CatalogSync_Database_Source_Oci
 	implements CatalogSync_Database_Source
{

	protected $handle;

	/**
	 * Constructor.
	 *
	 * @param resource $handle
	 */
	public function __construct($handle) {
		$this->handle = $handle;
	}

	/**
	 * Select results from a table
	 *
	 * @param string $table
	 * @param optional array $columns
	 * @param optional string $where
	 * @return CatalogSync_Database_Statement_Select
	 * @access public
	 */
	public function query ($table, array $columns = array(), $where = '') {
		// Build the query.
		if (empty($columns)) {
			$column_list = '*';
		} else {
			$column_list = implode(', ', $columns);
		}
		$query = "SELECT $column_list FROM $table";
		if (!empty($where)) {
			$query .= " $where";
		}

		// Parse and Execute the statement
		$statement = oci_parse($this->handle, $query);
		if ($error = oci_error($this->handle)) {
			throw new Exception($error['message'], $error['code']);
		}
		oci_execute($statement);
		if ($error = oci_error($this->handle)) {
			throw new Exception($error['message'], $error['code']);
		}

		// Return our Select object that can handle converting results.
		return new CatalogSync_Database_Statement_Select_Oci($statement);
	}



}
