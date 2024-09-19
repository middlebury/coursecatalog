<?php

/**
 * A helper to export archive jobs.
 *
 * @since 1/19/18
 *
 * @copyright Copyright &copy; 2018, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class helper_ExportJob extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Strategy pattern: call helper as broker method.
     *
     * @return string
     *
     * @since 1/19/18
     */
    public function direct($dest_dir, $config_id, $term, $revision_id, $verbose = '0')
    {
        return $this->exportJob($dest_dir, $config_id, $term, $revision_id, $verbose);
    }

    /**
     * Export an archive job.
     *
     * @param string $dest_dir
     * @param string $config_id
     * @param string $term
     * @param string $verbose
     *
     * @return int
     *
     * @since 1/19/18
     */
    public function exportJob($dest_dir, $config_id, $term, $revision_id, $verbose)
    {
        $config = new Zend_Config_Ini(BASE_PATH.'/archive_config.ini', APPLICATION_ENV);
        $destRoot = $config->catalog->archive_root;

        $jobRoot = $destRoot.'/'.$dest_dir;
        $htmlRoot = $jobRoot.'/html';
        if (empty($config->catalog->archive_tmp)) {
            $tmpRoot = '/tmp';
        } else {
            $tmpRoot = $config->catalog->archive_tmp;
        }

        // Ensure that the job-root directory exists.
        if (!file_exists($destRoot)) {
            echo 'Archive destination directory does not exist: '.$destRoot."\n";
            file_put_contents('php://stderr', "Archive destination directory does not exist: '$destRoot' => ".realpath($destRoot)."\n");

            return 2;
        }

        // Ensure that the destination directory exists.
        if (!file_exists($jobRoot)) {
            if (!mkdir($jobRoot, 0775, true)) {
                echo 'Unable to create directory: '.$jobRoot."\n";
                file_put_contents('php://stderr', "Unable to create destination directory '$jobRoot'.\n");

                return 2;
            }
        }

        // Ensure that the destination html directory exists.
        if (!file_exists($htmlRoot)) {
            if (!mkdir($htmlRoot, 0775, true)) {
                echo 'Unable to create directory: '.$htmlRoot."\n";
                file_put_contents('php://stderr', "Unable to create destination directory '$htmlRoot'.\n");

                return 2;
            }
        }

        $fileBase = str_replace('/', '-', $dest_dir).'_snapshot-'.date('Y-m-d');
        $htmlName = $fileBase.'.html';
        $tmpPath = $tmpRoot.'/'.$htmlName;
        $htmlPath = $htmlRoot.'/'.$htmlName;

        $params = [];
        $params['config_id'] = $config_id;
        $params['term'] = $term;
        $params['revision_id'] = $revision_id;
        $params['verbose'] = $verbose;

        // Generate the export.
        $base = '';
        if (!empty($config->catalog->archive->url_base)) {
            $base = '-b '.escapeshellarg($config->catalog->archive->url_base);
        }
        $command = BASE_PATH.'/bin/zfcli.php '.$base.' -a archive.generate -p '.escapeshellarg(http_build_query($params)).' > '.$tmpPath;

        exec($command, $output, $return);
        if ($return || filesize($tmpPath) < 10) {
            file_put_contents('php://stderr', "Error running command:\n\n\t$command\n\n".implode('', $output)."\n\n");
            unlink($tmpPath);

            return 1;
        }

        // Check to see if the export is different from the previous one.
        $result = shell_exec('ls -1t '.escapeshellarg($htmlRoot));
        if ($result) {
            $exports = explode("\n", trim($result));
        } else {
            $exports = [];
        }

        if (count($exports) > 1) {
            // When doing the diff, Ignore (-I) our the generated_date timestamp line.
            $diff = trim(shell_exec('diff -I generated_date '.escapeshellarg($tmpPath).' '.escapeshellarg($htmlRoot.'/'.$exports[0])));

            // Delete our tmp export if it is the same as the last one.
            // This way we only keep versions that contain changes.
            if (!strlen($diff)) {
                unlink($tmpPath);
                file_put_contents('php://stderr', "New version is the same as the last.\n");

                return 0;
            }
        }

        // On the off chance that we get here, but didn't actually generate a new archive,
        // check that it exists before moving and recreating the symlink.
        if (!file_exists($tmpPath)) {
            file_put_contents('php://stderr', "Trying to update the symlink, but no new HTML export exists. Leaving the old one in place.\n");

            return 4;
        }

        // Move the temp file into the HTML directory.
        // If the file didn't exist it will be created, if we have a new one for
        // the day it will be overwritten.
        rename($tmpPath, $htmlPath);

        // Update the symlink to point at the new file.
        $linkName = str_replace('/', '-', $dest_dir).'_latest.html';
        $linkPath = $jobRoot.'/'.$linkName;
        if (file_exists($linkPath)) {
            if (!unlink($linkPath)) {
                file_put_contents('php://stderr', "Error deleting latest link: $linkPath\n");

                return 2;
            }
        }
        if (!symlink('html/'.$htmlName, $linkPath)) {
            file_put_contents('php://stderr', "Error creating latest link: $linkPath\n");

            return 3;
        }

        return 0;
    }
}
