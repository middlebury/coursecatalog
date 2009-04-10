<?php
/**
 * @since 4/9/09
 * @package catalog
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

ini_set('display_errors', 'On');
error_reporting(E_STRICT | E_NOTICE);

define('OSID_BASE', dirname(__FILE__).'/main/lib');
function __autoload($className) {
	require_once(OSID_BASE.'/'.implode('/', explode('_', $className)).'.php');
}

$runtimeManager = new phpkit_OsidRuntimeManager(dirname(__FILE__).'/configuration.plist', OSID_BASE);

$courseManager = $runtimeManager->getManager(osid_OSID::COURSE(), 'banner_course_CourseManager', '3.0.0');

// print_r($courseManager);

print_r($courseManager->getId());
print "\n";
print_r($courseManager->getDisplayName());
print "\n";
print_r($courseManager->getDescription());
print "\n";

// print_r($runtimeManager->getConfiguration());
print "\n";

if (!$courseManager->supportsCourseCatalogLookup()) {
	print "\nNo support for Course Catalog Lookup\n";
	exit;
} 
$lookupSession = $courseManager->getCourseCatalogLookupSession();

var_dump($lookupSession);
print "\nHello";