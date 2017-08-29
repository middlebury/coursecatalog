#!/usr/bin/env php
<?php
// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
require_once(dirname(__FILE__) . '/../application/autoload.php');
$config = new Zend_Config_Ini(BASE_PATH.'/archive_config.ini', APPLICATION_ENV);

$myDir = dirname(__FILE__);

if (empty($config->catalog->archive_root)) {
	print "Invalid configuration: catalog.archive_root must be defined in archive_config.ini";
	return 3;
}
$destRoot = $config->catalog->archive_root;

$verbose = false;
$cmd = array_shift($argv);
$jobName = array_shift($argv);
if ($jobName == '-v') {
	$verbose = true;
	$jobName = array_shift($argv);
}
if (count($argv) || !isset($config->catalog->archive_jobs->$jobName)) {
	print "Usage:
	$cmd [-v] <job>

Where job is one of:
	".implode("\n\t", array_keys($config->catalog->archive_jobs->toArray()))."\n\n";
	print "Options:\n\t-v Verbose output.\n";
	if ($jobName && !isset($config->catalog->archive_jobs->$jobName)) {
		print "Error: Unknown job '$jobName'.\n";
	}
	return 1;
}

$job = $config->catalog->archive_jobs->$jobName;
$jobRoot = $destRoot.'/'.$job->dest_dir;
$htmlRoot = $jobRoot.'/html';

if (!file_exists($htmlRoot)) {
	if (!mkdir($htmlRoot, 0775, true))
		file_put_contents('php://stderr', "Unable to create destination directory '$htmlRoot'.\n");
}

$fileBase = str_replace('/', '-', $job->dest_dir).'_snapshot-'.date('Y-m-d');
$htmlName = $fileBase.'.html';
$htmlPath = $htmlRoot.'/'.$htmlName;

$params = array();
parse_str($job->params, $params);
$params['verbose'] = $verbose;

// Generate the export.
$base = '';
if (!empty($config->catalog->archive->url_base)) {
	$base = '-b '.escapeshellarg($config->catalog->archive->url_base);
}
$command = $myDir.'/zfcli.php '.$base.' -a archive.generate -p '.escapeshellarg(http_build_query($params)).' > '.$htmlPath;
exec($command, $output, $return);
if ($return) {
	file_put_contents('php://stderr', "Error running command:\n\n\t$command\n");
	unlink($htmlPath);
	return 2;
}

// Check to see if the export is different from the previous one.
$exports = explode("\n", trim(shell_exec('ls -1t '.escapeshellarg($htmlRoot))));
array_shift($exports);
if (count($exports)) {
	$diff = trim(shell_exec('diff '.escapeshellarg($htmlPath).' '.escapeshellarg($htmlRoot.'/'.$exports[0])));

	// Delete our current export if it is the same as the last one.
	// This way we only keep versions that contain changes.
	if (!strlen($diff)) {
		unlink($htmlPath);
		file_put_contents('php://stderr', "New version is the same as the last.\n");
		return 0;
	}
}

$linkName = str_replace('/', '-', $job->dest_dir).'_latest.html';
$linkPath = $jobRoot.'/'.$linkName;
if (file_exists($linkPath)) {
	if (!unlink($linkPath)) {
		file_put_contents('php://stderr', "Error deleting latest link: $linkPath\n");
		return 4;
	}
}
if (!symlink('html/'.$htmlName, $linkPath)) {
	file_put_contents('php://stderr', "Error creating latest link: $linkPath\n");
	return 5;
}

// Success
return 0;
