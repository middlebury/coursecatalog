<?php

/**
 * @since 4/27/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This class is an extension of the PDO object that will log debugging information
 * about the number of statements prepared and executed.
 *
 * @since 4/27/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class PDODebug_PDO extends PDO
{
    public $preparations = 0;
    public $executions = 0;
    public $execs = 0;
    public $queries = 0;
    public $cursorsClosed = 0;

    private $checkForDuplicatePreparation = false;
    private $preparedQueries = [];
    private $duplicateQueries = [];

    public function __construct($dsn, $username = '', $password = '', $driver_options = [])
    {
        parent::__construct($dsn, $username, $password, $driver_options);
        $this->setAttribute(PDO::ATTR_STATEMENT_CLASS, ['PDODebug_PDOStatement', [$this]]);
    }

    /**
     *  Execute an SQL statement and return the number of affected rows.
     *
     * @param string $statement
     *
     * @return int
     *
     * @since 4/27/09
     */
    public function exec($statement): int|false
    {
        ++$this->execs;

        return parent::exec($statement);
    }

    /**
     * Executes an SQL statement, returning a result set as a PDOStatement object.
     *
     * @param optional int $fetchMode
     * @param optional mixed $fetchModeArgs
     *
     * @return PDOStatement
     *
     * @since 4/27/09
     */
    public function query($query, $fetchMode = PDO::FETCH_BOTH, ...$fetchModeArgs): PDOStatement|false
    {
        ++$this->queries;

        return parent::query($statement, $fetchMode, ...$fetchModeArgs);
    }

    /**
     * Prepare a statement.
     *
     * @param string $statement
     * @param optional array $driver_options
     *
     * @since 4/27/09
     */
    public function prepare($statement, $driver_options = []): PDOStatement
    {
        ++$this->preparations;
        if ($this->checkForDuplicatePreparation) {
            if (in_array($statement, $this->preparedQueries)) {
                $hash = md5($statement);
                if (!isset($this->duplicateQueries[$hash])) {
                    $this->duplicateQueries[$hash] = ['count' => 0, 'query' => $statement];
                }
                ++$this->duplicateQueries[$hash]['count'];
            }
            $this->preparedQueries[] = $statement;
        }

        return parent::prepare($statement, $driver_options);
    }

    /**
     * Reset counters.
     *
     * @return void
     *
     * @since 4/27/09
     */
    public function resetCounters()
    {
        $this->preparations = 0;
        $this->executions = 0;
        $this->execs = 0;
        $this->queries = 0;
        $this->cursorsClosed = 0;
        $this->preparedQueries = [];
        $this->duplicateQueries = [];
    }

    /**
     * Answer an array of all counter values.
     *
     * @return array
     *
     * @since 4/27/09
     */
    public function getCounters()
    {
        return [
            'PDO::exec()' => $this->execs,
            'PDO::query()' => $this->queries,
            'PDO::prepare()' => $this->preparations,
            'PDOStatement::execute()' => $this->executions,
            'PDOStatement::closeCursor()' => $this->cursorsClosed,
        ];
    }

    /**
     * Set this connection to record duplicate query preparations.
     *
     * @return void
     *
     * @since 4/28/09
     */
    public function recordDuplicates()
    {
        $this->checkForDuplicatePreparation = true;
    }

    /**
     * Answer an array of all duplicate queries.
     *
     * @return array
     *
     * @since 4/28/09
     */
    public function getDuplicates()
    {
        if (!$this->checkForDuplicatePreparation) {
            throw new Exception('Duplicates have not been recorded. Use recordDuplicates() to start recording.');
        }

        return $this->duplicateQueries;
    }
}
