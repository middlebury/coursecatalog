<?php
/**
 * @since 11/16/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * A helper for accessing recent courses for an instructor.
 *
 * @since 11/16/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Helper_RecentCourses_Instructor implements Helper_RecentCourses_Interface
{
    protected $groups = [];
    protected $recentInterval;
    protected $alternatesType;
    protected $courseLookupSession;

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct(osid_course_CourseOfferingSearchResults $offerings, osid_course_CourseLookupSession $courseLookupSession)
    {
        $this->recentInterval = new DateInterval('P4Y');
        $this->alternatesType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:alternates');
        $this->groupCourseOfferings($offerings);
        $this->courseLookupSession = $courseLookupSession;
    }

    /**
     * Set the duration considered recent by a DateInterval object.
     *
     * @return null
     */
    public function setRecentInterval(DateInterval $interval)
    {
        $this->recentInterval = $interval;
    }

    /**
     * Answer an array of primary courses.
     *
     * @return array
     */
    public function getPrimaryCourses()
    {
        $courses = [];
        foreach ($this->groups as $group) {
            $courses[] = $this->courseLookupSession->getCourse($group['primary_course_id']);
        }

        return $courses;
    }

    /**
     * Answer an array of alternate courses for a primary course.
     *
     * @return array
     */
    public function getAlternatesForCourse(osid_course_Course $course)
    {
        $idString = Zend_Controller_Action_HelperBroker::getStaticHelper('OsidId')->toString($course->getId());
        if (!isset($this->groups[$idString])) {
            throw new osid_NotFoundException('The course specified is not one of our primary courses.');
        }
        if (!isset($this->groups[$idString]['alternate_courses'])) {
            $this->groups[$idString]['alternate_courses'] = [];
            foreach ($this->groups[$idString]['alternate_course_ids'] as $id) {
                $this->groups[$idString]['alternate_courses'][] = $this->courseLookupSession->getCourse($id);
            }
        }

        return $this->groups[$idString]['alternate_courses'];
    }

    /**
     * Answer an array of terms from primary or alternate courses given a primary id.
     *
     * @return array
     */
    public function getTermsForCourse(osid_course_Course $course)
    {
        $idString = Zend_Controller_Action_HelperBroker::getStaticHelper('OsidId')->toString($course->getId());
        if (!isset($this->groups[$idString])) {
            throw new osid_NotFoundException('The course specified is not one of our primary courses.');
        }
        ksort($this->groups[$idString]['terms']);

        return $this->groups[$idString]['terms'];
    }

    /*********************************************************
     * Internal methods
     *********************************************************/

    /**
     * Group our course offerings.
     *
     * @return null
     */
    protected function groupCourseOfferings(osid_course_CourseOfferingSearchResults $offerings)
    {
        while ($offerings->hasNext()) {
            $offering = $offerings->getNextCourseOffering();
            if ($this->termIsRecent($offering->getTerm())) {
                $courseId = $offering->getCourseId();
                $courseIdString = Zend_Controller_Action_HelperBroker::getStaticHelper('OsidId')->toString($courseId);
                $termIdString = Zend_Controller_Action_HelperBroker::getStaticHelper('OsidId')->toString($offering->getTermId());

                $groupAlternateCourseIds = [];
                $groupTerms = [$termIdString => $offering->getTerm()];
                // Use the first offering's course as the primary by default. If a different cross-listed offering
                // is the primary one, we'll use that key instead later.
                $groupPrimaryCourseId = $courseId;

                if ($offering->hasRecordType($this->alternatesType)) {
                    $alternatesRecord = $offering->getCourseOfferingRecord($this->alternatesType);
                    $alternates = $alternatesRecord->getAlternates();
                    while ($alternates->hasNext()) {
                        $alternate = $alternates->getNextCourseOffering();
                        $alternateCourseId = $alternate->getCourseId();
                        $alternateCourseIdString = Zend_Controller_Action_HelperBroker::getStaticHelper('OsidId')->toString($alternateCourseId);
                        $groupAlternateCourseIds[$alternateCourseIdString] = $alternateCourseId;
                        // Reset the group key if the primary alternate is later in the search results.
                        if ($alternate->hasRecordType($this->alternatesType)) {
                            $alternateAltRecord = $alternate->getCourseOfferingRecord($this->alternatesType);
                            if ($alternateAltRecord->isPrimary()) {
                                $groupPrimaryCourseId = $alternateCourseId;
                                unset($groupAlternateCourseIds[$alternateCourseIdString]);
                            }
                        }
                    }
                }

                $groupKey = Zend_Controller_Action_HelperBroker::getStaticHelper('OsidId')->toString($groupPrimaryCourseId);
                // Add our group to our result list.
                if (!isset($this->groups[$groupKey])) {
                    $this->groups[$groupKey] = [
                        'primary_course_id' => $groupPrimaryCourseId,
                        'alternate_course_ids' => [],
                        'terms' => [],
                    ];
                }
                $this->groups[$groupKey]['alternate_course_ids'] = array_merge($this->groups[$groupKey]['alternate_course_ids'], $groupAlternateCourseIds);
                $this->groups[$groupKey]['terms'] = array_merge($this->groups[$groupKey]['terms'], $groupTerms);
            }
        }
    }

    /**
     * Filter out all but the recent terms.
     *
     * @return bool
     */
    protected function termIsRecent(osid_course_Term $term)
    {
        // Define a cutoff date after which courses will be included in the feed.
        // Default is 4 years.
        $now = new DateTime();
        $cutOff = $this->DateTime_getTimestamp($now->sub($this->recentInterval));
        $termEnd = $this->DateTime_getTimestamp($term->getEndTime());

        return $termEnd > $cutOff;
    }

    public function DateTime_getTimestamp($dt)
    {
        $dtz_original = $dt->getTimezone();
        $dtz_utc = new DateTimeZone('UTC');
        $dt->setTimezone($dtz_utc);
        $year = intval($dt->format('Y'));
        $month = intval($dt->format('n'));
        $day = intval($dt->format('j'));
        $hour = intval($dt->format('G'));
        $minute = intval($dt->format('i'));
        $second = intval($dt->format('s'));
        $dt->setTimezone($dtz_original);

        return gmmktime($hour, $minute, $second, $month, $day, $year);
    }
}
