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
class CatalogSync_Database_Statement_Insert_Pdo
	implements CatalogSync_Database_Statement_Insert
{

	protected $statement;
	protected $columns = array();

	/**
	 * Constructor.
	 *
	 * @param PDO $pdo
	 */
	public function __construct(PDO $pdo, $table, array $columns) {
		// Build an array mapping columns to placeholder names.
		foreach ($columns as $column) {
			$this->columns[$column] = ':'.$column;
		}

		// Prepare our actual statement
		$column_list = implode(", ", array_keys($this->columns));
		$placeholder_list = implode(", ", array_values($this->columns));
		$this->statement = $pdo->prepare("INSERT INTO ".$table." (".$column_list.") VALUES (".$placeholder_list.")");
	}

	/**
	 * Insert all rows found in the SelectStatement
	 *
	 * @param CatalogSync_Database_Statement_Select $select
	 * @return null
	 * @access public
	 */
	public function insertAll (CatalogSync_Database_Statement_Select $select) {
		while ($row = $select->fetch()) {
			foreach ($this->columns as $column => $placeholder) {
				$this->statement->bindValue($placeholder, $row->$column);
			}
			$this->statement->execute();
		}
	}

}
