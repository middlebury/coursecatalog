<?php
/**
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service\CatalogSync\Database\Source;

use App\Service\CatalogSync\Database\SelectStatement;

/**
 * This interface defines the requirements of source databases.
 *
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class PdoMysqlSelectStatement implements SelectStatement
{
    protected $statement;
    protected $column_conversions = [];

    /**
     * Constructor.
     */
    public function __construct(\PDOStatement $statement)
    {
        $this->statement = $statement;
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        $this->closeCursor();
    }

    /**
     * Fetch the next row of values.
     */
    public function fetch(): object|false
    {
        if (!isset($this->statement) || null === $this->statement) {
            throw new \Exception('Cannot fetch without a statement. Maybe it was already closed?');
        }
        // Fetch
        $row = $this->statement->fetch(\PDO::FETCH_OBJ, \PDO::FETCH_ORI_NEXT);
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
     */
    public function closeCursor(): void
    {
        $this->statement->closeCursor();
    }

    /**
     * Configure conversion for a column value.
     *
     * @param string $column
     *
     * @return null
     */
    public function convertDate($column): void
    {
        // Do nothing -- data should already be converted.
    }

    /**
     * Configure conversion for a column value.
     *
     * @param string $column
     *
     * @return null
     */
    public function convertText($column): void
    {
        // Do nothing -- data should already be converted.
    }

    /**
     * Configure conversion for a column value.
     *
     * @param string $column
     *
     * @return null
     */
    public function convertBin2Hex($column): void
    {
        // Do nothing -- data should already be converted.
    }
}
