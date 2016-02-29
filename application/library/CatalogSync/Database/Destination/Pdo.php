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
class CatalogSync_Database_Destination_Pdo
	extends CatalogSync_Database_PdoAbstract
	implements CatalogSync_Database_Destination
{

	/**
	 * Begin a transaction.
	 *
	 * @return boolean
	 * @access public
	 */
	public function beginTransaction () {
		return $this->pdo->beginTransaction();
	}

	/**
	 * Commit a transaction.
	 *
	 * @return boolean
	 * @access public
	 */
	public function commit () {
		return $this->pdo->commit();
	}

	/**
	 * Roll back an open transaction.
	 *
	 * @return boolean
	 * @access public
	 */
	public function rollBack () {
		return $this->pdo->rollBack();
	}

	/**
	 * Truncate a table.
	 *
	 * @param string $table
	 * @return null
	 * @access public
	 */
	public function truncate ($table) {
		$statement = $this->pdo->prepare("DELETE FROM $table");
		$statement->execute();
	}

	/**
	 * Prepare an insert statement.
	 *
	 * @param string $table
	 * @param array $columns
	 * @return CatalogSync_Database_Statement_Insert
	 * @access public
	 */
	public function prepareInsert ($table, array $columns) {
		return new CatalogSync_Database_Statement_Insert_Pdo($this->pdo, $table, $columns);
	}

	/**
	 * Answer our PDO connection.
	 *
	 * @return PDO
	 */
	public function getPdo() {
		return $this->pdo;
	}

}
