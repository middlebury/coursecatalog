<?php
/**
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Helper\RecentCourses;

/**
 * This interface provides access to recent courses and their terms.
 *
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface RecentCoursesInterface
{
    /**
     * Set the duration considered recent by a DateInterval object.
     *
     * @return null
     */
    public function setRecentInterval(\DateInterval $interval);

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
    public function getAlternatesForCourse(\osid_course_Course $course);

    /**
     * Answer an array of terms from primary or alternate courses given a primary id.
     *
     * @return array
     */
    public function getTermsForCourse(\osid_course_Course $course);
}
