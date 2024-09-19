#!/usr/bin/env php
<?php

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production');
require_once dirname(__FILE__).'/../application/autoload.php';
$config = new Zend_Config_Ini(BASE_PATH.'/archive_config.ini', APPLICATION_ENV);
if (empty($config->catalog->archive_root)) {
    echo 'Invalid configuration: catalog.archive_root must be defined in archive_config.ini';

    return 3;
}
$destRoot = $config->catalog->archive_root;
$verbose = '0';
$cmd = array_shift($argv);
$verbose = array_shift($argv);
if ($verbose && '-v' == $verbose) {
    $verbose = '1';
}
$params = [];
$params['verbose'] = $verbose;
$base = '';
if (!empty($config->catalog->archive->url_base)) {
    $base = '-b '.escapeshellarg($config->catalog->archive->url_base);
}
$return = shell_exec_with_pipes(BASE_PATH.'/bin/zfcli.php '.$base.' -a archive.export_active_jobs -p '.escapeshellarg(http_build_query($params)), $stdout, $stderr);
if (strlen($stdout)) {
    echo $stdout;
}
if ($return) {
    file_put_contents('php://stderr', "Error running command:\n\n\tarchive.export_active_jobs\n");
    file_put_contents('php://stderr', "$stderr\n");

    return $return;
} elseif (strlen($stderr)) {
    file_put_contents('php://stderr', $stderr);
}

// Success
return 0;

function shell_exec_with_pipes($cmd, &$stdout = null, &$stderr = null)
{
    $proc = proc_open($cmd, [
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ], $pipes);
    $stdout = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[2]);

    return proc_close($proc);
}
