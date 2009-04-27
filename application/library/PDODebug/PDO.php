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
	public function exec ($statement) {
		$this->execs++;
		return parent::exec($statement);
	}
	
	/**
	 * Executes an SQL statement, returning a result set as a PDOStatement object 
	 * 
	 * @param string $statement
	 * @param optional int $fetchType
	 * @param optional mixed $arg3
	 * @param optional array $ctorargs
	 * @return PDOStatement
	 * @access public
	 * @since 4/27/09
	 */
	public function query ($statement, $fetchType = PDO::FETCH_BOTH, $arg3 = null, $ctorargs = null) {
		$this->queries++;
		return parent::query($statement, $fetchType, $arg3, $ctorargs);
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
	public function prepare ($statement, $driver_options = array()) {
		$this->preparations++;
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
}

?>