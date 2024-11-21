<?php
/**
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service\CatalogSync\Database;

/**
 * This interface defines the requirements of source databases.
 *
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface SourceDatabase extends Database
{
    /**
     * Select results from a table.
     *
     * @param string $table
     * @param optional array $columns
     * @param optional string $where
     *
     * @return CatalogSync_Database_Statement_Select
     */
    public function query($table, array $columns = [], $where = '');

    /**
     * Count results in a table.
     *
     * @param string $table
     * @param optional string $where
     *
     * @return int
     */
    public function count($table, $where = '');
}
