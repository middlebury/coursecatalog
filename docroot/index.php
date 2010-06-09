<?php
$GLOBALS['start_time'] = microtime();
require_once(dirname(__FILE__) . '/../application/autoload.php');

define('DISPLAY_ERROR_BACKTRACE', true);
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
	
	$registry = Zend_Registry::getInstance();
	$registry->config = new Zend_Config_Ini(BASE_PATH.'/frontend_config.ini', 'development');
	Zend_Layout::startMvc();
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
}
// Default 
catch (Exception $e) {
	ErrorPrinter::handleException($e, 500);
}