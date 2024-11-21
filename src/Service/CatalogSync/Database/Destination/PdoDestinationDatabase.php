<?php
/**
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service\CatalogSync\Database\Destination;

use App\Service\CatalogSync\Database\AbstractPdoDatabase;
use App\Service\CatalogSync\Database\DestinationDatabase;

/**
 * This interface defines the requirements of destination databases.
 *
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class PdoDestinationDatabase extends AbstractPdoDatabase implements DestinationDatabase
{
    /**
     * Begin a transaction.
     *
     * @return bool
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commit a transaction.
     *
     * @return bool
     */
    public function commit()
    {
        return $this->pdo->commit();
    }

    /**
     * Roll back an open transaction.
     *
     * @return bool
     */
    public function rollBack()
    {
        return $this->pdo->rollBack();
    }

    /**
     * Truncate a table.
     *
     * @param string $table
     *
     * @return null
     */
    public function truncate($table)
    {
        $statement = $this->pdo->prepare("DELETE FROM $table");
        $statement->execute();
    }

    /**
     * Prepare an insert statement.
     *
     * @param string $table
     *
     * @return App\Service\CatalogSync\Database\InsertStatement
     */
    public function prepareInsert($table, array $columns)
    {
        return new PdoInsertStatement($this->pdo, $table, $columns);
    }

    /**
     * Answer our PDO connection.
     *
     * @return PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }
}
