#!/usr/bin/env php
<?php

$GLOBALS['start_time'] = microtime();
require_once __DIR__.'/../application/autoload.php';

define('DISPLAY_ERROR_BACKTRACE', true);
set_exception_handler(['harmoni_ErrorHandler', 'handleException']);

/*********************************************************
 * Config
 *********************************************************/
$configFile = dirname(__DIR__).'/update_config.ini';
if (!file_exists($configFile)) {
    throw new Exception('Config file "'.$configFile.'" does not exist.');
}
// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', getenv('APPLICATION_ENV') ?: 'production');
$config = new Zend_Config_Ini($configFile, APPLICATION_ENV);

// Set a memory limit for this operation if one is configured.
// This can allow this upgrade script to consume more memory than other operations.
if (!empty($config->memory_limit)) {
    ini_set('memory_limit', $config->memory_limit);
}

// Run the sync.
try {
    $director = new CatalogSync_Director($config);
    $director->sync();
    exit(0);
} catch (Exception $e) {
    fwrite(\STDERR, $e->getMessage()."\n");
    fwrite(\STDERR, $e->getTraceAsString()."\n");
    exit(1);
}
