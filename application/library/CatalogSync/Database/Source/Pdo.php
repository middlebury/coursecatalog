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
class CatalogSync_Database_Source_Pdo
	extends CatalogSync_Database_PdoAbstract
	implements CatalogSync_Database_Source
{

	/**
	 * Answer some database options for our connection.
	 *
	 * @param string $type
	 * @return array
	 */
	protected function getDatabaseOptions($type) {
		$options = parent::getDatabaseOptions($type);
		$options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		return $options;
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
		// Strip the 'SATURN.' or 'GENERAL.' prefixes from table-names.
		preg_match('/^(.+\.)?(.+)$/', $table, $m);
		$table = $m[2];

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
		$statement = $this->pdo->query($query);

		// Return our Select object that can handle converting results.
		return new CatalogSync_Database_Statement_Select_Pdo($statement);
	}



}
