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

$verbose = '0';
$cmd = array_shift($argv);
$verbose = array_shift($argv);
if ($verbose && $verbose == '-v') {
	$verbose = '1';
}

$params['verbose'] = $verbose;

$base = '';
if (!empty($config->catalog->archive->url_base)) {
	$base = '-b '.escapeshellarg($config->catalog->archive->url_base);
}

exec($myDir.'/zfcli.php '.$base.' -a archive.export_active_jobs -p '.escapeshellarg(http_build_query($params)), $output, $return);

// Success
return 0;
