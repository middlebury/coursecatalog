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
class PDODebug_PDOStatement extends PDOStatement
{
    public $dbh;

    protected function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    /**
     * Execute the statement.
     *
     * @param optional array $input_parameters
     *
     * @since 4/27/09
     */
    public function execute($input_parameters = []): bool
    {
        ++$this->dbh->executions;

        return parent::execute($input_parameters);
    }

    /**
     * Closes the cursor, enabling the statement to be executed again.
     *
     * @since 4/27/09
     */
    public function closeCursor(): bool
    {
        ++$this->dbh->cursorsClosed;

        return parent::closeCursor();
    }
}
