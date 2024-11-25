<?php
/**
 * @since 2/22/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service\CatalogSync\Syncer;

use App\Service\CatalogSync\Database\Destination\PdoDestinationDatabase;
use App\Service\CatalogSync\Database\Source\PdoMysqlSourceDatabase;
use App\Service\CatalogSync\Database\SourceDatabase;

/**
 * This class implements the Banner-to-Catalog sync using Pdo connection
 * on the source side and a MySQL-PDO connection on the destination side.
 *
 * @since 2/22/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class PdoMysqlSyncer extends AbstractSyncer implements Syncer
{
    public function __construct(
        protected PdoMysqlSourceDatabase $source_db,
        PdoDestinationDatabase $destination_db,
        array $allowedBlckCodes = [],
    ) {
        parent::__construct($destination_db, $allowedBlckCodes);
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
