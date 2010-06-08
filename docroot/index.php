<?php
$GLOBALS['start_time'] = microtime();
require_once(dirname(__FILE__) . '/../application/autoload.php');

define('DISPLAY_ERROR_BACKTRACE', true);
set_exception_handler(array('harmoni_ErrorHandler', 'handleException'));
try {

	$front = Zend_Controller_Front::getInstance();
	$front->throwExceptions(true);
	$front->registerPlugin(new CatalogExternalRedirector());
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