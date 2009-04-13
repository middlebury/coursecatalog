<?php
/**
 * @since 4/9/09
 * @package catalog
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

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
$catalogLookupSession = $courseManager->getCourseCatalogLookupSession();
$catalogs = $catalogLookupSession->getCourseCatalogs();
$i = 0;
while ($catalogs->hasNext()) {
	$catalog = $catalogs->getNextCourseCatalog();
// 	var_dump($catalog);
	print "\n\t".$catalog->getId()->getAuthority();
	print "\n\t".$catalog->getId()->getIdentifierNamespace();
	print "\n\t".$catalog->getId()->getIdentifier();
	print "\n\t".$catalog->getDisplayName();
	print "\n\t".$catalog->getDescription();
	print "\n\t".print_r($catalog->getGenusType(), true);
	print "\n\tProperties:";
	$properties = $catalog->getProperties();
	while ($properties->hasNext()) {
		$property = $properties->getNextProperty();
		print "\n\t\t".$property->getDisplayName();
		print "\n\t\t\t".$property->getDisplayLabel();
		print "\n\t\t\t".$property->getDescription();
		print "\n\t\t\t".$property->getValue();
	}
	print "\n\tRecord Types:";
	$recordTypes = $catalog->getRecordTypes();
	while ($recordTypes->hasNext()) {
		$type = $recordTypes->getNextType();
		print "\n\t\t".$type->getAuthority();
		print "\n\t\t\t".$type->getIdentifierNamespace();
		print "\n\t\t\t".$type->getIdentifier();
		print "\n\t\t\t".$type->getDisplayName();
		print "\n\t\t\t".$type->getDisplayLabel();
		print "\n\t\t\t".$type->getDescription();
	}
	print "\n";
	$i++;
}

print "Found $i catalogs\n";

print "Getting the last catalog.\n";
$catalog = $catalogLookupSession->getCourseCatalog($catalog->getId());

var_dump($catalog);
print "\n";