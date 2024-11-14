<?php

namespace App\Service\Osid;

use App\Helper\RecentCourses\RecentCoursesInterface;

/**
 * Service that provides a standardized data array suitble for templating.
 *
 * Used by controllers to process and annotate OSID data-model results with
 * additional data for templating.
 */
class DataLoader
{
    public function __construct(
        private Runtime $osidRuntime,
        private IdMap $osidIdMap,
        private TermHelper $osidTermHelper,
        private TopicHelper $osidTopicHelper,
        private ?string $bannerWebUrl = null,
    ) {
        $this->alternateType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:alternates');
        $this->instructorsType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors');
        $this->locationType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:location');
        $this->namesType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:person_names');
    }

    /**
     * Answer an array of course data suitable for templating.
     *
     * @param \osid_id_Id $id
     *                            The course id
     * @param \osid_id_Id $termId
     *                            A reference term's id if one is being used for filtering
     *                            offerings
     *
     * @return array
     *               An array of data about the course
     */
    public function getCourseDataById(\osid_id_Id $id, ?\osid_id_Id $termId = null)
    {
        $lookupSession = $this->osidRuntime->getCourseManager()->getCourseLookupSession();
        $lookupSession->useFederatedCourseCatalogView();

        if ($termId) {
            $termLookupSession = $this->osidRuntime->getCourseManager()->getTermLookupSession();
            $termLookupSession->useFederatedCourseCatalogView();
            $term = $termLookupSession->getTerm($termId);
        } else {
            $term = null;
        }

        return $this->getCourseData($lookupSession->getCourse($id), $term);
    }

    /**
     * Answer an array of course data suitable for templating.
     *
     * @param \osid_course_Course $course
     *                                    The course
     * @param \osid_course_Term   $term
     *                                    A reference term if one is being used for filtering offerings
     *
     * @return array
     *               An array of data about the course
     */
    public function getCourseData(\osid_course_Course $course, ?\osid_course_Term $term = null)
    {
        $data = [];
        $data['course'] = $course;
        $data['term'] = $term;
        // Optional add-on data that can be populated by other methods.
        $data['is_primary'] = true;
        $data['alternates'] = null;
        $data['offerings'] = [];
        $data['terms'] = [];
        $data['include_alternates_in_title'] = true;
        // Load the topics into our view
        $data = array_merge($data, $this->getTopics($course->getTopics()));
        // Alternate status.
        $data['is_primary'] = true;
        if ($course->hasRecordType($this->alternateType)) {
            $record = $course->getCourseRecord($this->alternateType);
            $data['is_primary'] = $record->isPrimary();
        }

        return $data;
    }

    /**
     * Answer a list of all alternates for a course.
     *
     * @param \osid_course_Course $course
     *                                    The course to get alternates for
     *
     * @return \osid_course_Course[]
     *                               The courses, annotated with additional is_primary values
     */
    public function getAllCourseAlternates(\osid_course_Course $course)
    {
        $data = null;
        if ($course->hasRecordType($this->alternateType)) {
            $record = $course->getCourseRecord($this->alternateType);
            $data = [];
            if ($record->hasAlternates()) {
                $alternates = $record->getAlternates();
                while ($alternates->hasNext()) {
                    $alternate = $alternates->getNextCourse();
                    $alternate->is_primary = false;
                    if ($alternate->hasRecordType($this->alternateType)) {
                        $alternateRecord = $alternate->getCourseRecord($this->alternateType);
                        if ($alternateRecord->isPrimary()) {
                            $alternate->is_primary = true;
                        }
                    }
                    $data[] = $alternate;
                }
            }
        }

        return $data;
    }

    /**
     * Answer a list of alternates for a course filtered to recent ones.
     *
     * @param RecentCoursesInterface $recentCourses
     *                                              The helper used to filter to recent courses
     * @param \osid_course_Course    $course
     *                                              The course to get alternates for
     *
     * @return \osid_course_Course[]
     *                               The courses, annotated with additional is_primary values
     */
    public function getRecentCourseAlternates(RecentCoursesInterface $recentCourses, $course)
    {
        $data = null;
        foreach ($recentCourses->getAlternatesForCourse($course) as $alternate) {
            $alternate->is_primary = false;
            if ($alternate->hasRecordType($this->alternateType)) {
                $alternateRecord = $alternate->getCourseRecord($this->alternateType);
                if ($alternateRecord->isPrimary()) {
                    $alternate->is_primary = true;
                }
            }
            $data[] = $alternate;
        }

        return $data;
    }

    /**
     * Answer an array of course offering data suitable for templating.
     *
     * @param \osid_course_Course $course
     *                                    The course offerings are associated with
     * @param \osid_course_Term   $term
     *                                    A reference term if one is being used for filtering offerings
     *
     * @return array
     *               An array of course offering data
     */
    public function getCourseOfferingsData(\osid_course_Course $course, ?\osid_course_Term $term = null)
    {
        $data = [];
        $offeringLookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSession();
        $offeringLookupSession->useFederatedCourseCatalogView();
        if ($term) {
            $offerings = $offeringLookupSession->getCourseOfferingsByTermForCourse(
                $term->getId(),
                $course->getId(),
            );
        } else {
            $offerings = $offeringLookupSession->getCourseOfferingsForCourse($course->getId());
        }
        while ($offerings->hasNext()) {
            $offering = $offerings->getNextCourseOffering();
            $data[] = $this->getOfferingData($offering);
        }

        return $data;
    }

    /**
     * Answer an array of data that includes the term and past/current/future.
     *
     * @param \osid_course_Term      $currentTerm
     *                                              The current term to compare against
     * @param RecentCoursesInterface $recentCourses
     *                                              The helper used to filter to recent courses
     * @param \osid_course_Course    $course
     *                                              The course to get alternates for
     *
     * @return array
     *               An array of term data. Sub-keys are 'term' (the Term object) and
     *               'type' (current/future/past).
     */
    public function getRecentTermDataForCourse(\osid_course_Term $currentTerm, RecentCoursesInterface $recentCourses, \osid_course_Course $course)
    {
        $now = $this->DateTime_getTimestamp(new \DateTime());
        $currentTermId = $currentTerm->getId();
        $currentEndTime = $this->DateTime_getTimestamp($currentTerm->getEndTime());
        $recentTerms = $recentCourses->getTermsForCourse($course);
        $data = [];
        if (count($recentTerms)) {
            foreach ($recentTerms as $term) {
                if ($term->getId()->isEqual($currentTermId)) {
                    $type = 'current';
                } elseif ($currentEndTime < $this->DateTime_getTimestamp($term->getEndTime())) {
                    $type = 'future';
                } elseif ($now > $this->DateTime_getTimestamp($term->getStartTime()) && $now < $this->DateTime_getTimestamp($term->getEndTime())) {
                    $type = 'current';
                } else {
                    $type = 'past';
                }
                $data[] = [
                    'term' => $term,
                    'type' => $type,
                ];
            }
        }

        return $data;
    }

    /**
     * Answer a timestamp in GMT give a DateTime.
     *
     * @param \DateTime $dt
     *                      The DateTime to get a timestamp for
     *
     * @return int
     *             The GMT timestamp
     */
    public function DateTime_getTimestamp(\DateTime $dt)
    {
        $dtz_original = $dt->getTimezone();
        $dtz_utc = new \DateTimeZone('UTC');
        $dt->setTimezone($dtz_utc);
        $year = (int) $dt->format('Y');
        $month = (int) $dt->format('n');
        $day = (int) $dt->format('j');
        $hour = (int) $dt->format('G');
        $minute = (int) $dt->format('i');
        $second = (int) $dt->format('s');
        $dt->setTimezone($dtz_original);

        return gmmktime($hour, $minute, $second, $month, $day, $year);
    }

    /**
     * Load topics into our view.
     *
     * @param \osid_course_TopicList
     *
     * @return void
     *
     * @since 4/28/09
     */
    public function getTopics(\osid_course_TopicList $topicList)
    {
        $data = [];
        $topics = $this->osidTopicHelper->topicListAsArray($topicList);

        $data['subjectTopics'] = $this->osidTopicHelper->filterTopicsByType($topics, new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.subject'));

        $data['departmentTopics'] = $this->osidTopicHelper->filterTopicsByType($topics, new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.department'));

        $data['divisionTopics'] = $this->osidTopicHelper->filterTopicsByType($topics, new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.division'));

        $data['requirementTopics'] = $this->osidTopicHelper->filterTopicsByType($topics, new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.requirement'));

        $data['levelTopics'] = $this->osidTopicHelper->filterTopicsByType($topics, new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.level'));

        $data['blockTopics'] = $this->osidTopicHelper->filterTopicsByType($topics, new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.block'));

        $data['instructionMethodTopics'] = $this->osidTopicHelper->filterTopicsByType($topics, new \phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.instruction_method'));

        return $data;
    }

    /**
     * Answer an array of course offering data suitable for templating.
     *
     * @param \osid_id_Id $id
     *                        The course offering id
     *
     * @return array
     *               An array of data about the course offering
     */
    public function getOfferingDataById(\osid_id_Id $id)
    {
        $lookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSession();
        $lookupSession->useFederatedCourseCatalogView();

        return $this->getOfferingData($lookupSession->getCourseOffering($id));
    }

    /**
     * Answer an array of course offering data suitable for templating.
     *
     * @param \osid_course_CourseOffering $offering
     *                                              The course
     *
     * @return array
     *               An array of data about the course offering
     */
    public function getOfferingData(\osid_course_CourseOffering $offering)
    {
        $id = $offering->getId();

        // Templates can access basic getter methods on the offering itself.
        $data = ['offering' => $offering];

        // Load the topics into our view
        $data = array_merge(
            $data,
            $this->osidTopicHelper->asTypedArray($offering->getTopics())
        );

        $data['location'] = null;
        if ($offering->hasLocation()) {
            $data['location'] = $offering->getLocation();
        }

        $data['weekly_schedule'] = null;
        $weeklyScheduleType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:weekly_schedule');
        if ($offering->hasRecordType($weeklyScheduleType)) {
            $data['weekly_schedule'] = $offering->getCourseOfferingRecord($weeklyScheduleType);
        }

        // Instructors
        $data['instructors'] = null;
        $data['instructor_names'] = null;
        $instructorsType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors');
        if ($offering->hasRecordType($instructorsType)) {
            $instructorsRecord = $offering->getCourseOfferingRecord($instructorsType);
            $instructors = $instructorsRecord->getInstructors();
            $data['instructors'] = [];
            $data['instructor_names'] = [];
            while ($instructors->hasNext()) {
                $instructor = $instructors->getNextResource();
                $data['instructors'][] = $instructor;
                if ($instructor->hasRecordType($this->namesType)) {
                    $namesRecord = $instructor->getResourceRecord($this->namesType);
                    $instructorData['givename'] = $namesRecord->getGivenName();
                    $instructorData['surname'] = $namesRecord->getSurname();
                    $data['instructor_names'][] = $namesRecord->getSurname();
                } else {
                    $data['instructor_names'][] = $instructor->getDisplayName();
                }
            }
        }

        // Alternates.
        $data['is_primary'] = true;
        $data['alternates'] = null;
        if ($offering->hasRecordType($this->alternateType)) {
            $record = $offering->getCourseOfferingRecord($this->alternateType);
            $data['is_primary'] = $record->isPrimary();
            // Alternates can be fetched if needed with getOfferingAlternates().
        }

        // Availability link. @todo
        $data['availabilityLink'] = null;
        $this->getAvailabilityLink($offering);

        $data['properties'] = [];
        $properties = $offering->getProperties();
        while ($properties->hasNext()) {
            $data['properties'][] = $properties->getNextProperty();
        }

        // Other offerings.
        $lookupSession = $this->osidRuntime->getCourseManager()->getCourseOfferingLookupSession();
        $lookupSession->useFederatedCourseCatalogView();
        $data['offeringsTitle'] = 'All Sections';
        $data['offerings'] = $lookupSession->getCourseOfferingsByTermForCourse(
            $offering->getTermId(),
            $offering->getCourseId()
        );

        return $data;
    }

    /**
     * Answer an array of course offering alternates suitable for templating.
     *
     * @param \osid_course_CourseOffering $offering
     *                                              The course
     *
     * @return array
     *               An array of alternates for the course offering
     */
    public function getOfferingAlternates(\osid_course_CourseOffering $offering)
    {
        $data = null;

        if ($offering->hasRecordType($this->alternateType)) {
            $record = $offering->getCourseOfferingRecord($this->alternateType);
            if ($record->hasAlternates()) {
                $data = [];
                $alternates = $record->getAlternates();
                while ($alternates->hasNext()) {
                    $alternate = $alternates->getNextCourseOffering();
                    $alternate->is_primary = false;
                    if ($alternate->hasRecordType($this->alternateType)) {
                        $alternateRecord = $alternate->getCourseOfferingRecord($this->alternateType);
                        if ($alternateRecord->isPrimary()) {
                            $alternate->is_primary = true;
                        }
                    }
                    $data[] = $alternate;
                }
            }
        }

        return $data;
    }

    /**
     * Answer a safe HTML string for the schedule info passed.
     *
     * @return string
     */
    public function getAvailabilityLink(\osid_course_CourseOffering $courseOffering)
    {
        $bannerIdRecordType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:banner_identifiers');
        if (!empty($this->bannerWebUrl) && $courseOffering->hasRecordType($bannerIdRecordType)) {
            $bannerIdRecord = $courseOffering->getCourseOfferingRecord($bannerIdRecordType);

            return '<a href="'.$this->bannerWebUrl.'?term_in='.$bannerIdRecord->getTermCode().'&crn_in='.$bannerIdRecord->getCourseReferenceNumber()."\" target='_blank' class='availability_link'>View availability, prerequisites, and other requirements.</a>";
        }

        return null;
    }
}
