<?php
/**
 * @since 11/16/09
 * @package catalog.library
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * This class hierarchy provides access to recent courses and their terms.
 * 
 * @since 11/16/09
 * @package catalog.library
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class Helper_RecentCourses_Abstract {

	private $groups;
	private $terms;
	protected $alternateType;
	
	/**
	 * Constructor
	 * 
	 * @param osid_course_CourseSearchResults $courses
	 * @return void
	 * @access public
	 * @since 11/16/09
	 */
	public function __construct (osid_course_CourseSearchResults $courses) {
		$this->alternateType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:alternates');
		$this->groups = array();
		$this->terms = array();
		$this->groupAlternates($courses);
	}
	
	/**
	 * Answer an array of primary courses.
	 * 
	 * @return array
	 * @access public
	 * @since 11/16/09
	 */
	public function getPrimaryCourses () {
		$primaries = array();
		foreach ($this->groups as $group) {
			$primaries[] = current($group);
		}
		return $primaries;
	}
	
	/**
	 * Answer an array of alternate courses for a primary course.
	 * 
	 * @param osid_id_Id $courseId
	 * @return array
	 * @access public
	 * @since 11/16/09
	 */
	public function getAlternatesForCourse (osid_course_Course $course) {
		foreach ($this->groups as $group) {
			$primary = current($group);
			if ($primary->getId()->isEqual($course->getId())) {
				$alternates = array();
				foreach ($group as $course) {
					if (!$course->getId()->isEqual($course->getId()))
						$alternates[] = $course;
				}
				return $alternates;
			}
		}
		throw new osid_NotFoundException("No primary matches the id given.");
	}
	
	/**
	 * Answer an array of terms from primary or alternate courses given a primary id.
	 * 
	 * @param osid_course_Course $course
	 * @return array
	 * @access public
	 * @since 11/16/09
	 */
	public function getTermsForCourse (osid_course_Course $course) {
		return $this->getRecentTermsForCourse($course, true);
	}
	
/*********************************************************
 * Internal interface
 *********************************************************/
	
	/**
	 * Answer the terms for a course. These may be all terms or terms taught
	 * 
	 * @param osid_course_Course $course
	 * @return array
	 * @access protected
	 * @since 11/16/09
	 */
	abstract protected function fetchCourseTerms (osid_course_Course $course);
	
/*********************************************************
 * Internal methods
 *********************************************************/

	
	/**
	 * Group alternate courses
	 * 
	 * @param osid_course_CourseSearchResults $courses
	 * @return array A two-dimensional array of course objects array(array($course, $equivCourse), array($course), array($course, $equivCourse));
	 * @access protected
	 * @since 11/13/09
	 */
	private function groupAlternates (osid_course_CourseSearchResults $courses) {
		while ($courses->hasNext()) {
			$groupId = false;
			
			$course = $courses->getNextCourse();
			$courseIdString = AbstractCatalogController::getStringFromOsidId($course->getId());
			
// 			print "\n<h2>Grouping $courseIdString</h2>\n";
// 			print "\n<h3>Current Groups:</h3>\n";
// 			var_dump($this->groups);
			
			if ($course->hasRecordType($this->alternateType)) {
				$record = $course->getCourseRecord($this->alternateType);
				if ($record->hasAlternates()) {
					$groupId = $this->findGroupIdMatchingAlternates($record->getAlternateIds());
				}
			}
			
// 			print "\n<h3>Group Id found:</h3>\n";
// 			var_dump($groupId);
			
			if (!$groupId)
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
	
	/**
	 * return the id of a group that matches or false
	 * 
	 * @param osid_id_IdList $altIds
	 * @return mixed
	 * @access protected
	 * @since 11/13/09
	 */
	private function findGroupIdMatchingAlternates (osid_id_IdList $altIds) {
// 		print "\n<h3>Alternates:</h3>\n";
		while ($altIds->hasNext()) {
			$altId = $altIds->getNextId();
			$altIdString = AbstractCatalogController::getStringFromOsidId($altId);
// 			var_dump($altIdString);
			
			foreach ($this->groups as $groupId => $group) {
				foreach ($group as $otherIdString => $otherCourse) {
					if ($otherIdString == $altIdString) {
						return $groupId;
					}
				}
			}
		}
		return false;
	}
	
	/**
	 * Answer an array of the recent terms for a course.
	 * 
	 * @param osid_course_Course $course
	 * @param optional $includeAlternates
	 * @return array
	 * @access private
	 * @since 11/16/09
	 */
	private function getRecentTermsForCourse (osid_course_Course $course, $includeAlternates = false) {		
		$recentTerms = $this->filterRecentTerms($this->fetchCourseTerms($course));
		
		if ($includeAlternates) {
			if ($course->hasRecordType($this->alternateType)) {
				$record = $course->getCourseRecord($this->alternateType);
				if ($record->hasAlternates()) {
					$alternates = $record->getAlternates();
					while ($alternates->hasNext()) {
						$altTerms = $this->filterRecentTerms($this->fetchCourseTerms($alternates->getNextCourse()));
						
						$recentTerms = array_merge($recentTerms, $altTerms);
					}
				}
			}
		}
		
		$dates = array();
		foreach ($recentTerms as $key => $term) {
			$dates[$key] = $this->DateTime_getTimestamp($term->getEndTime());
		}
		
		array_multisort($dates, SORT_NUMERIC, SORT_ASC, $recentTerms);
		
		return $recentTerms;
	}
	
	/**
	 * Answer the most recent term for a course
	 * 
	 * @param osid_course_Course $course
	 * @return osid_course_Term
	 * @access private
	 * @since 11/16/09
	 */
	private function getMostRecentTermForCourse (osid_course_Course $course) {
		$terms = $this->getRecentTermsForCourse($course);
		
		if (!count($terms))
			throw new osid_NotFoundException('No terms available.');
		
		foreach ($terms as $term) {
			if (!isset($mostRecent))
				$mostRecent = $term;
			else if ($this->DateTime_getTimestamp($term->getEndTime()) > $this->DateTime_getTimestamp($mostRecent->getEndTime()))
				$mostRecent = $term;
		}
		
// 		print "\nRecent term for ".$course->getDisplayName().": ";
// 		var_dump($mostRecent);
		
		return $mostRecent;
	}
	
	/**
	 * Filter out all but the recent terms
	 * 
	 * @param array $allTerms
	 * @return array
	 * @access private
	 * @since 11/16/09
	 */
	private function filterRecentTerms (array $allTerms) {
		// Define a cutoff date after which courses will be included in the feed.
		// Currently set to 4 years. Would be good to have as a configurable time.
		$now = new DateTime;
		$cutOff = $this->DateTime_getTimestamp($now) - (60 * 60 * 24 * 365 * 4);
		$recentTerms = array();
		foreach ($allTerms as $term) {
			if ($this->DateTime_getTimestamp($term->getEndTime()) > $cutOff) {
				$termIdString = AbstractCatalogController::getStringFromOsidId($term->getId());
				$recentTerms[$termIdString] = $term;
			}
		}
		return $recentTerms;
	}
	
	function DateTime_getTimestamp($dt) {
		$dtz_original = $dt -> getTimezone();
		$dtz_utc = new DateTimeZone("UTC");
		$dt -> setTimezone($dtz_utc);
		$year = intval($dt -> format("Y"));
		$month = intval($dt -> format("n"));
		$day = intval($dt -> format("j"));
		$hour = intval($dt -> format("G"));
		$minute = intval($dt -> format("i"));
		$second = intval($dt -> format("s"));
		$dt -> setTimezone($dtz_original);
		return gmmktime($hour,$minute,$second,$month,$day,$year);
	}
	
}

?>