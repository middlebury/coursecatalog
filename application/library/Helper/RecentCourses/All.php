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
class Helper_RecentCourses_All
	extends Helper_RecentCourses_Abstract
{
	private $termsCache;

	/**
	 * Constructor
	 *
	 * @param osid_course_CourseList $courses
	 * @return void
	 * @access public
	 * @since 11/16/09
	 */
	public function __construct (osid_course_CourseList $courses) {
		$this->termsCache = array();
		parent::__construct($courses);
	}

	/**
	 * Answer the terms for a course. These may be all terms or terms taught
	 *
	 * @param osid_course_Course $course
	 * @return array
	 * @access protected
	 * @since 11/16/09
	 */
	protected function fetchCourseTerms (osid_course_Course $course) {
		$cacheKey = Zend_Controller_Action_HelperBroker::getStaticHelper('OsidId')->toString($course->getId());

		if (!isset($this->termsCache[$cacheKey])) {
			$termsType = new phpkit_type_URNInetType("urn:inet:middlebury.edu:record:terms");
			$allTerms = array();
			if ($course->hasRecordType($termsType)) {
				$termsRecord = $course->getCourseRecord($termsType);
				try {
					$terms = $termsRecord->getTerms();
					while ($terms->hasNext()) {
						$allTerms[] = $terms->getNextTerm();
					}
				} catch (osid_OperationFailedException $e) {
				}
			}
			$this->termsCache[$cacheKey] = $allTerms;
		}
		return $this->termsCache[$cacheKey];
	}
}
