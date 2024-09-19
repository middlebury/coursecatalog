<?php
/**
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This interface defines the requirements of all sync databases.
 *
 * @since 2/23/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface CatalogSync_Database
{
    /**
     * Configure this sync instance.
     *
     * @return void
     */
    public function configure(Zend_Config $config);

    /**
     * Set up connections to our source and destination.
     *
     * @return void
     */
    public function connect();

    /**
     * Disconnect from our databases.
     *
     * @return void
     */
    public function disconnect();
}
