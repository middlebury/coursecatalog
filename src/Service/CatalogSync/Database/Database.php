<?php
/**
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service\CatalogSync\Database;

/**
 * This interface defines the requirements of all sync databases.
 *
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface Database
{
    /**
     * Set up connections to our source and destination.
     */
    public function connect(): void;

    /**
     * Disconnect from our databases.
     */
    public function disconnect(): void;
}
