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
 * and mysqldump to copy from the temporary cache to a file export.
 *
 * @since 2/22/16
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class OciToFileSyncer extends OciSyncer implements Syncer
{
    public function __construct(
        OciSourceDatabase $source_db,
        private string $destinationDirectory,
        PdoDestinationDatabase $temp_db,
        array $allowedBlckCodes = [],
        private string $mysqlCommand = 'mysql',
        private string $mysqldumpCommand = 'mysqldump',
        private string $sha256command = 'sha256sum',
    ) {
        parent::__construct($source_db, $temp_db, $allowedBlckCodes);
    }

    /**
     * Take actions before copying data.
     */
    public function preCopy(): void
    {
        parent::preCopy();

        // Recreate the cache tables from our table definition.
        $this->output->write('Creating cache tables	...');
        \harmoni_SQLUtils::runSQLfile(__DIR__.'/../../../../application/library/banner/sql/table_creation.sql', $this->destination_db->getPdo());
        $this->output->write("	done\n");
    }

    /**
     * Take actions after copying data.
     */
    public function postCopy(): void
    {
        if (!file_exists($this->destinationDirectory)) {
            mkdir($this->destinationDirectory);
        }

        if (!is_dir($this->destinationDirectory) || !is_writeable($this->destinationDirectory)) {
            throw new \Exception('Cannot write to ' . $this->destinationDirectory);
        }
        chdir($this->destinationDirectory);

        // Export the tables from our temporary database
        $this->output->write('Dumping banner tables to file 	...');
        $filename = 'catalog-banner-export-' . date('c') . '.sql';
        $command = $this->mysqldumpCommand.' --add-drop-table --single-transaction '
            .' -h '.escapeshellarg($this->destination_db->getHost())
            .' -u '.escapeshellarg($this->destination_db->getUsername())
            .' -p'.escapeshellarg($this->destination_db->getPassword())
            .' '.escapeshellarg($this->destination_db->getDatabase())
            .' '.implode(' ', $this->getBannerTables())
            .' > '.$filename;
        exec($command, $output, $return_var);
        $this->output->write("	done\n");
        if ($return_var) {
            throw new \Exception('Exporting database to file failed: '.implode("\n", $output));
        }

        // Create a sha1 hash
        $this->output->write('Hashing the SQL export 	...');
        $command = $this->sha256command.' '.$filename.' > '.$filename.'.sha256';
        exec($command, $output, $return_var);
        $this->output->write("	done\n");
        if ($return_var) {
            throw new \Exception('Creating the file hash failed: '.implode("\n", $output));
        }
    }

    /**
     * Update derived data in the destination database.
     */
    public function updateDerived(): void {
        // Nothing to do since our temporary database won't have
        // derived tables.
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
            'instructors',
        ];
    }
}
