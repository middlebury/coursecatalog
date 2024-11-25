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
interface SelectStatement
{
    /**
     * Fetch the next row of values.
     */
    public function fetch(): object|false;

    /**
     * Close this query's cursor if open.
     *
     * @return null
     */
    public function closeCursor(): void;

    /**
     * Configure conversion for a column value.
     *
     * @param string $column
     *
     * @return null
     */
    public function convertDate($column): void;

    /**
     * Configure conversion for a column value.
     *
     * @param string $column
     *
     * @return null
     */
    public function convertText($column): void;

    /**
     * Configure conversion for a column value.
     *
     * @param string $column
     *
     * @return null
     */
    public function convertBin2Hex($column): void;
}
