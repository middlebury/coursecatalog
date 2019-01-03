#!/usr/bin/env php
<?php
$GLOBALS['start_time'] = microtime();
require_once(dirname(__FILE__) . '/../application/autoload.php');

define('DISPLAY_ERROR_BACKTRACE', true);
set_exception_handler(array('harmoni_ErrorHandler', 'handleException'));
try {
	$runtimeManager = new phpkit_AutoloadOsidRuntimeManager(BASE_PATH.'/configuration.plist');
	$courseManager = $runtimeManager->getManager(osid_OSID::COURSE(), 'banner_course_CourseManager', '3.0.0');

	if (!$courseManager->supportsCourseOfferingSearch()) {
		print "\nCourseOfferingSearch is unsupported. Not building indices.\n";
		exit(1);
	}

	$searchSession = $courseManager->getCourseOfferingSearchSession();
	if (!method_exists($searchSession, 'buildIndex')) {
		print "\nCourseOfferingSearch does not support the buildIndex() method. Not building indices.\n";
		exit(2);
	}

	$minMemory = '300M';
	$minBytes = asBytes($minMemory);
	$currentBytes = asBytes(ini_get('memory_limit'));
	if ($currentBytes < $minBytes) {
		ini_set('memory_limit', $minMemory);
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

function asBytes($val) {
	$val = trim($val);
	$num = intval(preg_replace('/^([0-9]+)(.*)$/', '', $val));
	$last = strtolower($val[strlen($val)-1]);
	switch($last) {
		// The 'G' modifier is available since PHP 5.1.0
		case 'g':
			$num *= 1024;
		case 'm':
			$num *= 1024;
		case 'k':
			$num *= 1024;
	}

	return $num;
}
