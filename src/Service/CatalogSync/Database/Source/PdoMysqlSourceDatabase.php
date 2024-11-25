<?php
/**
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service\CatalogSync\Database\Source;

use App\Service\CatalogSync\Database\AbstractPdoDatabase;
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
class PdoMysqlSourceDatabase extends AbstractPdoDatabase implements SourceDatabase
{
    /**
     * Answer some database options for our connection.
     *
     * @param string $type
     */
    protected function getDatabaseOptions($type): array
    {
        $options = parent::getDatabaseOptions($type);
        $options[\PDO::ATTR_ERRMODE] = \PDO::ERRMODE_EXCEPTION;

        return $options;
    }

    /**
     * Select results from a table.
     *
     * @param string $table
     * @param optional array $columns
     * @param optional string $where
     *
     * @return App\Service\CatalogSync\Database\SelectStatement
     */
    public function query($table, array $columns = [], $where = ''): SelectStatement
    {
        // Strip the 'SATURN.' or 'GENERAL.' prefixes from table-names.
        preg_match('/^(.+\.)?(.+)$/', $table, $m);
        $table = $m[2];

        // Build the query.
        if (empty($columns)) {
            $column_list = '*';
        } else {
            $column_list = implode(', ', $columns);
        }
        $query = "SELECT $column_list FROM $table";
        if (!empty($where)) {
            $query .= " $where";
        }

        // Parse and Execute the statement
        $statement = $this->pdo->query($query);

        // Return our Select object that can handle converting results.
        return new PdoMysqlSelectStatement($statement);
    }

    /**
     * Count results in a table.
     *
     * @param string $table
     * @param optional string $where
     */
    public function count($table, $where = ''): int
    {
        $query = "SELECT COUNT(*) as num_rows FROM $table";
        if (!empty($where)) {
            $query .= " $where";
        }

        // Parse and Execute the statement
        return (int) $this->pdo->query($query)->fetchColumn();
    }
}
