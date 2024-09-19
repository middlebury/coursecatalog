<?php
/**
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This interface defines the requirements of destination databases.
 *
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface CatalogSync_Database_Destination extends CatalogSync_Database
{
    /**
     * Begin a transaction.
     *
     * @return bool
     */
    public function beginTransaction();

    /**
     * Commit a transaction.
     *
     * @return bool
     */
    public function commit();

    /**
     * Roll back an open transaction.
     *
     * @return bool
     */
    public function rollBack();

    /**
     * Truncate a table.
     *
     * @param string $table
     *
     * @return null
     */
    public function truncate($table);

    /**
     * Prepare an insert statement.
     *
     * @param string $table
     *
     * @return CatalogSync_Database_Statement_Insert
     */
    public function prepareInsert($table, array $columns);
}
