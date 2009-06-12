<?php
$GLOBALS['start_time'] = microtime();
require_once(dirname(__FILE__) . '/../application/autoload.php');

define('DISPLAY_ERROR_BACKTRACE', true);
set_exception_handler(array('harmoni_ErrorHandler', 'handleException'));
try {

	$courseManager = AbstractCatalogController::getCourseManager();
	if (!$courseManager->supportsCourseOfferingSearch()) {
		print "\nCourseOfferingSearch is unsupported. Not building indices.\n";
		exit(1);
	}
	
	$searchSession = $courseManager->getCourseOfferingSearchSession();
	if (!method_exists($searchSession, 'buildIndex')) {
		print "\nCourseOfferingSearch does not support the buildIndex() method. Not building indices.\n";
		exit(2);
	}
	
	$searchSession->buildIndex(true);

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