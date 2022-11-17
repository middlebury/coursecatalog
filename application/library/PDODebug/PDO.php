<?php
/**
 * @since 4/27/09
 * @package PDODebug
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This class is an extension of the PDO object that will log debugging information
 * about the number of statements prepared and executed.
 *
 * @since 4/27/09
 * @package PDODebug
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class PDODebug_PDO
	extends PDO
{
	public $preparations = 0;
	public $executions = 0;
	public $execs = 0;
	public $queries = 0;
	public $cursorsClosed = 0;

	private $checkForDuplicatePreparation = false;
	private $preparedQueries = array();
	private $duplicateQueries = array();

	public function __construct($dsn, $username="", $password="", $driver_options=array()) {
		parent::__construct($dsn, $username, $password, $driver_options);
		$this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('PDODebug_PDOStatement', array($this)));
	}

	/**
	 *  Execute an SQL statement and return the number of affected rows
	 *
	 * @param string $statement
	 * @return int
	 * @access public
	 * @since 4/27/09
	 */
	public function exec ($statement): int|false {
		$this->execs++;
		return parent::exec($statement);
	}

	/**
	 * Executes an SQL statement, returning a result set as a PDOStatement object
	 *
	 * @param string $statement
	 * @param optional int $fetchMode
	 * @param optional mixed $fetchModeArgs
	 * @return PDOStatement
	 * @access public
	 * @since 4/27/09
	 */
	public function query ($query, $fetchMode = PDO::FETCH_BOTH, ...$fetchModeArgs): PDOStatement|false {
		$this->queries++;
		return parent::query($statement, $fetchMode, ...$fetchModeArgs);
	}

	/**
	 * Prepare a statement
	 *
	 * @param string $statement
	 * @param optional array $driver_options
	 * @return PDOStatement
	 * @access public
	 * @since 4/27/09
	 */
	public function prepare ($statement, $driver_options = array()): PDOStatement {
		$this->preparations++;
		if ($this->checkForDuplicatePreparation) {
			if (in_array($statement, $this->preparedQueries)) {
				$hash = md5($statement);
				if (!isset($this->duplicateQueries[$hash])) {
					$this->duplicateQueries[$hash] = array('count' => 0, 'query' => $statement);
				}
				$this->duplicateQueries[$hash]['count']++;
			}
			$this->preparedQueries[] = $statement;
		}
		return parent::prepare($statement, $driver_options);
	}

	/**
	 * Reset counters
	 *
	 * @return void
	 * @access public
	 * @since 4/27/09
	 */
	public function resetCounters () {
		$this->preparations = 0;
		$this->executions = 0;
		$this->execs = 0;
		$this->queries = 0;
		$this->cursorsClosed = 0;
		$this->preparedQueries = array();
		$this->duplicateQueries = array();
	}

	/**
	 * Answer an array of all counter values
	 *
	 * @return array
	 * @access public
	 * @since 4/27/09
	 */
	public function getCounters () {
		return array(
				'PDO::exec()'					=> $this->execs,
				'PDO::query()'					=> $this->queries,
				'PDO::prepare()' 				=> $this->preparations,
				'PDOStatement::execute()'		=> $this->executions,
				'PDOStatement::closeCursor()'	=> $this->cursorsClosed
			);
	}

	/**
	 * Set this connection to record duplicate query preparations.
	 *
	 * @return void
	 * @access public
	 * @since 4/28/09
	 */
	public function recordDuplicates () {
		$this->checkForDuplicatePreparation = true;
	}

	/**
	 * Answer an array of all duplicate queries
	 *
	 * @return array
	 * @access public
	 * @since 4/28/09
	 */
	public function getDuplicates () {
		if (!$this->checkForDuplicatePreparation)
			throw new Exception("Duplicates have not been recorded. Use recordDuplicates() to start recording.");
		return $this->duplicateQueries;
	}
}
