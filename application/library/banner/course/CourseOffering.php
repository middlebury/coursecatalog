<?php
/**
 * @since 4/14/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 *  <p>A <code> CourseOffering </code> represents a learning unit offered
 *  duing a <code> Term. </code> A <code> Course </code> is instantiated at a
 *  time and place through the creation of a <code> CourseOffering. </code>
 *  </p>.
 */
class banner_course_CourseOffering extends phpkit_AbstractOsidObject implements osid_course_CourseOffering, middlebury_course_CourseOffering_InstructorsRecord, middlebury_course_CourseOffering_AlternatesRecord, middlebury_course_CourseOffering_LinkRecord, middlebury_course_CourseOffering_BannerIdentifiersRecord, middlebury_course_CourseOffering_EnrollmentNumbersRecord
{
    /**
     * @var array;
     *
     * @since 4/16/09
     *
     * @static
     */
    private static $requiredFields = [
        'SSBSECT_TERM_CODE',
        'SSBSECT_CRN',
        'SSBSECT_PTRM_CODE',
        'SSBSECT_SUBJ_CODE',
        'SSBSECT_CRSE_NUMB',
        'SSBSECT_SEQ_NUMB',
        'SSBSECT_CAMP_CODE',
        'SSBSECT_CRSE_TITLE',
        'SSBSECT_MAX_ENRL',
        'SSBSECT_ENRL',
        'SSBSECT_SEATS_AVAIL',
        'SSBSECT_LINK_IDENT',

        'SSBDESC_TEXT_NARRATIVE',

        'GTVINSM_CODE',
        'GTVINSM_DESC',

        'term_display_label',
        'STVTERM_START_DATE',

        'STVSCHD_CODE',
        'STVSCHD_DESC',

        'SSRMEET_BLDG_CODE',
        'SSRMEET_ROOM_CODE',
        'SSRMEET_BEGIN_TIME',
        'SSRMEET_END_TIME',
        'SSRMEET_SUN_DAY',
        'SSRMEET_MON_DAY',
        'SSRMEET_TUE_DAY',
        'SSRMEET_WED_DAY',
        'SSRMEET_THU_DAY',
        'SSRMEET_FRI_DAY',
        'SSRMEET_SAT_DAY',
        'SSRMEET_START_DATE',
        'SSRMEET_END_DATE',
        'num_meet',

        'STVBLDG_DESC',

        'STVCAMP_DESC',

        'SCBCRSE_TITLE',
        'SCBCRSE_DEPT_CODE',
        'SCBCRSE_DIVS_CODE',

        'SCBDESC_TEXT_NARRATIVE',

        'SSRXLST_XLST_GROUP',
    ];

    private $row;
    private $session;
    private $raw_description;

    /**
     * Constructor.
     *
     * @return void
     *
     * @since 4/13/09
     */
    public function __construct(array $row, banner_course_CourseOffering_SessionInterface $session)
    {
        $this->instructorsType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors');
        $this->weeklyScheduleType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:weekly_schedule');
        $this->alternatesType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:alternates');
        $this->linkType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:link');
        $this->identifiersType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:banner_identifiers');
        $this->enrollmentNumbersType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:enrollment_numbers');

        parent::__construct();
        $this->checkRow($row);
        $this->row = $row;
        $this->session = $session;
        $this->setId($this->session->getOfferingIdFromTermCodeAndCrn($row['SSBSECT_TERM_CODE'], $row['SSBSECT_CRN']));
        $this->setDisplayName(
            $row['SSBSECT_SUBJ_CODE']
            .$row['SSBSECT_CRSE_NUMB']
            .$row['SSBSECT_SEQ_NUMB']
            .'-'.$row['term_display_label']
            .substr($row['STVTERM_START_DATE'], 2, 2));

        if (null !== $row['SSBDESC_TEXT_NARRATIVE']) {
            $this->setRawDescription($row['SSBDESC_TEXT_NARRATIVE']);
        } elseif (null !== $row['SCBDESC_TEXT_NARRATIVE']) {
            $this->setRawDescription($row['SCBDESC_TEXT_NARRATIVE']);
        } else {
            $this->setDescription('');
        }

        $this->setGenusType(new phpkit_type_Type(
            'urn', 										// namespace
            $this->session->getIdAuthority(), 			// id authority
            'genera:offering.'.$row['STVSCHD_CODE'], 	// identifier
            'Course Offerings', 						// domain
            trim($row['STVSCHD_DESC']), 						// display name
            trim($row['STVSCHD_CODE'])						// display label
        ));

        $this->addRecordType($this->instructorsType);
        $this->addRecordType($this->weeklyScheduleType);
        $this->addRecordType($this->alternatesType);
        $this->addRecordType($this->linkType);
        $this->addRecordType($this->identifiersType);
        $this->addRecordType($this->enrollmentNumbersType);

        $properties = [];
        $properties[] = new phpkit_Property('Course Reference Number', 'CRN', 'An number that uniquely identifies a section within a term.', $row['SSBSECT_CRN']);
        $properties[] = new phpkit_Property('Subject Code', 'Subject Code', 'The subject code of the course this section is an offering of.', $row['SSBSECT_SUBJ_CODE']);
        $properties[] = new phpkit_Property('Course Number', 'Course Number', 'The number of the course this section is an offering of.', $row['SSBSECT_CRSE_NUMB']);
        $properties[] = new phpkit_Property('Section Identifier', 'Section Identifier', 'The section identifier for this section.', $row['SSBSECT_SEQ_NUMB']);
        // $properties[] = new phpkit_Property('Part-of-Term Code', 'Part-of-Term Code', 'An code that identifies the part of the term a section is associated with.', $row['SSBSECT_PTRM_CODE']);
        // 		$properties[] = new phpkit_Property('Term Code', 'Term Code', 'An code that identifies the term a section is associated with.', $row['SSBSECT_TERM_CODE']);
        $this->addProperties($properties, $this->identifiersType);
    }

    /**
     * Gets the description associated with this instance of this OSID
     * object.
     *
     * @return the description
     *
     * @compliance mandatory This method must be implemented.
     *
     * @notes   A description is a string used for describing an object in
     *          human terms and may not have significance in the underlying
     *          system. A provider may wish to initialize the description
     *          based on one or more object attributes and/or treat it as an
     *          auxiliary piece of data that can be modified. A provider may
     *          also wish to translate the description into a specific locale
     *          using the Locale service.
     */
    public function getDescription()
    {
        $description = parent::getDescription();
        if (empty($description) && !empty($this->raw_description)) {
            $this->setDescription(banner_course_Course::convertDescription(trim($this->raw_description)));
        }

        return parent::getDescription();
    }

    /**
     * Set the description.
     *
     * @param string $description
     *
     * @return void
     *
     * @since 10/28/08
     */
    protected function setRawDescription($description)
    {
        $this->raw_description = $description;
        $this->setDescription('');
    }

    /**
     * Check the data row passed for all of our required fields.
     *
     * @return void
     *
     * @since 4/16/09
     */
    protected function checkRow(array $row)
    {
        foreach (self::$requiredFields as $field) {
            if (!array_key_exists($field, $row)) {
                throw new osid_OperationFailedException("Required field, $field not found in data row.");
            }
        }
    }

    /**
     * Answer the rows that contain the meeting info.
     *
     * @return array
     *
     * @since 6/10/09
     */
    protected function getMeetingRows()
    {
        if (!isset($this->meetingRows)) {
            if ((int) $this->row['num_meet'] > 1) {
                $this->meetingRows = $this->session->getCourseOfferingMeetingRows($this->getId());
            } else {
                $this->meetingRows = [];
                $this->meetingRows[] = $this->row;
            }
        }

        return $this->meetingRows;
    }

    /*********************************************************
     * Interface Methods
     *********************************************************/

    /**
     *  Gets the formal title of this course. It may be the same as the
     *  display name or it may be used to more formally label the course. A
     *  display name might be Physics 102 where the title is Introduction to
     *  Electromagentism.
     *
     * @return string the course title
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTitle()
    {
        $title = '';

        if (isset($this->row['SCBCRSE_TITLE']) && !empty($this->row['SCBCRSE_TITLE'])) {
            $title .= $this->row['SCBCRSE_TITLE']."\n";
        }

        // Add a section-specific title if one is set and it differs from the course title.
        if (isset($this->row['SSBSECT_CRSE_TITLE']) && !empty($this->row['SSBSECT_CRSE_TITLE'])
            && trim(mb_strtolower($this->row['SSBSECT_CRSE_TITLE'])) != trim(mb_strtolower($this->row['SCBCRSE_TITLE']))
        ) {
            $title .= $this->row['SSBSECT_CRSE_TITLE'];
        }

        $title = trim($title);

        if (strlen($title)) {
            return $title;
        }

        try {
            return $this->getCourse()->getTitle();
        } catch (osid_NotFoundException $e) {
            return '';
        }
    }

    /**
     *  Gets the course number which is a label generally used to indedx the
     *  course in a catalog, such as T101 or 16.004.
     *
     * @return string the course number
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getNumber()
    {
        return $this->getDisplayName();
    }

    /**
     *  Gets the number of credits in this course.
     *
     * @return float the number of credits
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCredits()
    {
        if (isset($this->row['SSBSECT_CREDIT_HRS']) && !empty($this->row['SSBSECT_CREDIT_HRS'])) {
            return (float) $this->row['SSBSECT_CREDIT_HRS'];
        } else {
            return $this->getCourse()->getCredits();
        }
    }

    /**
     *  Gets the an informational string for the course prerequisites.
     *
     * @return string the prerequisites
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getPrereqInfo()
    {
        return '';
    }

    /**
     *  Gets the canonical course <code> Id </code> associated with this
     *  course offering.
     *
     * @return object osid_id_Id the course <code> Id </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseId()
    {
        return $this->session->getCourseIdFromSubjectAndNumber($this->row['SSBSECT_SUBJ_CODE'], $this->row['SSBSECT_CRSE_NUMB']);
    }

    /**
     *  Gets the canonical course associated with this course offering.
     *
     * @return object osid_course_Course the course
     *
     * @throws osid_OperationFailedException unable to complete request
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourse()
    {
        if (!isset($this->course)) {
            try {
                $this->course = $this->session->getCourseLookupSession()->getCourse($this->getCourseId());
            } catch (osid_NotFoundException $e) {
                throw new osid_OperationFailedException($e->getMessage(), $e->getCode());
            }
        }

        return $this->course;
    }

    private $course;

    /**
     *  Gets the <code> Id </code> of the <code> Term </code> of this
     *  offering.
     *
     * @return object osid_id_Id the <code> Term </code> <code> Id </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTermId()
    {
        $termCode = $this->row['SSBSECT_TERM_CODE'];
        if (!empty($this->row['SSBSECT_PTRM_CODE']) && 1 != $this->row['SSBSECT_PTRM_CODE']) {
            $termCode .= '.'.$this->row['SSBSECT_PTRM_CODE'];
        }

        return $this->getOsidIdFromString($termCode, 'term.');
    }

    /**
     *  Gets the <code> Term </code> of this offering.
     *
     * @return object osid_course_Term the term
     *
     * @throws osid_OperationFailedException unable to complete request
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTerm()
    {
        return $this->session->getTermLookupSession()->getTerm($this->getTermId());
    }

    /**
     *  WARNING: This method was not in the OSID trunk as of 2009-04-27. A
     *  ticket requesting the addition of this method is available at:
     *  http://oki.assembla.com/spaces/osid-dev/tickets/18-osid-course---No-way-to-map-Topics-to-Courses-or-CourseOfferings-
     *  Gets a list of the <code> Id </code> s of the <code> Topic </code> s
     *  this offering is associated with.
     *
     * @return object osid_id_IdList the <code> Topic </code> <code> Id
     *                </code> s
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTopicIds()
    {
        if (!isset($this->topicIds)) {
            $this->topicIds = [];
            if ($this->row['SCBCRSE_DEPT_CODE']) {
                $this->topicIds[] = $this->getOsidIdFromString($this->row['SCBCRSE_DEPT_CODE'], 'topic.department.');
            }
            if ($this->row['SSBSECT_SUBJ_CODE']) {
                $this->topicIds[] = $this->getOsidIdFromString($this->row['SSBSECT_SUBJ_CODE'], 'topic.subject.');
            }
            if ($this->row['SCBCRSE_DIVS_CODE']) {
                $this->topicIds[] = $this->getOsidIdFromString($this->row['SCBCRSE_DIVS_CODE'], 'topic.division.');
            }
            if ($this->row['GTVINSM_CODE']) {
                $this->topicIds[] = $this->getOsidIdFromString($this->row['GTVINSM_CODE'], 'topic.instruction_method.');
            }

            $this->topicIds = array_merge(
                $this->topicIds,
                $this->session->getRequirementTopicIdsForCourseOffering($this->getId()),
                $this->session->getLevelTopicIdsForCourseOffering($this->getId()),
                $this->session->getBlockTopicIdsForCourseOffering($this->getId())
            );
        }

        return new phpkit_id_ArrayIdList($this->topicIds);
    }
    private $topicIds;

    /**
     *  WARNING: This method was not in the OSID trunk as of 2009-04-27. A
     *  ticket requesting the addition of this method is available at:
     *  http://oki.assembla.com/spaces/osid-dev/tickets/18-osid-course---No-way-to-map-Topics-to-Courses-or-CourseOfferings-
     *  Gets the <code> Topic </code> s this offering is associated with.
     *
     * @return object osid_course_TopicList the topics
     *
     * @throws osid_OperationFailedException unable to complete request
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTopics()
    {
        $topicLookup = $this->session->getTopicLookupSession();
        $topicLookup->useComparativeTopicView();

        return $topicLookup->getTopicsByIds($this->getTopicIds());
    }

    /**
     *  Gets a string describing the location of this course offering.
     *
     * @return string location info
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getLocationInfo()
    {
        $campus = $this->row['STVCAMP_DESC'];

        $parts = [];
        foreach ($this->getMeetingRows() as $row) {
            if ($this->row['SSRMEET_ROOM_CODE'] || $row['SSRMEET_ROOM_CODE'] || $row['STVBLDG_DESC']) {
                $parts[] = $row['SSRMEET_BLDG_CODE'].' '.$row['SSRMEET_ROOM_CODE']
            .' ('.$row['STVBLDG_DESC'].')';
            }
        }

        if (count($parts)) {
            return $campus.' Campus: '.implode(', ', $parts);
        } else {
            return $campus;
        }
    }

    /**
     *  Tests if this course offering has an associated location resource.
     *
     * @return boolean <code> true </code> if this course offering has a
     *                        location resource, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function hasLocation()
    {
        return $this->row['SSRMEET_BLDG_CODE'] && $this->row['SSRMEET_ROOM_CODE'];
    }

    /**
     *  Gets the <code> Id </code> of the <code> Resource </code> representing
     *  the location of this course offering.
     *
     * @return object osid_id_Id the location
     *
     * @throws osid_IllegalStateException <code> hasLocation() </code> is
     *                                           <code> false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getLocationId()
    {
        return $this->getOsidIdFromString(
            $this->row['SSRMEET_BLDG_CODE'].'.'.$this->row['SSRMEET_ROOM_CODE'],
            'resource.place.room.');
    }

    /**
     *  Gets the <code> Resource </code> representing the location of this
     *  offering.
     *
     * @return object osid_resource_Resource the location
     *
     * @throws osid_IllegalStateException <code> hasLocation() </code> is
     *                                           <code> false </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getLocation()
    {
        try {
            return $this->session->getResourceLookupSession()->getResource($this->getLocationId());
        } catch (osid_NotFoundException $e) {
            throw new osid_OperationFailedException($e->getMessage());
        }
    }

    /**
     *  Gets a string describing the schedule of this course offering.
     *
     * @return string schedule info
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getScheduleInfo()
    {
        $parts = [];
        $rows = $this->getMeetingRows();
        foreach ($rows as $row) {
            $days = [];
            if ($row['SSRMEET_MON_DAY']) {
                $days[] = 'Monday';
            }
            if ($row['SSRMEET_TUE_DAY']) {
                $days[] = 'Tuesday';
            }
            if ($row['SSRMEET_WED_DAY']) {
                $days[] = 'Wednesday';
            }
            if ($row['SSRMEET_THU_DAY']) {
                $days[] = 'Thursday';
            }
            if ($row['SSRMEET_FRI_DAY']) {
                $days[] = 'Friday';
            }
            if ($row['SSRMEET_SAT_DAY']) {
                $days[] = 'Saturday';
            }
            if ($row['SSRMEET_SUN_DAY']) {
                $days[] = 'Sunday';
            }

            if (!count($days)) {
                continue;
            }

            $info = $this->as12HourTime($row['SSRMEET_BEGIN_TIME'])
                .'-'.$this->as12HourTime($row['SSRMEET_END_TIME'])
                .' on '.implode(', ', $days);
            if (count($rows) > 1) {
                $info .= ' at '.$row['SSRMEET_BLDG_CODE'].' '.$row['SSRMEET_ROOM_CODE'];
            }

            $start = date('M j, Y', strtotime($row['SSRMEET_START_DATE']));
            $end = date('M j, Y', strtotime($row['SSRMEET_END_DATE']));
            $info .= ' ('.$start.' to '.$end.')';

            $parts[] = $info;
        }

        if (count($parts)) {
            return implode("\n", $parts);
        }
        // If no schedule is set, use a standardized message.
        else {
            // Flexible Online.
            if ('FON' == $this->row['GTVINSM_CODE']) {
                return 'Flexible Online';
            }
            // MIIS Institute Online asynchronous.
            if ('ONASY' == $this->row['GTVINSM_CODE']) {
                return '';
            }
            // Cross-listed sections that don't have schedules entered.
            if ($this->hasAlternates() && !$this->isPrimary()) {
                return 'Unscheduled cross-list, see primary section.';
            }

            // Default case.
            return 'TBD';
        }
    }

    /**
     * Convert a 24-hour time into a 12-hour time.
     *
     * @param string $time
     *
     * @return string
     *
     * @since 4/16/09
     */
    protected function as12HourTime($time)
    {
        $parts = date_parse_from_format('Hi', $time);

        return date('g:ia', mktime($parts['hour'], $parts['minute']));
    }

    /**
     *  Tests if this course offering has an associated calendar.
     *
     * @return boolean <code> true </code> if this course offering has a
     *                        calendar, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function hasCalendar()
    {
        return false;
    }

    /**
     *  Gets the calendar for this course offering. Schedule items are
     *  associated with this calendar through the available Scheduling
     *  manager.
     *
     * @return object osid_id_Id <code> Id </code> of a <code> </code>
     *                calendar
     *
     * @throws osid_IllegalStateException <code> hasCalendar() </code> is
     *                                           <code> false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCalendarId()
    {
        throw new osid_IllegalStateException('This version of the OSID does not support Learning Objectives');

        return $this->getOsidIdFromString(
            $this->row['SSBSECT_TERM_CODE'].'.'.$this->row['SSBSECT_CRN'],
            'CourseSchedule.');
    }

    /**
     *  Gets the calendar for this course offering, which may be a root in a
     *  calendar hierarchy.
     *
     * @return object osid_calendaring_Calendar a calendar
     *
     * @throws osid_IllegalStateException <code> hasCalendar() </code> is
     *                                           <code> false </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCalendar()
    {
        throw new osid_IllegalStateException('This version of the OSID does not support Calendering');

        return $this->session->getCalendarLookupSession()->getResource($this->getCalendarId());
    }

    /**
     *  Tests if this course offering has an associated learning objective.
     *
     * @return boolean <code> true </code> if this course offering has a
     *                        learning objective, <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function hasLearningObjective()
    {
        return false;
    }

    /**
     *  Gets the root node of a learning objective map for this course
     *  offering.
     *
     * @return object osid_id_Id <code> Id </code> of a <code> l </code>
     *                earning <code> Objective </code>
     *
     * @throws osid_IllegalStateException <code> hasLearningObjective()
     *                                           </code> is <code> false </code>
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function getLearningObjectiveId()
    {
        throw new osid_IllegalStateException('This version of the OSID does not support Learning Objectives');
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the root node of a learning objective map for this course
     *  offering.
     *
     * @return object osid_learning_Objective the returned learning <code>
     *                Objective </code>
     *
     * @throws osid_IllegalStateException <code> hasLearningObjective()
     *                                           </code> is <code> false </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getLearningObjective()
    {
        throw new osid_IllegalStateException('This version of the OSID does not support Learning Objectives');
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets an external resource, such as a class web site, associated with
     *  this offering.
     *
     * @return string a URL string
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getURL()
    {
        return '';
    }

    /**
     *  Gets the record corresponding to the given <code> CourseOffering
     *  </code> record <code> Type. </code> This method must be used to
     *  retrieve an object implementing the requested record interface along
     *  with all of its ancestor interfaces. The <code>
     *  courseOfferingRecordType </code> may be the <code> Type </code>
     *  returned in <code> getRecordTypes() </code> or any of its parents in a
     *  <code> Type </code> hierarchy where <code>
     *  hasRecordType(courseOfferingRecordType) </code> is <code> true </code>
     *  .
     *
     *  @param object osid_type_Type $courseOfferingRecordType the type of
     *          course offering record to retrieve
     *
     * @return object osid_course_CourseOfferingRecord the course offering
     *                record
     *
     * @throws osid_NullArgumentException <code> courseOfferingRecordType
     *                                           </code> is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure occurred
     * @throws osid_UnsupportedException <code>
     *                                           hasRecordType(courseOfferingRecordType) </code> is <code>
     *                                           false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingRecord(osid_type_Type $courseOfferingRecordType)
    {
        if ($this->hasRecordType($courseOfferingRecordType)) {
            return $this;
        }

        throw new osid_UnsupportedException('Record type is not supported.');
    }

    /**
     * Answer an Id object from a string database Id.
     *
     * @param string $databaseId
     * @param string optional $prefix
     *
     * @return osid_id_Id
     *
     * @since 4/10/09
     */
    protected function getOsidIdFromString($databaseId, $prefix = null)
    {
        if (null === $prefix) {
            $prefix = $this->idPrefix;
        }

        return new phpkit_id_Id($this->session->getIdAuthority(), 'urn', $prefix.$databaseId);
    }

    /*********************************************************
     * Record support
     *********************************************************/
    /**
     *  Tests if the given type is implemented by this record. Other types
     *  than that directly indicated by <code> getType() </code> may be
     *  supported through an inheritance scheme where the given type specifies
     *  a record that is a parent interface of the interface specified by
     *  <code> getType(). </code>.
     *
     *  @param object osid_type_Type $recordType a type
     *
     * @return boolean <code> true </code> if the given record <code> Type
     *                        </code> is implemented by this record, <code> false </code>
     *                        otherwise
     *
     * @throws osid_NullArgumentException <code> recordType </code> is <code>
     *                                           null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function implementsRecordType(osid_type_Type $recordType)
    {
        return $this->hasRecordType($recordType);
    }

    /**
     *  Gets the <code> CourseOffering </code> from which this record
     *  originated.
     *
     * @return object osid_course_CourseOffering the course offering
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOffering()
    {
        return $this;
    }

    /*********************************************************
     * InstructorsRecord support
     *********************************************************/

    /**
     *  Gets the Ids of the instructors associated with this course offering.
     *
     * @return object osid_id_IdList the list of instructor ids
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     */
    public function getInstructorIds()
    {
        return $this->session->getInstructorIdsForOffering($this->getId());
    }

    /**
     *  Gets the <code> Resources </code> representing the instructors associated
     *  with this course offering.
     *
     * @return object osid_resource_ResourceList the list of instructors
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     */
    public function getInstructors()
    {
        return $this->session->getInstructorsForOffering($this->getId());
    }

    /*********************************************************
     * LinkRecord support
     *********************************************************/

    /**
     * Answer the link-set id for the offering.
     *
     * The offerings of a course in a term will be grouped into one or more link sets
     * (set 1, set 2, set 3, etc).
     * Each offering also has a link type (such as lecture, discussion, lab, etc).
     *
     * When registering for a Course that has multiple Offerings (such as lecture + lab or
     * lectures at different times), students must choose a link set and then one offering
     * of each type within that set.
     *
     * @return osid_id_Id
     *
     * @since 8/3/10
     */
    public function getLinkSetId()
    {
        if (null === $this->row['SSBSECT_LINK_IDENT']) {
            $linkId = 'NULL';
        } else {
            // Link ids are of the form L1, L2, D1, D2.
            // The set id is the second charactor.
            $linkId = substr($this->row['SSBSECT_LINK_IDENT'], 1, 1);
        }

        return $this->getOsidIdFromString($linkId, 'link_set.');
    }

    /**
     * Answer the link-type id for the offering.
     *
     * The offerings of a course in a term will be grouped into one or more link sets
     * (set 1, set 2, set 3, etc).
     * Each offering also has a link type (such as lecture, discussion, lab, etc).
     *
     * When registering for a Course that has multiple Offerings (such as lecture + lab or
     * lectures at different times), students must choose a link set and then one offering
     * of each type within that set.
     *
     * @return osid_id_Id
     *
     * @since 8/3/10
     */
    public function getLinkTypeId()
    {
        if (null === $this->row['SSBSECT_LINK_IDENT']) {
            $linkId = 'NULL';
        } else {
            // Link ids are of the form L1, L2, D1, D2.
            // The type id is the first charactor.
            $linkId = substr($this->row['SSBSECT_LINK_IDENT'], 0, 1);
        }

        return $this->getOsidIdFromString($linkId, 'link_type.');
    }

    /*********************************************************
     * AlternatesRecord support
     *********************************************************/
    /**
     * Tests if this course offering has any alternate course offerings.
     *
     * @return boolean <code> true </code> if this course offering has any
     *                        alternates, <code> false </code> otherwise
     *
     * @compliance mandatory This method must be implemented.
     */
    public function hasAlternates()
    {
        return !empty($this->row['SSRXLST_XLST_GROUP']);
    }

    /**
     *  Gets the Ids of any alternate course offerings.
     *
     * @return object osid_id_IdList the list of alternate ids
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     */
    public function getAlternateIds()
    {
        if (!$this->hasAlternates()) {
            return new phpkit_EmptyList('osid_id_IdList');
        }

        return $this->session->getAlternateIdsForOffering($this->getId());
    }

    /**
     *  Gets the alternate <code> CourseOfferings </code>.
     *
     * @return object osid_course_CourseOfferingList The list of alternates
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     */
    public function getAlternates()
    {
        // Get a lookup session for all catalogs in case cross-lists span catalogs.
        $lookupSession = $this->session->getManager()->getCourseOfferingLookupSession();
        $lookupSession->useFederatedCourseCatalogView();
        try {
            return $lookupSession->getCourseOfferingsByIds($this->getAlternateIds());
        } catch (osid_NotFoundException $e) {
            throw new osid_OperationFailedException('Could not load alternates for '.$this->getId()->getIdentifier(), 404, $e);
        }
    }

    /**
     * Answer <code> true </code> if this course is the primary version in a group of
     * alternates.
     *
     * @return bool
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     */
    public function isPrimary()
    {
        return (int) $this->row['SSBSECT_MAX_ENRL'] > 0;
    }

    /*********************************************************
     * BannerIdentifiersRecord support
     *********************************************************/

    /**
     *  Answers the term-code of the Course Offering.
     *
     * @return string the term-code
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     */
    public function getTermCode()
    {
        return $this->row['SSBSECT_TERM_CODE'];
    }

    /**
     *  Answers the Course Reference Number (CRN) of a course.
     *
     * @return string The Course Reference Number (CRN)
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     */
    public function getCourseReferenceNumber()
    {
        return $this->row['SSBSECT_CRN'];
    }

    /**
     *  Answers the Sequence Number of a course section.
     *
     * @return string the sequence number
     *
     *  @compliance mandatory This method must be implemented.
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     */
    public function getSequenceNumber()
    {
        return $this->row['SSBSECT_SEQ_NUMB'];
    }

    /*********************************************************
     * WeeklyScheduleRecord support
     *********************************************************/

    /**
     * Answer true if this CourseOffering meets on Sunday.
     *
     * @return bool
     *
     * @compliance mandatory This method must be implemented.
     */
    public function meetsOnSunday()
    {
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_SUN_DAY'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Answer time the meeting starts on Sunday.
     *
     * @return array an array of start-times whose order matches those returned by getSundayEndTimes()
     *               Times are  in seconds from midnight Sunday morning
     *
     * @compliance mandatory This method must be implemented.
     *
     * @throws osid_IllegalStateException <code>meetsOnSunday()</code> is <code>false</code>
     *
     * @since 6/10/09
     */
    public function getSundayStartTimes()
    {
        $times = [];
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_SUN_DAY'])) {
                $times[] = $this->asSeconds($row['SSRMEET_BEGIN_TIME']);
            }
        }

        return $times;
    }

    /**
     * Answer time the meeting ends on Sunday.
     *
     * @return array an array of end-times whose order matches those returned by getSundayStartTimes()
     *               Times are  in seconds from midnight Sunday morning
     *
     * @compliance mandatory This method must be implemented.
     *
     * @throws osid_IllegalStateException <code>meetsOnSunday()</code> is <code>false</code>
     *
     * @since 6/10/09
     */
    public function getSundayEndTimes()
    {
        $times = [];
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_SUN_DAY'])) {
                $times[] = $this->asSeconds($row['SSRMEET_END_TIME']);
            }
        }

        return $times;
    }

    /**
     * Answer true if this CourseOffering meets on Monday.
     *
     * @return bool
     *
     * @compliance mandatory This method must be implemented.
     */
    public function meetsOnMonday()
    {
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_MON_DAY'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Answer time the meeting starts on Monday.
     *
     * @return array an array of start-times whose order matches those returned by getMondayEndTimes()
     *               Times are  in seconds from midnight Monday morning
     *
     * @compliance mandatory This method must be implemented.
     *
     * @throws osid_IllegalStateException <code>meetsOnMonday()</code> is <code>false</code>
     *
     * @since 6/10/09
     */
    public function getMondayStartTimes()
    {
        $times = [];
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_MON_DAY'])) {
                $times[] = $this->asSeconds($row['SSRMEET_BEGIN_TIME']);
            }
        }

        return $times;
    }

    /**
     * Answer time the meeting ends on Monday.
     *
     * @return array an array of end-times whose order matches those returned by getMondayStartTimes()
     *               Times are  in seconds from midnight Monday morning
     *
     * @compliance mandatory This method must be implemented.
     *
     * @throws osid_IllegalStateException <code>meetsOnMonday()</code> is <code>false</code>
     *
     * @since 6/10/09
     */
    public function getMondayEndTimes()
    {
        $times = [];
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_MON_DAY'])) {
                $times[] = $this->asSeconds($row['SSRMEET_END_TIME']);
            }
        }

        return $times;
    }

    /**
     * Answer true if this CourseOffering meets on Tuesday.
     *
     * @return bool
     *
     * @compliance mandatory This method must be implemented.
     */
    public function meetsOnTuesday()
    {
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_TUE_DAY'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Answer time the meeting starts on Tuesday.
     *
     * @return array an array of start-times whose order matches those returned by getTuesdayEndTimes()
     *               Times are  in seconds from midnight Tuesday morning
     *
     * @compliance mandatory This method must be implemented.
     *
     * @throws osid_IllegalStateException <code>meetsOnTuesday()</code> is <code>false</code>
     *
     * @since 6/10/09
     */
    public function getTuesdayStartTimes()
    {
        $times = [];
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_TUE_DAY'])) {
                $times[] = $this->asSeconds($row['SSRMEET_BEGIN_TIME']);
            }
        }

        return $times;
    }

    /**
     * Answer time the meeting ends on Tuesday.
     *
     * @return array an array of end-times whose order matches those returned by getTuesdayStartTimes()
     *               Times are  in seconds from midnight Tuesday morning
     *
     * @compliance mandatory This method must be implemented.
     *
     * @throws osid_IllegalStateException <code>meetsOnTuesday()</code> is <code>false</code>
     *
     * @since 6/10/09
     */
    public function getTuesdayEndTimes()
    {
        $times = [];
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_TUE_DAY'])) {
                $times[] = $this->asSeconds($row['SSRMEET_END_TIME']);
            }
        }

        return $times;
    }

    /**
     * Answer true if this CourseOffering meets on Wednesday.
     *
     * @return bool
     *
     * @compliance mandatory This method must be implemented.
     */
    public function meetsOnWednesday()
    {
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_WED_DAY'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Answer time the meeting starts on Wednesday.
     *
     * @return array an array of start-times whose order matches those returned by getWednesdayEndTimes()
     *               Times are  in seconds from midnight Wednesday morning
     *
     * @compliance mandatory This method must be implemented.
     *
     * @throws osid_IllegalStateException <code>meetsOnWednesday()</code> is <code>false</code>
     *
     * @since 6/10/09
     */
    public function getWednesdayStartTimes()
    {
        $times = [];
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_WED_DAY'])) {
                $times[] = $this->asSeconds($row['SSRMEET_BEGIN_TIME']);
            }
        }

        return $times;
    }

    /**
     * Answer time the meeting ends on Wednesday.
     *
     * @return array an array of end-times whose order matches those returned by getWednesdayStartTimes()
     *               Times are  in seconds from midnight Wednesday morning
     *
     * @compliance mandatory This method must be implemented.
     *
     * @throws osid_IllegalStateException <code>meetsOnWednesday()</code> is <code>false</code>
     *
     * @since 6/10/09
     */
    public function getWednesdayEndTimes()
    {
        $times = [];
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_WED_DAY'])) {
                $times[] = $this->asSeconds($row['SSRMEET_END_TIME']);
            }
        }

        return $times;
    }

    /**
     * Answer true if this CourseOffering meets on Thursday.
     *
     * @return bool
     *
     * @compliance mandatory This method must be implemented.
     */
    public function meetsOnThursday()
    {
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_THU_DAY'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Answer time the meeting starts on Thursday.
     *
     * @return array an array of start-times whose order matches those returned by getThursdayEndTimes()
     *               Times are  in seconds from midnight Thursday morning
     *
     * @compliance mandatory This method must be implemented.
     *
     * @throws osid_IllegalStateException <code>meetsOnThursday()</code> is <code>false</code>
     *
     * @since 6/10/09
     */
    public function getThursdayStartTimes()
    {
        $times = [];
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_THU_DAY'])) {
                $times[] = $this->asSeconds($row['SSRMEET_BEGIN_TIME']);
            }
        }

        return $times;
    }

    /**
     * Answer time the meeting ends on Thursday.
     *
     * @return array an array of end-times whose order matches those returned by getThursdayStartTimes()
     *               Times are  in seconds from midnight Thursday morning
     *
     * @compliance mandatory This method must be implemented.
     *
     * @throws osid_IllegalStateException <code>meetsOnThursday()</code> is <code>false</code>
     *
     * @since 6/10/09
     */
    public function getThursdayEndTimes()
    {
        $times = [];
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_THU_DAY'])) {
                $times[] = $this->asSeconds($row['SSRMEET_END_TIME']);
            }
        }

        return $times;
    }

    /**
     * Answer true if this CourseOffering meets on Friday.
     *
     * @return bool
     *
     * @compliance mandatory This method must be implemented.
     */
    public function meetsOnFriday()
    {
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_FRI_DAY'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Answer time the meeting starts on Friday.
     *
     * @return array an array of start-times whose order matches those returned by getFridayEndTimes()
     *               Times are  in seconds from midnight Friday morning
     *
     * @compliance mandatory This method must be implemented.
     *
     * @throws osid_IllegalStateException <code>meetsOnFriday()</code> is <code>false</code>
     *
     * @since 6/10/09
     */
    public function getFridayStartTimes()
    {
        $times = [];
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_FRI_DAY'])) {
                $times[] = $this->asSeconds($row['SSRMEET_BEGIN_TIME']);
            }
        }

        return $times;
    }

    /**
     * Answer time the meeting ends on Friday.
     *
     * @return array an array of end-times whose order matches those returned by getFridayStartTimes()
     *               Times are  in seconds from midnight Friday morning
     *
     * @compliance mandatory This method must be implemented.
     *
     * @throws osid_IllegalStateException <code>meetsOnFriday()</code> is <code>false</code>
     *
     * @since 6/10/09
     */
    public function getFridayEndTimes()
    {
        $times = [];
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_FRI_DAY'])) {
                $times[] = $this->asSeconds($row['SSRMEET_END_TIME']);
            }
        }

        return $times;
    }

    /**
     * Answer true if this CourseOffering meets on Saturday.
     *
     * @return bool
     *
     * @compliance mandatory This method must be implemented.
     */
    public function meetsOnSaturday()
    {
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_SAT_DAY'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Answer time the meeting starts on Saturday.
     *
     * @return array an array of start-times whose order matches those returned by getSaturdayEndTimes()
     *               Times are  in seconds from midnight Saturday morning
     *
     * @compliance mandatory This method must be implemented.
     *
     * @throws osid_IllegalStateException <code>meetsOnSaturday()</code> is <code>false</code>
     *
     * @since 6/10/09
     */
    public function getSaturdayStartTimes()
    {
        $times = [];
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_SAT_DAY'])) {
                $times[] = $this->asSeconds($row['SSRMEET_BEGIN_TIME']);
            }
        }

        return $times;
    }

    /**
     * Answer time the meeting ends on Saturday.
     *
     * @return array an array of end-times whose order matches those returned by getSaturdayStartTimes()
     *               Times are  in seconds from midnight Saturday morning
     *
     * @compliance mandatory This method must be implemented.
     *
     * @throws osid_IllegalStateException <code>meetsOnSaturday()</code> is <code>false</code>
     *
     * @since 6/10/09
     */
    public function getSaturdayEndTimes()
    {
        $times = [];
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_SAT_DAY'])) {
                $times[] = $this->asSeconds($row['SSRMEET_END_TIME']);
            }
        }

        return $times;
    }

    /**
     * Answer true if there is a date/time on which section meeting begins.
     *
     * @return bool
     *              If a start date of the section is set
     */
    public function hasMeetingStartDate()
    {
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_START_DATE'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Answer date/time on which section meeting begins.
     *
     * @return dateTime
     *                  The start date of the section
     */
    public function getMeetingStartDate()
    {
        foreach ($this->getMeetingRows() as $row) {
            return new DateTime($row['SSRMEET_START_DATE']);
        }
    }

    /**
     * Answer true if there is a date/time on which section meeting ends.
     *
     * @return bool
     *              If a start date of the section is set
     */
    public function hasMeetingEndDate()
    {
        foreach ($this->getMeetingRows() as $row) {
            if (!empty($row['SSRMEET_END_DATE'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Answer date/time on which the section meeting ends.
     *
     * @return DateTime
     *                  The end date
     */
    public function getMeetingEndDate()
    {
        foreach ($this->getMeetingRows() as $row) {
            return new DateTime($row['SSRMEET_END_DATE']);
        }
    }

    /**
     * Answer the number of seconds since midnight for a time-string from our db.
     *
     * @param string $timeString
     *
     * @return int
     *
     * @since 6/10/09
     */
    protected function asSeconds($timeString)
    {
        $parts = date_parse_from_format('Hi', $timeString);

        return ((int) $parts['hour'] * 3600) + ((int) $parts['minute'] * 60);
    }

    /*********************************************************
     * EnrollmentNumbersRecord support
     *********************************************************/

    /**
     * Answer the maximum enrollment for the offering.
     *
     * This is the total number of seats available.
     *
     * @return int
     *
     * @since 1/9/18
     */
    public function getMaxEnrollment()
    {
        return (int) $this->row['SSBSECT_MAX_ENRL'];
    }

    /**
     * Answer the current enrollment for the offering.
     *
     * The number of seats currently filled.
     *
     * @return int
     *
     * @since 1/9/18
     */
    public function getEnrollment()
    {
        return (int) $this->row['SSBSECT_ENRL'];
    }

    /**
     * Answer the number of seats available to be filled.
     *
     * This should generally be the maximum enrollment minus the current
     * enrollment unless other constraints are in place.
     *
     * @return int
     *
     * @since 1/9/18
     */
    public function getSeatsAvailable()
    {
        return (int) $this->row['SSBSECT_SEATS_AVAIL'];
    }

    /*********************************************************
     * Full-text search indexing support. Internal to this implementation.
     *********************************************************/

    /**
     * Answer a string to be indexed for full-text search.
     *
     * WARNING: This method is internal to this implementation.
     *
     * @return string
     *
     * @since 6/9/09
     */
    public function getFulltextStringForIndex()
    {
        $text = '';

        $text .= ' '.$this->getNumber();
        $text .= ' '.$this->row['SSBSECT_SUBJ_CODE'].$this->row['SSBSECT_CRSE_NUMB'];
        $text .= ' '.$this->row['SSBSECT_SUBJ_CODE'];
        $text .= ' '.$this->row['SSBSECT_CRSE_NUMB'];
        $text .= ' '.$this->row['SSBSECT_CRN'];
        $text .= ' '.$this->row['term_display_label'].substr($this->row['STVTERM_START_DATE'], 2, 2);
        $text .= ' '.$this->row['SCBCRSE_DEPT_CODE'];
        $text .= ' '.$this->row['SCBCRSE_DIVS_CODE'];

        $text .= ' '.$this->getTitle();

        $text .= ' '.$this->getDescription();

        // 		$text .= ' '.$this->getPrereqInfo();

        // 		try {
        // 			$topics = $this->getTopics();
        // 			while ($topics->hasNext()) {
        // 				$topic = $topics->getNextTopic();
        // 				$text .= ' '.$topic->getDisplayName();
        // 			}
        // 		} catch (osid_OperationFailedException $e) {}
        //
        $text .= ' '.$this->getLocationInfo();
        // 		$text .= ' '.$this->getScheduleInfo();
        //
        //
        // 		if ($this->hasLearningObjective()) {
        // 			try {
        // 				$objective = $this->getLearningObjective();
        // 				$text .= ' '.$objective->getDisplayName();
        // 			} catch (osid_OperationFailedException $e) {}
        // 		}

        if ($this->hasRecordType($this->instructorsType)) {
            try {
                $record = $this->getCourseOfferingRecord($this->instructorsType);
                $instructors = $record->getInstructors();
                while ($instructors->hasNext()) {
                    $instructor = $instructors->getNextResource();
                    $text .= ' '.$instructor->getDisplayName();
                }
            } catch (osid_OperationFailedException $e) {
            } catch (osid_PermissionDeniedException $e) {
            }
        }

        return $text;
    }
}
