#!/usr/bin/env php
<?php

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', getenv('APPLICATION_ENV') ?: 'production');
require_once __DIR__.'/../application/autoload.php';
$config = new Zend_Config_Ini(BASE_PATH.'/archive_config.ini', APPLICATION_ENV);
if (empty($config->catalog->archive_root)) {
    echo 'Invalid configuration: catalog.archive_root must be defined in archive_config.ini';

    return 3;
}
$destRoot = $config->catalog->archive_root;
$verbose = '0';
$cmd = array_shift($argv);
$jobId = array_shift($argv);
if ('-v' == $jobId) {
    $verbose = '1';
    $jobId = array_shift($argv);
}
if (count($argv)) {
    echo "Usage:
	$cmd [-v] <job>
Where job is an id from the table 'archive_jobs'";
    echo "Options:\n\t-v Verbose output.\n";

    return 1;
}
$params = [];
$params['id'] = $jobId;
$params['verbose'] = $verbose;
$base = '';
if (!empty($config->catalog->archive->url_base)) {
    $base = '-b '.escapeshellarg($config->catalog->archive->url_base);
}
exec(BASE_PATH.'/bin/zfcli.php '.$base.' -a archive.export_single_job -p '.escapeshellarg(http_build_query($params)), $output, $return);
var_dump($output);
if ($return) {
    file_put_contents('php://stderr', "Error running command:\n\n\t$command\n");
    unlink($htmlPath);

    return 2;
}

// Success
return 0;
