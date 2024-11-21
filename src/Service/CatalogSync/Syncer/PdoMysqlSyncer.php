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
use App\Service\CatalogSync\Syncer;

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
        protected PdoDestinationDatabase $destination_db,
        protected array $allowedBlckCodes = [],
    ) {
        parent::__construct($destination_db, $allowedBlckCodes);
    }

    /**
     * Set up connections to our source and destination.
     *
     * @return void
     */
    public function connect()
    {
        parent::connect();

        // Connect to Banner
        $this->source_db->connect();
    }

    /**
     * Disconnect from our databases.
     *
     * @return void
     */
    public function disconnect()
    {
        parent::disconnect();
        $this->source_db->disconnect();
    }

    /**
     * Answer the database we should copy from.
     *
     * @return App\Service\CatalogSync\Database\DestinationDatabase
     */
    protected function getCopySourceDatabase()
    {
        return $this->source_db;
    }
}
