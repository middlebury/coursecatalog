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
class OciSelectStatement implements SelectStatement
{
    protected $statement;
    protected $column_conversions = [];

    /**
     * Constructor.
     *
     * @param resource $statement
     */
    public function __construct($statement)
    {
        $this->statement = $statement;
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        if (isset($this->statement)) {
            oci_free_statement($this->statement);
        }
    }

    /**
     * Fetch the next row of values.
     *
     * @return object
     */
    public function fetch()
    {
        if (!isset($this->statement) || null === $this->statement) {
            throw new \Exception('Cannot fetch without a statement. Maybe it was already closed?');
        }
        // Fetch
        $row = oci_fetch_object($this->statement);
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
    public function closeCursor()
    {
        oci_free_statement($this->statement);
        unset($this->statement);
    }

    /**
     * Configure conversion for a column value.
     *
     * @param string $column
     *
     * @return null
     */
    public function convertDate($column)
    {
        $this->column_conversions[$column] = 'toMySQLDate';
    }

    /**
     * Convert an Oracle date to a MySQL date.
     *
     * @param string $value
     *
     * @return string
     */
    protected function toMySQLDate($value)
    {
        return date('Y-m-d', strtotime($value));
    }

    /**
     * Configure conversion for a column value.
     *
     * @param string $column
     *
     * @return null
     */
    public function convertText($column)
    {
        $this->column_conversions[$column] = 'loadText';
    }

    /**
     * Load text from an Oracle column if it exists.
     *
     * @return string
     */
    protected function loadText($value)
    {
        if (null === $value) {
            return null;
        } else {
            return $value->load();
        }
    }

    /**
     * Configure conversion for a column value.
     *
     * @param string $column
     *
     * @return null
     */
    public function convertBin2Hex($column)
    {
        $this->column_conversions[$column] = 'bin2Hex';
    }

    /**
     * Convert an Oracle binary column to a hex representation.
     *
     * @return string
     */
    protected function bin2Hex($value)
    {
        return bin2hex($value);
    }
}
