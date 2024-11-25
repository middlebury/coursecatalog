<?php
/**
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service\CatalogSync\Database\Destination;

use App\Service\CatalogSync\Database\InsertStatement;
use App\Service\CatalogSync\Database\SelectStatement;

/**
 * This interface defines an API for Insert statements.
 *
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class PdoInsertStatement implements InsertStatement
{
    protected $statement;
    protected $columns = [];

    /**
     * Constructor.
     */
    public function __construct(\PDO $pdo, $table, array $columns)
    {
        // Build an array mapping columns to placeholder names.
        foreach ($columns as $column) {
            $this->columns[$column] = ':'.$column;
        }

        // Prepare our actual statement
        $column_list = implode(', ', array_keys($this->columns));
        $placeholder_list = implode(', ', array_values($this->columns));
        $this->statement = $pdo->prepare('INSERT INTO '.$table.' ('.$column_list.') VALUES ('.$placeholder_list.')');
    }

    /**
     * Insert all rows found in the SelectStatement.
     *
     * @param callable|null $rowPrepCallback
     *                                       A callback that will receive a reference to the currently selected row
     *                                       and will be able to modify the row before it is inserted
     *
     * @return null
     */
    public function insertAll(SelectStatement $select, ?callable $rowPrepCallback = null): void
    {
        while ($row = $select->fetch()) {
            if (null !== $rowPrepCallback) {
                call_user_func($rowPrepCallback, $row);
            }
            try {
                foreach ($this->columns as $column => $placeholder) {
                    $this->statement->bindValue($placeholder, $row->$column);
                }
                $this->statement->execute();
            } catch (\Exception $e) {
                ob_start();
                $p = $this->statement->debugDumpParams();
                $params = str_replace("\n", '; ', ob_get_clean());
                throw new \Exception(sprintf('Insert Error: %s. %s', $e->getMessage(), $params), $e->getCode(), $e);
            }
        }
    }
}
