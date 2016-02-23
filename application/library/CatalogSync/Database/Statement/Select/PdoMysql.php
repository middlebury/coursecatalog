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

class CatalogSync_Database_Statement_Select_PdoMysql
	implements CatalogSync_Database_Statement_Select
{

	protected $statement;
	protected $column_conversions = array();

	/**
	 * Constructor
	 *
	 * @param PDOStatement $statement
	 */
	public function __construct(PDOStatement $statement) {
		$this->statement = $statement;
	}

	/**
	 * Destructor
	 *
	 */
	public function __destruct() {
		$this->closeCursor();
	}

	/**
	 * Fetch the next row of values
	 *
	 * @return object
	 * @access public
	 */
	public function fetch () {
		if (!isset($this->statement) || is_null($this->statement)) {
			throw new Exception('Cannot fetch without a statement. Maybe it was already closed?');
		}
		// Fetch
		$row = $this->statement->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_NEXT);
		// End of results
		if (!$row) {
			return false;
		}

		// Apply any conversions for our result rows.
		foreach ($this->column_conversions as $column => $function) {
			$row->$column = $this->$function($row->$column);
		}

		return $row;
	}

	/**
	 * Close this query's cursor if open.
	 *
	 * @return null
	 * @access public
	 */
	public function closeCursor () {
		$this->statement->closeCursor();
	}

	/**
	 * Configure conversion for a column value
	 *
	 * @param string $column
	 * @return null
	 * @access public
	 */
	public function convertDate ($column) {
		// Do nothing -- data should already be converted.
	}

	/**
	 * Configure conversion for a column value
	 *
	 * @param string $column
	 * @return null
	 * @access public
	 */
	public function convertText ($column) {
		// Do nothing -- data should already be converted.
	}

	/**
	 * Configure conversion for a column value
	 *
	 * @param string $column
	 * @return null
	 * @access public
	 */
	public function convertBin2Hex ($column) {
		$this->column_conversions[$column] = 'bin2Hex';
	}

	/**
	 * Convert an Oracle binary column to a hex representation.
	 *
	 * @param mixed $value
	 * @return string
	 * @access protected
	 */
	protected function bin2Hex($value) {
		return bin2hex($value);
	}

}
