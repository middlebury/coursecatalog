<?php

/**
 * @since 7/3/2025
 *
 * @copyright Copyright &copy; 2025, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Service\CatalogSync\Syncer;

use App\Service\CatalogSync\Database\Destination\PdoDestinationDatabase;
use App\Service\CatalogSync\Database\SourceDatabase;

/**
 * This class implements the Banner-to-Catalog sync using a SQL file data source.
 *
 * @since 7/3/2025
 *
 * @copyright Copyright &copy; 2025, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class FromFileSyncer extends AbstractSyncer implements Syncer
{
    public function __construct(
        private string $syncFileDirectory,
        PdoDestinationDatabase $destination_db,
        private string $mysqlCommand = 'mysql',
        private string $mysqldumpCommand = 'mysqldump',
        private string $sha256command = 'sha256sum',
    ) {
        parent::__construct($destination_db, []);
    }

    /**
     * Validate that the source database has data.
     */
    protected function validateSource(): void
    {
        if (!is_dir($this->syncFileDirectory) || !is_readable($this->syncFileDirectory)) {
            throw new \Exception('Cannot read from ' . $this->syncFileDirectory);
        }
        // Find the latest SQL file for which a valid hash exists.
        $this->getLatestValidSqlFile($this->syncFileDirectory);
    }

    /**
     * Copy data.
     */
    public function copy(): void {
        // Find the latest SQL file for which a valid hash exists.
        $latestValidFile = $this->getLatestValidSqlFile($this->syncFileDirectory);
        if (file_exists($this->syncFileDirectory.'/latest.txt')
            && basename($latestValidFile) == trim(file_get_contents($this->syncFileDirectory.'/latest.txt'))
        ) {
            throw new \Exception('The latest file has already been imported. Nothing to do.');
        }

        $targetDb = $this->getCopyTargetDatabase();

        // Empty all of the Banner tables that we are going to import.
        $this->output->write('Truncating tables	...');
        foreach ($this->getBannerTables() as $table) {
            $targetDb->truncate($table);
        }
        $this->output->write("	done\n");

        // Import our data from the SQL dump.
        $this->output->write('Importing data	...');

        $command = $this->mysqlCommand
        .' -h '.escapeshellarg($targetDb->getHost())
        .' -u '.escapeshellarg($targetDb->getUsername())
        .' -p'.escapeshellarg($targetDb->getPassword())
        .' -D '.escapeshellarg($targetDb->getDatabase())
        .' < '.escapeshellarg($latestValidFile);
        exec($command, $output, $return_var);
        $this->output->write("	done\n");
        if ($return_var) {
            throw new \Exception('Importing to primary database failed: '.implode("\n", $output));
        }

        // Record our latest file imported so that we don't reimport the same
        // one time and time again.
        file_put_contents($this->syncFileDirectory.'/latest.txt', basename($latestValidFile));
    }

    /**
     * Answer the latest .sql file that matches its .sql.sha256 hash.
     *
     * @param string $directory
     *   The directory to search.
     * @return string
     *   The relative path to the SQL file.
     */
    protected function getLatestValidSqlFile(string $directory): string {
        $latest = null;
        $latestTstamp = null;
        $oldWD = getcwd();
        chdir($directory);
        foreach (scandir('.', SCANDIR_SORT_DESCENDING) as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) == 'sql' && file_exists($file.'.sha256')) {
                if (is_null($latestTstamp) || filemtime($file) > $latestTstamp) {
                    // Check if the file matches its hash.
                    $command = $this->sha256command.' -c '.escapeshellarg($file.'.sha256');
                    exec($command, $output, $return_var);
                    if (!$return_var) {
                        $latest = $file;
                        $latestTstamp = filemtime($file);
                    }
                }
            }
        }
        chdir($oldWD);
        if (is_null($latest)) {
            throw new \Exception('No valid .sql file was found in '.$directory);
        }

        return $directory.'/'.$latest;
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
