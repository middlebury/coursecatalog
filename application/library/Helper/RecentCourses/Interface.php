<?php
/**
 * @since 01/07/2016
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This interface provides access to recent courses and their terms.
 *
 * @since 01/07/2016
 *
 * @copyright Copyright &copy; 2016, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface Helper_RecentCourses_Interface
{
    /**
     * Set the duration considered recent by a DateInterval object.
     *
     * @return null
     */
    public function setRecentInterval(DateInterval $interval);

    /**
     * Answer an array of primary courses.
     *
     * @return array
     */
    public function getPrimaryCourses();

    /**
     * Answer an array of alternate courses for a primary course.
     *
     * @return array
     */
    public function getAlternatesForCourse(osid_course_Course $course);

    /**
     * Answer an array of terms from primary or alternate courses given a primary id.
     *
     * @return array
     */
    public function getTermsForCourse(osid_course_Course $course);
}
