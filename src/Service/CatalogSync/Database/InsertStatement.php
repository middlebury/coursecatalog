<?php
/**
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service\CatalogSync\Database;

/**
 * This interface defines an API for Insert statements.
 *
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface InsertStatement
{
    /**
     * Insert all rows found in the SelectStatement.
     *
     * @param callable|null $rowPrepCallback
     *                                       A callback that will receive a reference to the currently selected row
     *                                       and will be able to modify the row before it is inserted
     *
     * @return null
     */
    public function insertAll(SelectStatement $select, ?callable $rowPrepCallback = null): void;
}
