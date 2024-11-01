<?php
/**
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App\Helper\RecentCourses;

use App\Service\Osid\IdMap;

/**
 * A helper for accessing recent courses in a list.
 *
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class All extends RecentCoursesAbstract
{
    private $termsCache;

    /**
     * Constructor.
     *
     * @return void
     *
     * @since 11/16/09
     */
    public function __construct(
        IdMap $osidIdMap,
        \osid_course_CourseList $courses,
        string $referenceDate = 'now',
    ) {
        $this->termsCache = [];
        parent::__construct($osidIdMap, $courses, $referenceDate);
    }

    /**
     * Answer the terms for a course. These may be all terms or terms taught.
     *
     * @return array
     *
     * @since 11/16/09
     */
    protected function fetchCourseTerms(\osid_course_Course $course)
    {
        $cacheKey = $this->osidIdMap->toString($course->getId());

        if (!isset($this->termsCache[$cacheKey])) {
            $termsType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:terms');
            $allTerms = [];
            if ($course->hasRecordType($termsType)) {
                $termsRecord = $course->getCourseRecord($termsType);
                try {
                    $terms = $termsRecord->getTerms();
                    while ($terms->hasNext()) {
                        $allTerms[] = $terms->getNextTerm();
                    }
                } catch (\osid_OperationFailedException $e) {
                }
            }
            $this->termsCache[$cacheKey] = $allTerms;
        }

        return $this->termsCache[$cacheKey];
    }
}
