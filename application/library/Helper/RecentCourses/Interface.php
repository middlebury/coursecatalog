<?php
/**
* @since 01/07/2016
 * @package catalog.library
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This interface provides access to recent courses and their terms.
 *
 * @since 01/07/2016
 * @package catalog.library
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface Helper_RecentCourses_Interface {

  /**
	 * Set the duration considered recent by a DateInterval object.
	 *
	 * @param DateInterval $interval
	 * @return null
	 * @access public
	 */
	public function setRecentInterval (DateInterval $interval);

  /**
	 * Answer an array of primary courses.
	 *
	 * @return array
	 * @access public
	 */
	public function getPrimaryCourses ();

  /**
	 * Answer an array of alternate courses for a primary course.
	 *
	 * @param osid_id_Id $courseId
	 * @return array
	 * @access public
	 */
	public function getAlternatesForCourse (osid_course_Course $course);

  /**
	 * Answer an array of terms from primary or alternate courses given a primary id.
	 *
	 * @param osid_course_Course $course
	 * @return array
	 * @access public
	 */
	public function getTermsForCourse (osid_course_Course $course);

}
