<?php
/**
 * @since 2/22/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service\CatalogSync\Syncer;

use App\Service\CatalogSync\Database\Destination\PdoDestinationDatabase;
use App\Service\CatalogSync\Database\Source\OciSourceDatabase;
use App\Service\CatalogSync\Database\SourceDatabase;

/**
 * This class implements the Banner-to-Catalog sync using the Banner OCI connection
 * on the source side and a MySQL-PDO connection on the destination side.
 *
 * @since 2/22/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class OciSyncer extends AbstractSyncer implements Syncer
{
    public function __construct(
        protected OciSourceDatabase $source_db,
        PdoDestinationDatabase $destination_db,
        array $allowedBlckCodes = [],
    ) {
        parent::__construct($destination_db, $allowedBlckCodes);

        /*
         * Custom Error handler function to throw exceptions on any PHP
         * Warnings or Errors. This should catch any OCI problems that
         * are not picked up by calls to oci_error().
         */
        set_error_handler([$this, 'exception_error_handler'], \E_ERROR | \E_WARNING);
    }

    /**
     * Custom Error handler function to throw exceptions on any PHP
     * Warnings or Errors. This should catch any OCI problems that
     * are not picked up by calls to oci_error().
     */
    public function exception_error_handler($errno, $errstr, $errfile = null, $errLine = null, $errcontext = null)
    {
        throw new \Exception($errstr, $errno);
    }

    /**
     * Set up connections to our source and destination.
     */
    public function connect(): void
    {
        parent::connect();

        // Connect to Banner
        $this->source_db->connect();
    }

    /**
     * Disconnect from our databases.
     */
    public function disconnect(): void
    {
        parent::disconnect();
        $this->source_db->disconnect();
    }

    /**
     * Answer the database we should copy from.
     *
     * @return App\Service\CatalogSync\Database\SourceDatabase
     */
    protected function getCopySourceDatabase(): SourceDatabase
    {
        return $this->source_db;
    }
}
