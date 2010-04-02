<?php
/**
 * @since 11/16/09
 * @package catalog.library
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * A helper for accessing recent courses in a list.
 * 
 * @since 11/16/09
 * @package catalog.library
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Helper_RecentCourses_Department
	extends Helper_RecentCourses_All
{

	/**
	 * Group alternate courses
	 * 
	 * @param osid_course_CourseSearchResults $courses
	 * @return array A two-dimensional array of course objects array(array($course, $equivCourse), array($course), array($course, $equivCourse));
	 * @access protected
	 * @since 11/13/09
	 */
	protected function groupAlternates (osid_course_CourseSearchResults $courses) {
		while ($courses->hasNext()) {
			
			$course = $courses->getNextCourse();
			$courseIdString = AbstractCatalogController::getStringFromOsidId($course->getId());
			
			$groupId = $courseIdString;

			
// 			print "\n<h3>Using Group Id:</h3>\n";
// 			var_dump($groupId);
			
			if (!isset($this->groups[$groupId]))
				$this->groups[$groupId] = array();
			
			$this->groups[$groupId][$courseIdString] = $course;
		}
		
		// Sort all of the groups by effective date.
		foreach ($this->groups as $groupKey => &$group) {
			$dates = array();
			$names = array();
			foreach ($group as $key => $course) {
				try {
					$term = $this->getMostRecentTermForCourse($course);
					$dates[] = $this->DateTime_getTimestamp($term->getEndTime());
					$names[] = $course->getDisplayName();
				} catch (osid_NotFoundException $e) {
					unset($group[$key]);
				}
			}
// 			var_dump(array_keys($group));
// 			var_dump($dates);
// 			var_dump($names);
			array_multisort($dates, SORT_NUMERIC, SORT_DESC, $names, SORT_ASC, $group);
			
			// Filter out any groups that don't have courses with recent terms.
			if (!count($group))
				unset($this->groups[$groupKey]);
		}
		
		return $this->groups;
	}

}

?>