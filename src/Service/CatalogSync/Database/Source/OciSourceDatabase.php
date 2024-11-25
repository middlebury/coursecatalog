<?php
/**
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service\CatalogSync\Database\Source;

use App\Service\CatalogSync\Database\SelectStatement;
use App\Service\CatalogSync\Database\SourceDatabase;

/**
 * This interface defines the requirements of source databases.
 *
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class OciSourceDatabase implements SourceDatabase
{
    protected $handle;

    /**
     * Constructor.
     */
    public function __construct(
        private string $tns,
        private string $username,
        private string $password,
    ) {
    }

    /**
     * Set up connections to our source and destination.
     */
    public function connect(): void
    {
        $this->handle = oci_connect($this->username, $this->password, $this->tns, 'UTF8');
        if (!$this->handle) {
            $error = oci_error();
            throw new \Exception('Oracle connect failed with message: '.$error['message'], $error['code']);
        }
    }

    /**
     * Disconnect from our databases.
     */
    public function disconnect(): void
    {
        oci_close($this->handle);
    }

    /**
     * Select results from a table.
     *
     * @param string $table
     * @param optional array $columns
     * @param optional string $where
     * @param optional array $whereArgs
     *   An array or placeholder arguments for the where clause. Example:
     *     $where = 'first_name = :fname AND surname = :lname'
     *     $whereArgs = [':fname' => 'John', ':lname' => 'Doe']
     *
     * @return App\Service\CatalogSync\Database\SelectStatement
     */
    public function query($table, array $columns = [], $where = '', $whereArgs = []): SelectStatement
    {
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
            throw new \Exception($error['message'], $error['code']);
        }
        if (!empty($whereArgs)) {
            foreach ($whereArgs as $name => $value) {
                oci_bind_by_name($statement, $name, $value);
            }
        }
        oci_execute($statement);
        if ($error = oci_error($this->handle)) {
            throw new \Exception($error['message'], $error['code']);
        }

        // Return our Select object that can handle converting results.
        return new OciSelectStatement($statement);
    }

    /**
     * Count results in a table.
     *
     * @param string $table
     * @param optional string $where
     */
    public function count($table, $where = ''): int
    {
        $query = "SELECT COUNT(*) as NUM_ROWS FROM $table";
        if (!empty($where)) {
            $query .= " $where";
        }

        // Parse and Execute the statement
        $statement = oci_parse($this->handle, $query);
        if ($error = oci_error($this->handle)) {
            throw new \Exception($error['message'], $error['code']);
        }
        oci_execute($statement);
        if ($error = oci_error($this->handle)) {
            throw new \Exception($error['message'], $error['code']);
        }

        $result = new OciSelectStatement($statement);
        $row = $result->fetch();
        $result->closeCursor();

        return (int) $row->NUM_ROWS;
    }
}
