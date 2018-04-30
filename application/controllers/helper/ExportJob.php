<?php

/**
 * A helper to export archive jobs.
 *
 * @since 1/19/18
 *
 * @copyright Copyright &copy; 2018, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Helper_ExportJob
	extends Zend_Controller_Action_Helper_Abstract
{
	/**
	 * Strategy pattern: call helper as broker method
	 *
	 * @param string $path
	 * @return string
	 * @access public
	 * @since 1/19/18
	 */
	public function direct ($dest_dir, $config_id, $term, $revision_id, $verbose = '0') {
		return $this->exportJob($dest_dir, $config_id, $term, $revision_id, $verbose);
	}

	/**
	 * Export an archive job
	 *
	 * @param string $dest_dir
   * @param string $config_id
   * @param string $term
   * @param string $verbose
	 * @return int
	 * @access public
	 * @since 1/19/18
	 */
	public function exportJob ($dest_dir, $config_id, $term, $revision_id, $verbose) {
    $config = new Zend_Config_Ini(BASE_PATH.'/archive_config.ini', APPLICATION_ENV);
		$destRoot = $config->catalog->archive_root;

    $jobRoot = $destRoot . '/' . $dest_dir;
    $htmlRoot = $jobRoot . '/html';

    if (!file_exists($htmlRoot)) {
      if (!mkdir($htmlRoot, 0775, true))
				echo "Unable to create directory: " . $htmlRoot . "\n";
        file_put_contents('php://stderr', "Unable to create destination directory '$htmlRoot'.\n");
    }

    $fileBase = str_replace('/', '-', $dest_dir) . '_snapshot-' . date('Y-m-d');
    $htmlName = $fileBase . '.html';
    $htmlPath = $htmlRoot . '/' . $htmlName;
    /* If the file we are about to write already exists, that means there has
       already been an export today and we need to handle symlinks differently.*/
    $differentDate = !file_exists($htmlPath);

    $params = array();
    $params['config_id'] = $config_id;
    $params['term'] = $term;
		$params['revision_id'] = $revision_id;
    $params['verbose'] = $verbose;

    // Generate the export.
    $base = '';
    if (!empty($config->catalog->archive->url_base)) {
      $base = '-b '.escapeshellarg($config->catalog->archive->url_base);
    }
    $command = BASE_PATH.'/bin/zfcli.php '.$base.' -a archive.generate -p '.escapeshellarg(http_build_query($params)).' > '.$htmlPath;

    exec($command, $output, $return);
    if ($return) {
			var_dump($return);
			var_dump($command);
      file_put_contents('php://stderr', "Error running command:\n\n\t$command\n");
      unlink($htmlPath);
      return 1;
    }

    // Check to see if the export is different from the previous one.
    $exports = explode("\n", trim(shell_exec('ls -1t '.escapeshellarg($htmlRoot))));
    array_shift($exports);
    if (count($exports)) {
      // When doing the diff, Ignore (-I) our the generated_date timestamp line.
      $diff = trim(shell_exec('diff -I generated_date '.escapeshellarg($htmlPath).' '.escapeshellarg($htmlRoot.'/'.$exports[0])));

      // Delete our current export if it is the same as the last one.
      // This way we only keep versions that contain changes.
      if (!strlen($diff)) {
        if($differentDate) unlink($htmlPath);
        file_put_contents('php://stderr', "New version is the same as the last.\n");
        return 0;
      }
    }

	// On the off chance that we get here, but didn't actually generate a new archive,
	// check that it exists before recreating the symlink.
	if (!file_exists($jobRoot.'/html/'.$htmlName)) {
		file_put_contents('php://stderr', "Trying to update the symlink, but no new HTML export exists. Leaving the old one in place.\n");
		return 4;
	}

    $linkName = str_replace('/', '-', $dest_dir).'_latest.html';
    $linkPath = $jobRoot.'/'.$linkName;
    if (file_exists($linkPath)) {
      if (!unlink($linkPath)) {
				echo "Error deleting latest link: " . $linkPath . "\n";
        file_put_contents('php://stderr', "Error deleting latest link: $linkPath\n");
        return 2;
      }
    }
    if (!symlink('html/'.$htmlName, $linkPath)) {
			echo "Error creating latest link: " . $linkPath . "\n";
      file_put_contents('php://stderr', "Error creating latest link: $linkPath\n");
      return 3;
    }

		return 0;
	}
}
