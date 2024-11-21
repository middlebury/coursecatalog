<?php
/**
 * @since 2/22/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service\CatalogSync\Syncer;

/**
 * This interface defines the requirements of Sync classes. Classes SHOULD throw.
 *
 * @since 2/22/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface Syncer
{
    /**
     * Roll back any changes to the destination.
     *
     * @return void
     */
    public function rollback();

    /**
     * Set up connections to our source and destination.
     *
     * @return void
     */
    public function connect();

    /**
     * Take actions before copying data.
     *
     * @return void
     */
    public function preCopy();

    /**
     * Copy data.
     *
     * @return void
     */
    public function copy();

    /**
     * Take actions after copying data.
     *
     * @return void
     */
    public function postCopy();

    /**
     * Update derived data in the destination database.
     *
     * @return void
     */
    public function updateDerived();

    /**
     * Disconnect from our databases.
     *
     * @return void
     */
    public function disconnect();

    /**
     * Answer an array of non-fatal errors that should be mailed.
     *
     * @return array
     *               An array of error messages
     */
    public function getNonFatalErrors();
}
