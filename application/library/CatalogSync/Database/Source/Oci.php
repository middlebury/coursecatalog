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

	protected $config;
	protected $handle;
	protected $name;

	/**
	 * Constructor.
	 *
	 * @param string $name
	 */
	public function __construct($name) {
		$this->name = $name;
	}

	/**
	 * Configure this sync instance
	 *
	 * @param Zend_Config $config
	 * @return void
	 * @access public
	 */
	public function configure (Zend_Config $config) {
		$this->config = $this->validateConfig($config);
	}

	/**
	 * Validate options for a banner configuration.
	 *
	 * @param Zend_Config $config
	 * @return Zend_Config
	 */
	protected function validateConfig(Zend_Config $config) {
		// Check our configuration
		if (empty($config->tns)) {
			throw new Exception($this->name.'.tns must be specified in the config.');
		}
		if (empty($config->username)) {
			throw new Exception($this->name.'.username must be specified in the config.');
		}
		if (empty($config->password)) {
			$config->password = '';
		}
		return $config;
	}

	/**
	 * Set up connections to our source and destination.
	 *
	 * @return void
	 * @access public
	 */
	public function connect () {
		$this->handle = oci_connect($this->config->username, $this->config->password, $this->config->tns, "UTF8");
		if (!$this->handle) {
			$error = oci_error();
			throw new Exception('Oracle connect failed with message: '.$error['message'], $error['code']);
		}
	}

	/**
	 * Disconnect from our databases
	 *
	 * @return void
	 * @access public
	 */
	public function disconnect () {
		oci_close($this->handle);
	}

	/**
	 * Select results from a table
	 *
	 * @param string $table
	 * @param optional array $columns
	 * @param optional string $where
	 * @param optional array $whereArgs
	 *   An array or placeholder arguments for the where clause. Example:
	 *     $where = 'first_name = :fname AND surname = :lname'
	 *     $whereArgs = [':fname' => 'John', ':lname' => 'Doe']
	 * @return CatalogSync_Database_Statement_Select
	 * @access public
	 */
	public function query ($table, array $columns = array(), $where = '', $whereArgs = []) {
		// Build the query.
		if (empty($columns)) {
			$column_list = '*';
		} else {
			$column_list = implode(', ', $columns);
		}
		$query = "SELECT $column_list FROM $table";
		if (!empty($where)) {
			$query .= " WHERE $where";
		}

		// Parse and Execute the statement
		$statement = oci_parse($this->handle, $query);
		if ($error = oci_error($this->handle)) {
			throw new Exception($error['message'], $error['code']);
		}
		if (!empty($whereArgs)) {
			foreach ($whereArgs as $name => $value) {
				oci_bind_by_name($statement, $name, $value);
			}
		}
		oci_execute($statement);
		if ($error = oci_error($this->handle)) {
			throw new Exception($error['message'], $error['code']);
		}

		// Return our Select object that can handle converting results.
		return new CatalogSync_Database_Statement_Select_Oci($statement);
	}

	/**
	 * Count results in a table
	 *
	 * @param string $table
	 * @param optional string $where
	 * @return int
	 * @access public
	 */
	public function count ($table, $where = '') {
		$query = "SELECT COUNT(*) as NUM_ROWS FROM $table";
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

		$result = new CatalogSync_Database_Statement_Select_Oci($statement);
		$row = $result->fetch();
		$result->closeCursor();
		return intval($row->NUM_ROWS);
	}

}
