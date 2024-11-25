<?php
/**
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service\CatalogSync\Database;

/**
 * This interface defines the requirements of destination databases.
 *
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface DestinationDatabase extends Database
{
    /**
     * Begin a transaction.
     */
    public function beginTransaction(): bool;

    /**
     * Commit a transaction.
     */
    public function commit(): bool;

    /**
     * Roll back an open transaction.
     */
    public function rollBack(): bool;

    /**
     * Truncate a table.
     *
     * @param string $table
     *
     * @return null
     */
    public function truncate($table): void;

    /**
     * Prepare an insert statement.
     *
     * @param string $table
     *
     * @return App\Service\CatalogSync\Database\InsertStatement
     */
    public function prepareInsert($table, array $columns): InsertStatement;
}
