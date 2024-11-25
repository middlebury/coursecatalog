<?php
/**
 * @since 2/22/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service\CatalogSync\Syncer;

use App\Service\CatalogSync\Database\Destination\PdoDestinationDatabase;
use App\Service\CatalogSync\Database\DestinationDatabase;
use App\Service\CatalogSync\Database\Source\OciSourceDatabase;

/**
 * This class implements the Banner-to-Catalog sync using the Banner OCI connection
 * on the source side and a MySQL-PDO connection on the temporary cache side,
 * and mysqldump to copy from the cache to the destination.
 *
 * @since 2/22/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class OciWithCacheSyncer extends OciSyncer implements Syncer
{
    public function __construct(
        OciSourceDatabase $source_db,
        PdoDestinationDatabase $destination_db,
        protected PdoDestinationDatabase $temp_db,
        array $allowedBlckCodes = [],
        private string $mysqlCommand = 'mysql',
        private string $mysqldumpCommand = 'mysqldump',
    ) {
        parent::__construct($source_db, $destination_db, $allowedBlckCodes);
    }

    /**
     * Set up connections to our source and destination.
     */
    public function connect(): void
    {
        parent::connect();
        $this->temp_db->connect();
    }

    /**
     * Answer the database we should copy into during copy.
     *
     * @return App\Service\CatalogSync\Database\DestinationDatabase
     */
    protected function getCopyTargetDatabase(): DestinationDatabase
    {
        return $this->temp_db;
    }

    /**
     * Take actions before copying data.
     */
    public function preCopy(): void
    {
        parent::preCopy();

        // Create the cache tables
        // Copy the primary table definitions into our temporary database
        $command = $this->mysqldumpCommand.' --add-drop-table --single-transaction --no-data '
        .' -h '.escapeshellarg($this->destination_db->getHost())
        .' -u '.escapeshellarg($this->destination_db->getUsername())
        .' -p'.escapeshellarg($this->destination_db->getPassword())
        .' '.escapeshellarg($this->destination_db->getDatabase())
        .' '.implode(' ', $this->getBannerTables())
        .' | '.$this->mysqlCommand
        .' -h '.escapeshellarg($this->temp_db->getHost())
        .' -u '.escapeshellarg($this->temp_db->getUsername())
        .' -p'.escapeshellarg($this->temp_db->getPassword())
        .' -D '.escapeshellarg($this->temp_db->getDatabase());
        $this->output->write('Creating cache tables	...');
        exec($command, $output, $return_var);
        $this->output->write("	done\n");
        if ($return_var) {
            throw new \Exception('Moving from temp database to primary database failed: '.implode("\n", $output));
        }
    }

    /**
     * Take actions after copying data.
     */
    public function postCopy(): void
    {
        // Copy the temporary tables into our primary database
        // If we haven't had any problems updating from banner, import into our primary database
        $command = $this->mysqldumpCommand.' --add-drop-table --single-transaction '
            .' -h '.escapeshellarg($this->temp_db_config->host)
            .' -u '.escapeshellarg($this->temp_db_config->username)
            .' -p'.escapeshellarg($this->temp_db_config->password)
            .' '.escapeshellarg($this->temp_db_config->database)
            .' '.implode(' ', $this->getBannerTables())
            .' | '.$this->mysqlCommand
            .' -h '.escapeshellarg($this->destination_db_config->host)
            .' -u '.escapeshellarg($this->destination_db_config->username)
            .' -p'.escapeshellarg($this->destination_db_config->password)
            .' -D '.escapeshellarg($this->destination_db_config->database);
        $this->output->write('Moving from cache database to primary database 	...');
        exec($command, $output, $return_var);
        $this->output->write("	done\n");
        if ($return_var) {
            throw new \Exception('Moving from temp database to primary database failed: '.implode("\n", $output));
        }
    }

    /**
     * Disconnect from our databases.
     */
    public function disconnect(): void
    {
        parent::disconnect();
        $this->temp_db->disconnect();
    }

    /**
     * Answer a list of the Banner tables.
     */
    protected function getBannerTables(): array
    {
        return [
            'GORINTG',
            'GTVDUNT',
            'GTVINSM',
            'GTVINTP',
            'GTVMTYP',
            'GTVSCHS',
            'SCBCRSE',
            'SCBDESC',
            'SCRATTR',
            'SCREQIV',
            'SCRLEVL',
            'SIRASGN',
            'SOBPTRM',
            'SSBDESC',
            'SSBSECT',
            'SSBXLST',
            'SSRATTR',
            'SSRBLCK',
            'SSRMEET',
            'SSRXLST',
            'STVACYR',
            'STVAPRV',
            'STVASTY',
            'STVATTR',
            'STVBLCK',
            'STVBLDG',
            'STVCAMP',
            'STVCIPC',
            'STVCOLL',
            'STVCOMT',
            'STVCSTA',
            'STVDEPT',
            'STVDIVS',
            'STVFCNT',
            'STVLEVL',
            'STVMEET',
            'STVPTRM',
            'STVPWAV',
            'STVREPS',
            'STVSCHD',
            'STVSUBJ',
            'STVTERM',
            'STVTRMT',
            'SYVINST',
        ];
    }
}
