<?php
/**
 * @since 2/22/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service\CatalogSync\Syncer;

use Symfony\Component\Console\Output\OutputInterface;

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
     * Set the Output iterface to write status lines to.
     *
     * @param Symfony\Component\Console\Output\OutputInterface $output
     */
    public function setOutput(OutputInterface $output): void;

    /**
     * Roll back any changes to the destination.
     */
    public function rollback(): void;

    /**
     * Set up connections to our source and destination.
     */
    public function connect(): void;

    /**
     * Take actions before copying data.
     */
    public function preCopy(): void;

    /**
     * Copy data.
     */
    public function copy(): void;

    /**
     * Take actions after copying data.
     */
    public function postCopy(): void;

    /**
     * Update derived data in the destination database.
     */
    public function updateDerived(): void;

    /**
     * Disconnect from our databases.
     */
    public function disconnect(): void;

    /**
     * Answer an array of non-fatal errors that should be mailed.
     *
     * @return array
     *               An array of error messages
     */
    public function getNonFatalErrors(): array;
}
