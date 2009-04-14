<?php
/**
 * @since 4/9/09
 * @package catalog
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

require_once(dirname(__FILE__).'/main/lib/autoload.php');

$runtimeManager = new phpkit_OsidRuntimeManager(dirname(__FILE__).'/configuration.plist', OSID_BASE);

$courseManager = $runtimeManager->getManager(osid_OSID::COURSE(), 'banner_course_CourseManager', '3.0.0');

// print_r($courseManager);

// print_r($courseManager->getId());
// print "\n";
// print_r($courseManager->getDisplayName());
// print "\n";
// print_r($courseManager->getDescription());
// print "\n";
// 
// // print_r($runtimeManager->getConfiguration());
// print "\n";

if (!$courseManager->supportsCourseCatalogLookup()) {
	print "\nNo support for Course Catalog Lookup\n";
	exit;
} 
$catalogLookupSession = $courseManager->getCourseCatalogLookupSession();
// $catalogs = $catalogLookupSession->getCourseCatalogs();
// $i = 0;
// while ($catalogs->hasNext()) {
// 	$catalog = $catalogs->getNextCourseCatalog();
// // 	var_dump($catalog);
// 	printCourseCatalog($catalog);
// 	print "\n";
// 	$i++;
// }

print "Found $i catalogs\n";

$mcugId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:catalog/MCUG');
print "Getting the undergrad catalog.\n";
$catalog = $catalogLookupSession->getCourseCatalog($mcugId);

print "\nCatalog: ".$catalog->getDisplayName();

if (!$courseManager->supportsCourseLookup()) {
	print "\nNo support for Course Lookup\n";
	exit;
} 

$mcugId = new phpkit_id_URNInetId('urn:inet:middlebury.edu:catalog/MCUG');
$courseLookupSession = $courseManager->getCourseLookupSessionForCatalog($mcugId);

$courses = $courseLookupSession->getCourses();
$i = 0;
while ($courses->hasNext() && $i < 20) {
	$course = $courses->getNextCourse();
// 	var_dump($course);
	printCourse($course);
	$i++;
}

print "Found ".($i + $courses->available())." courses\n";

print "\n";

$phys0202Id = new phpkit_id_URNInetId('urn:inet:middlebury.edu:course/PHYS0202');
printCourse($courseLookupSession->getCourse($phys0202Id));
$math0300Id = new phpkit_id_URNInetId('urn:inet:middlebury.edu:course/MATH0300');
printCourse($courseLookupSession->getCourse($math0300Id));

print "\nGetting multiple:";
$courses = $courseLookupSession->getCoursesByIds(new phpkit_id_ArrayIdList(array($phys0202Id, $math0300Id)));
while ($courses->hasNext()) {
	printCourse($courses->getNextCourse());
}


print "\n";


/**
 * Print out a catalog
 * 
 * @param osid_course_CourseCatalog $catalog
 * @return void
 * @access public
 * @since 4/14/09
 */
function printCourseCatalog (osid_course_CourseCatalog $catalog) {
	print "\n----------------------";
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
}

/**
 * Print out a course
 * 
 * @param osid_course_Course $course
 * @return void
 * @access public
 * @since 4/14/09
 */
function printCourse (osid_course_Course $course) {
	print "\n----------------------";
	print "\n\t".$course->getId()->getAuthority();
	print "\n\t".$course->getId()->getIdentifierNamespace();
	print "\n\t".$course->getId()->getIdentifier();
	print "\n\t".$course->getDisplayName();
	print "\n\t".$course->getDescription();
	print "\n\t".$course->getTitle();
	print "\n\t".$course->getNumber();
	print "\n\t".$course->getCredits();
	print "\n\t".$course->getPrereqInfo();
	print "\n\t".print_r($course->getGenusType(), true);
	print "\n\tProperties:";
	$properties = $course->getProperties();
	while ($properties->hasNext()) {
		$property = $properties->getNextProperty();
		print "\n\t\t".$property->getDisplayName();
		print "\n\t\t\t".$property->getDisplayLabel();
		print "\n\t\t\t".$property->getDescription();
		print "\n\t\t\t".$property->getValue();
	}
	print "\n\tRecord Types:";
	$recordTypes = $course->getRecordTypes();
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
}