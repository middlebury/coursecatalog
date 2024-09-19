<?php
/**
 * @since 5/20/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 *  <p>An interface for specifying the ordering of search results. </p>.
 */
class banner_course_CourseOffering_Search_Order extends banner_course_AbstractSearchOrder implements osid_course_CourseOfferingSearchOrder, osid_course_CourseOfferingSearchOrderRecord, middlebury_course_CourseOffering_Search_InstructorsSearchOrderRecord
{
    /**
     * Constructor.
     *
     * @return void
     *
     * @since 5/28/09
     */
    public function __construct()
    {
        parent::__construct();

        $this->addSupportedRecordType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors'));
        $this->addSupportedRecordType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:weekly_schedule'));
    }

    /*********************************************************
     * Methods from osid_OsidSearchOrder
     *********************************************************/

    /**
     *  Specifies a preference for ordering the result set in an ascending
     *  manner.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function ascend()
    {
        if (count($this->terms)) {
            $last = count($this->terms) - 1;
            if (preg_match('/^SSRMEET_[A-Z]{3}_DAY$/', $this->terms[$last]['key'])) {
                $this->terms[$last]['direction'] = 'DESC';
            } else {
                $this->terms[$last]['direction'] = 'ASC';
            }
        }
    }

    /**
     *  Specifies a preference for ordering the result set in a descending
     *  manner.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function descend()
    {
        if (count($this->terms)) {
            $last = count($this->terms) - 1;
            if (preg_match('/^SSRMEET_[A-Z]{3}_DAY$/', $this->terms[$last]['key'])) {
                $this->terms[$last]['direction'] = 'ASC';
            } else {
                $this->terms[$last]['direction'] = 'DESC';
            }
        }
    }

    /**
     *  Specifies a preference for ordering the result set by the display
     *  name.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByDisplayName()
    {
        $this->orderByNumber();
    }

    /**
     *  Specifies a preference for ordering the result set by the genus type.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByGenusType()
    {
        $this->addOrderColumns(['SSBSECT_SCHD_CODE']);
    }

    /*********************************************************
     * Methods from osid_course_CourseOfferingSearchOrderRecord
     *********************************************************/

    /**
     *  Gets the <code> CourseOfferingSearchOrder </code> from which this
     *  record originated.
     *
     * @return object osid_course_CourseOfferingSearchOrder the course
     *                offering search order
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingSearchOrder()
    {
        return $this;
    }

    /*********************************************************
     * Methods from osid_course_CourseOfferingSearchOrder
     *********************************************************/

    /**
     *  Gets the course search order record corresponding to the given course
     *  record <code> Type. </code> Multiple retrievals return the same
     *  underlying object.
     *
     *  @param object osid_type_Type $courseRecordType a course record type
     *
     * @return object osid_course_CourseSearchOrderRecord the course search
     *                order record interface
     *
     * @throws osid_NullArgumentException <code> courseRecordType </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure occurred
     * @throws osid_UnsupportedException <code>
     *                                           hasRecordType(courseRecordType) </code> is <code> false
     *                                           </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseSearchOrderRecord(osid_type_Type $courseRecordType)
    {
        if (!$this->implementsRecordType($courseRecordType)) {
            throw new osid_UnsupportedException('The record type passed is not supported.');
        }

        return $this;
    }

    /**
     *  Specifies a preference for ordering the result set by course title.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByTitle()
    {
        $this->addOrderColumns(['section_title']);
    }

    /**
     *  Specifies a preference for ordering the result set by course number.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByNumber()
    {
        $this->addOrderColumns(['SSBSECT_SUBJ_CODE', 'SSBSECT_CRSE_NUMB', 'SSBSECT_SEQ_NUMB', 'term_display_label', 'SSBSECT_TERM_CODE']);
    }

    /**
     *  Specifies a preference for ordering the result set by course credits.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByCredits()
    {
        $this->addOrderColumns(['SSBSECT_CREDIT_HRS']);
    }

    /**
     *  Specifies a preference for ordering the result set by course
     *  prerequisite information.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByPrereqInfo()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Specifies a preference for ordering the result set by course.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByCourse()
    {
        $this->addOrderColumns(['SSBSECT_SUBJ_CODE', 'SSBSECT_CRSE_NUMB']);
    }

    /**
     *  Tests if a course order interface is available.
     *
     * @return boolean <code> true </code> if a course order interface is
     *                        available, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCourseSearchOrder()
    {
        return false;
    }

    /**
     *  Gets the course order interface.
     *
     * @return object osid_course_CourseSearchOrder the course search order
     *                interface
     *
     * @throws osid_UnimplementedException <code> supportsCourseSearchOrder()
     *                                            </code> is <code> false </code>
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCourseSearchOrder() </code> is <code> true.
     *              </code>
     */
    public function getCourseSearchOrder()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Specifies a preference for ordering the result set by course.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByTerm()
    {
        $this->addOrderColumns(['SSBSECT_TERM_CODE']);
    }

    /**
     *  Tests if a term order interface is available.
     *
     * @return boolean <code> true </code> if a term order interface is
     *                        available, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsTermSearchOrder()
    {
        return false;
    }

    /**
     *  Gets the term order interface.
     *
     * @return object osid_course_TermSearchOrder the term search order
     *                interface
     *
     * @throws osid_UnimplementedException <code> supportsTermSearchOrder()
     *                                            </code> is <code> false </code>
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsTermSearchOrder() </code> is <code> true. </code>
     */
    public function getTermSearchOrder()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Specifies a preference for ordering the result set by location info.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByLocationInfo()
    {
        $this->addOrderColumns(['SSRMEET_BLDG_CODE', 'SSRMEET_ROOM_CODE']);
    }

    /**
     *  Specifies a preference for ordering the result set by location.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByLocation()
    {
        $this->addOrderColumns(['SSRMEET_BLDG_CODE', 'SSRMEET_ROOM_CODE']);
    }

    /**
     *  Tests if a resource order interface is available for the location.
     *
     * @return boolean <code> true </code> if a location order interface is
     *                        available, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsLocationSearchOrder()
    {
        return false;
    }

    /**
     *  Gets the resource order interface for the location.
     *
     * @return object osid_resource_ResourceSearchOrder the location search
     *                order interface
     *
     * @throws osid_UnimplementedException <code>
     *                                            supportsLocationSearchOrder() </code> is <code> false </code>
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsLocationSearchOrder() </code> is <code> true.
     *              </code>
     */
    public function getLocationSearchOrder()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Specifies a preference for ordering the result set by schedule info.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByScheduleInfo()
    {
        $this->addOrderColumns([
            'SSRMEET_START_DATE',
            'SSRMEET_END_DATE',
            'SSRMEET_SUN_DAY',
            'SSRMEET_MON_DAY',
            'SSRMEET_TUE_DAY',
            'SSRMEET_WED_DAY',
            'SSRMEET_THU_DAY',
            'SSRMEET_FRI_DAY',
            'SSRMEET_SAT_DAY',
            'SSRMEET_BEGIN_TIME',
            'SSRMEET_END_TIME']);
    }

    /**
     *  Specifies a preference for ordering the result set by calendar.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByCalendar()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Tests if a resource order interface is available for the calendar.
     *
     * @return boolean <code> true </code> if a calendar order interface is
     *                        available, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsCalendarSearchOrder()
    {
        return false;
    }

    /**
     *  Gets the calendar order interface.
     *
     * @return object osid_calendaring_CalendarSearchOrder the calendar
     *                search order interface
     *
     * @throws osid_UnimplementedException <code>
     *                                            supportsCalendarSearchOrder() </code> is <code> false </code>
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsCalendarSearchOrder() </code> is <code> true.
     *              </code>
     */
    public function getCalendarSearchOrder()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Specifies a preference for ordering the result set by learning
     *  objective.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByLearningObjective()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Tests if a resource order interface is available for the learning
     *  objective.
     *
     * @return boolean <code> true </code> if a learning objective order
     *                        interface is available, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function supportsLearningObjectiveSearchOrder()
    {
        return false;
    }

    /**
     *  Gets the learning objective order interface.
     *
     * @return object osid_learning_ObjectiveSearchOrder the learning
     *                objective search order interface
     *
     * @throws osid_UnimplementedException <code>
     *                                            supportsLearningObjectiveSearchOrder() </code> is <code> false
     *                                            </code>
     *
     *  @compliance optional This method must be implemented if <code>
     *              supportsLearningObjectiveSearchOrder() </code> is <code>
     *              true. </code>
     */
    public function getLearningObjectiveSearchOrder()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Specifies a preference for ordering the result set by url.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByURL()
    {
        throw new osid_UnimplementedException();
    }

    /*********************************************************
     * Methods from middlebury_course_CourseOffering_Search_InstructorsSearchOrderRecord
     *********************************************************/

    /**
     *  Specifies a preference for ordering the result set by the instructor.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByInstructor()
    {
        $this->addOrderColumns(['SYVINST_LAST_NAME', 'SYVINST_FIRST_NAME']);
        $this->addTableJoin('LEFT JOIN SYVINST ON (SYVINST_TERM_CODE = SSBSECT_TERM_CODE AND SYVINST_CRN = SSBSECT_CRN)');
    }

    /*********************************************************
     * Methods from middlebury_course_CourseOffering_Search_InstructorsSearchOrderRecord
     *********************************************************/

    /**
     * Order by meeting on Sunday.
     *
     * @compliance mandatory This method must be implemented.
     */
    public function orderByMeetsSunday()
    {
        $this->addOrderColumns(['SSRMEET_SUN_DAY']);
        $this->ascend();
    }

    /**
     * Order by meeting on Monday.
     *
     * @compliance mandatory This method must be implemented.
     */
    public function orderByMeetsMonday()
    {
        $this->addOrderColumns(['SSRMEET_MON_DAY']);
        $this->ascend();
    }

    /**
     * Order by meeting on Tuesday.
     *
     * @compliance mandatory This method must be implemented.
     */
    public function orderByMeetsTuesday()
    {
        $this->addOrderColumns(['SSRMEET_TUE_DAY']);
        $this->ascend();
    }

    /**
     * Order by meeting on Wednesday.
     *
     * @compliance mandatory This method must be implemented.
     */
    public function orderByMeetsWednesday()
    {
        $this->addOrderColumns(['SSRMEET_WED_DAY']);
        $this->ascend();
    }

    /**
     * Order by meeting on Thursday.
     *
     * @compliance mandatory This method must be implemented.
     */
    public function orderByMeetsThursday()
    {
        $this->addOrderColumns(['SSRMEET_THU_DAY']);
        $this->ascend();
    }

    /**
     * Order by meeting on Friday.
     *
     * @compliance mandatory This method must be implemented.
     */
    public function orderByMeetsFriday()
    {
        $this->addOrderColumns(['SSRMEET_FRI_DAY']);
        $this->ascend();
    }

    /**
     * Order by meeting on Saturday.
     *
     * @compliance mandatory This method must be implemented.
     */
    public function orderByMeetsSaturday()
    {
        $this->addOrderColumns(['SSRMEET_SAT_DAY']);
        $this->ascend();
    }

    /**
     * Order by meeting times.
     *
     * @compliance mandatory This method must be implemented.
     */
    public function orderByMeetingTime()
    {
        $this->addOrderColumns(['SSRMEET_BEGIN_TIME']);
    }
}
