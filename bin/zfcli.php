#!/usr/bin/env php
<?php

error_reporting(E_ALL|E_STRICT);
ini_set('display_errors',true);
date_default_timezone_set('America/Chicago');

require_once(dirname(__FILE__) . '/../application/autoload.php');

try {
	$opts = new Zend_Console_Getopt(
		array(
			'help|h' => 'Displays usage information.',
			'action|a=s' => 'Action to perform in format of module.controller.action',
			'verbose|v' => 'Verbose messages will be dumped to the default output.',
			'development|d' => 'Enables development mode.',
			'params|p=s' => 'A query string of parameters.',
			'baseurl|b=s' => 'The base URL to use for output.',
		)
	);
	$opts->parse();
} catch (Zend_Console_Getopt_Exception $e) {
	exit($e->getMessage() ."\n\n". $e->getUsageMessage());
}

if(isset($opts->h)) {
	echo $opts->getUsageMessage();
	exit;
}

if(isset($opts->a)) {
	$reqRoute = array_reverse(explode('.',$opts->a));
	@list($action,$controller,$module) = $reqRoute;

	$params = array();
	parse_str($opts->p, $params);

	$request = new Zend_Controller_Request_Simple($action,$controller,$module, $params);
	$front = Zend_Controller_Front::getInstance();

	if(isset($opts->b)) {
		$front->setBaseUrl($opts->b);
	}

	$front->setRequest($request);
	$front->setRouter(new Webf_Controller_Router_Cli());

	$front->setResponse(new Zend_Controller_Response_Cli());

	$front->throwExceptions(true);


	// Our stuff.
	$front->registerPlugin(new CatalogExternalRedirector());

	Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH.'/controllers/helper', 'Helper');
	Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH.'/resources/Catalog/Action/Helper', 'Catalog_Action_Helper');
	Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH.'/resources/Auth/Action/Helper', 'Auth_Action_Helper');
	Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH.'/resources/General/Action/Helper', 'General_Action_Helper');

	// Define application environment
	defined('APPLICATION_ENV')
		|| define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
	$registry = Zend_Registry::getInstance();
	$registry->config = new Zend_Config_Ini(BASE_PATH.'/frontend_config.ini', APPLICATION_ENV);

	foreach ($registry->config->phpSettings as $key => $value) {
		$key = empty($prefix) ? $key : $prefix . $key;
		if (is_scalar($value)) {
			ini_set($key, $value);
		} elseif (is_array($value)) {
			throw new Exception("I don't know how to handle array settings. See Zend_Application::setPhpSettings().");
		}
	}

	$registry->db = Zend_Db::factory($registry->config->resources->db);

	$layoutConfig = new Zend_Config(array(
			'layoutPath' => BASE_PATH.'/application/layouts/scripts',
			'layout'     => 'midd',
		),
		true);
	if (isset($registry->config->resources->layout))
		$layoutConfig->merge($registry->config->resources->layout);

	Zend_Layout::startMvc($layoutConfig);
	Zend_Controller_Front::run(APPLICATION_PATH.'/controllers');
}
