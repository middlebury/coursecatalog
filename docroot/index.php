<?php
$GLOBALS['start_time'] = microtime();
require_once(dirname(__FILE__) . '/../application/autoload.php');

set_exception_handler(array('harmoni_ErrorHandler', 'handleException'));
try {
	// Start an output buffer so that we can prevent sending of Set-Cookie headers
	// if no session data is stored.
	ob_start();
	require_once('lazy_sessions.php');
	session_start();
	
	
	$front = Zend_Controller_Front::getInstance();
	$front->throwExceptions(true);
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

// Handle certain types of uncaught exceptions specially. In particular,
// Send back HTTP Headers indicating that an error has ocurred to help prevent
// crawlers from continuing to pound invalid urls.
} catch (UnknownActionException $e) {
	ErrorPrinter::handleException($e, 404);
} catch (NullArgumentException $e) {
	ErrorPrinter::handleException($e, 400);
} catch (InvalidArgumentException $e) {
	ErrorPrinter::handleException($e, 400);
} catch (PermissionDeniedException $e) {
	ErrorPrinter::handleException($e, 403);
} catch (UnknownIdException $e) {
	ErrorPrinter::handleException($e, 404);
} catch (osid_NotFoundException $e) {
	ErrorPrinter::handleException($e, 404);
}
// Default 
catch (Exception $e) {
	ErrorPrinter::handleException($e, 500);
}