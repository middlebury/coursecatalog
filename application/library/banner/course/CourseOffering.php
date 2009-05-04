<?php
/**
 * @since 4/14/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 *  <p>A <code> CourseOffering </code> represents a learning unit offered 
 *  duing a <code> Term. </code> A <code> Course </code> is instantiated at a 
 *  time and place through the creation of a <code> CourseOffering. </code> 
 *  </p>
 * 
 * @package banner.course
 */
class banner_course_CourseOffering
    extends phpkit_AbstractOsidObject
    implements osid_course_CourseOffering,
    types_course_CourseOfferingInstructorsRecord
{
	/**
	 * @var array $requiredFields;
	 * @access private
	 * @since 4/16/09
	 * @static
	 */
	private static $requiredFields = array(
			'SSBSECT_TERM_CODE',
			'SSBSECT_CRN',
			'SSBSECT_SUBJ_CODE',
			'SSBSECT_CRSE_NUMB',
			'SSBSECT_SEQ_NUMB',
			'SSBSECT_CAMP_CODE',
			
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
			
			'STVBLDG_DESC',
			
			'SCBCRSE_DEPT_CODE',
			'SCBCRSE_DIVS_CODE'
		);
	
	private $row;
	private $session;
	
	/**
	 * Constructor
	 * 
	 * @param array $dbRow
	 * @param banner_course_CourseOfferingSessionInterface $session
	 * @param string $displayName
	 * @return void
	 * @access public
	 * @since 4/13/09
	 */
	public function __construct (array $row, banner_course_CourseOfferingSessionInterface $session) {
		$this->instructorsType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors');
		
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
		$this->setDescription('');
		
		$this->setGenusType(new phpkit_type_Type(
			'urn', 										// namespace
			$this->session->getIdAuthority(), 			// id authority
			'genera:offering/'.$row['STVSCHD_CODE'], 	// identifier
			'Course Offerings', 						// domain
			$row['STVSCHD_DESC'], 						// display name
			$row['STVSCHD_CODE']						// display label
		));
		
		$this->addRecordType($this->instructorsType);
	}
	
	/**
	 * Check the data row passed for all of our required fields
	 * 
	 * @param array $row
	 * @return void
	 * @access protected
	 * @since 4/16/09
	 */
	protected function checkRow (array $row) {
		 foreach (self::$requiredFields as $field) {
		 	if (!array_key_exists($field, $row)) {
		 		throw new osid_OperationFailedException("Required field, $field not found in data row.");
		 	}
		 }
	}
	
    /**
     *  Gets the formal title of this course. It may be the same as the 
     *  display name or it may be used to more formally label the course. A 
     *  display name might be Physics 102 where the title is Introduction to 
     *  Electromagentism. 
     *
     *  @return string the course title 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTitle() {
    	if (isset($this->row['SSBSECT_CRSE_TITLE']) && strlen($this->row['SSBSECT_CRSE_TITLE']))
    		return $this->row['SSBSECT_CRSE_TITLE'];
    	
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
     *  @return string the course number 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNumber() {
    	return $this->getDisplayName();
    }


    /**
     *  Gets the number of credits in this course. 
     *
     *  @return float the number of credits 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCredits() {
    	if (isset($this->row['SSBSECT_CREDIT_HRS']) && strlen($this->row['SSBSECT_CREDIT_HRS']))
    		return floatval($this->row['SSBSECT_CREDIT_HRS']);
    	else
    		return $this->getCourse()->getCredits();
    }


    /**
     *  Gets the an informational string for the course prerequisites. 
     *
     *  @return string the prerequisites 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPrereqInfo() {
    	return '';
    }


    /**
     *  Gets the canonical course <code> Id </code> associated with this 
     *  course offering. 
     *
     *  @return object osid_id_Id the course <code> Id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseId() {
    	return $this->session->getCourseIdFromSubjectAndNumber($this->row['SSBSECT_SUBJ_CODE'], $this->row['SSBSECT_CRSE_NUMB']);
    }


    /**
     *  Gets the canonical course associated with this course offering. 
     *
     *  @return object osid_course_Course the course 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourse() {
    	if (!isset($this->course))
    		$this->course = $this->session->getCourseLookupSession()->getCourse($this->getCourseId());
    	return $this->course;
    }
	
	private $course;

    /**
     *  Gets the <code> Id </code> of the <code> Term </code> of this 
     *  offering. 
     *
     *  @return object osid_id_Id the <code> Term </code> <code> Id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermId() {
		return $this->getOsidIdFromString($this->row['SSBSECT_TERM_CODE'], 'term/');
    }


    /**
     *  Gets the <code> Term </code> of this offering. 
     *
     *  @return object osid_course_Term the term 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTerm() {
    	return $this->session->getTermLookupSession()->getTerm($this->getTermId());
    }
    
    /**
     *  WARNING: This method was not in the OSID trunk as of 2009-04-27. A 
     *  ticket requesting the addition of this method is available at: 
     *  http://oki.assembla.com/spaces/osid-dev/tickets/18-osid-course---No-way-to-map-Topics-to-Courses-or-CourseOfferings- 
     *  Gets a list of the <code> Id </code> s of the <code> Topic </code> s 
     *  this offering is associated with. 
     *
     *  @return object osid_id_IdList the <code> Topic </code> <code> Id 
     *          </code> s 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopicIds() {
    	if (!isset($this->topicIds)) {
	    	$this->topicIds = array();
	    	if ($this->row['SCBCRSE_DEPT_CODE'])
	    		$this->topicIds[] = $this->getOsidIdFromString($this->row['SCBCRSE_DEPT_CODE'], 'topic/department/');
	    	if ($this->row['SSBSECT_SUBJ_CODE'])
	    		$this->topicIds[] = $this->getOsidIdFromString($this->row['SSBSECT_SUBJ_CODE'], 'topic/subject/');
	    	if ($this->row['SCBCRSE_DIVS_CODE'])
	    		$this->topicIds[] = $this->getOsidIdFromString($this->row['SCBCRSE_DIVS_CODE'], 'topic/division/');
	    	
	    	$this->topicIds = array_merge($this->topicIds, $this->session->getRequirementTopicIdsForCourseOffering($this->getId()));
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
     *  @return object osid_course_TopicList the topics 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopics() {
    	return $this->session->getTopicLookupSession()->getTopicsByIds($this->getTopicIds());
    }


    /**
     *  Gets a string describing the location of this course offering. 
     *
     *  @return string location info 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLocationInfo() {
    	return $this->row['SSRMEET_BLDG_CODE'].' ' .$this->row['SSRMEET_ROOM_CODE']
    		.' ('.$this->row['STVBLDG_DESC'].')';
    }


    /**
     *  Tests if this course offering has an associated location resource. 
     *
     *  @return boolean <code> true </code> if this course offering has a 
     *          location resource, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasLocation() {
    	return true;
    }


    /**
     *  Gets the <code> Id </code> of the <code> Resource </code> representing 
     *  the location of this course offering. 
     *
     *  @return object osid_id_Id the location 
     *  @throws osid_IllegalStateException <code> hasLocation() </code> is 
     *          <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLocationId() {
    	return $this->getOsidIdFromString(
    		$this->row['SSRMEET_BLDG_CODE'].'/'.$this->row['SSRMEET_ROOM_CODE'], 
    		'resource/place/room/');
    }


    /**
     *  Gets the <code> Resource </code> representing the location of this 
     *  offering. 
     *
     *  @return object osid_resource_Resource the location 
     *  @throws osid_IllegalStateException <code> hasLocation() </code> is 
     *          <code> false </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLocation() {
    	return $this->session->getResourceLookupSession()->getResource($this->getLocationId());
    }


    /**
     *  Gets a string describing the schedule of this course offering. 
     *
     *  @return string schedule info 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getScheduleInfo() {
    	$days = array();
    	if ($this->row['SSRMEET_SUN_DAY'])	
    		$days[] = 'Sunday';
    	if ($this->row['SSRMEET_MON_DAY'])	
    		$days[] = 'Monday';
    	if ($this->row['SSRMEET_TUE_DAY'])	
    		$days[] = 'Tuesday';
    	if ($this->row['SSRMEET_WED_DAY'])	
    		$days[] = 'Wednesday';
    	if ($this->row['SSRMEET_THU_DAY'])	
    		$days[] = 'Thursday';
    	if ($this->row['SSRMEET_FRI_DAY'])	
    		$days[] = 'Friday';
    	if ($this->row['SSRMEET_SAT_DAY'])	
    		$days[] = 'Saturday';
    	
    	return $this->as12HourTime($this->row['SSRMEET_BEGIN_TIME'])
    		.'-'.$this->as12HourTime($this->row['SSRMEET_END_TIME'])
    		.' on '.implode(', ', $days);
    	
    }
    
    /**
     * Convert a 24-hour time into a 12-hour time
     * 
     * @param string $time
     * @return string
     * @access protected
     * @since 4/16/09
     */
    protected function as12HourTime ($time) {
    	$parts = strptime($time, '%H%M');
    	return date('H:ia', mktime($parts['tm_hour'], $parts['tm_min']));
    }


    /**
     *  Tests if this course offering has an associated calendar. 
     *
     *  @return boolean <code> true </code> if this course offering has a 
     *          calendar, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasCalendar() {
    	return false;
    }


    /**
     *  Gets the calendar for this course offering. Schedule items are 
     *  associated with this calendar through the available Scheduling 
     *  manager. 
     *
     *  @return object osid_id_Id <code> Id </code> of a <code> </code> 
     *          calendar 
     *  @throws osid_IllegalStateException <code> hasCalendar() </code> is 
     *          <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCalendarId() {
    	throw new osid_IllegalStateException('This version of the OSID does not support Learning Objectives');
    	return $this->getOsidIdFromString(
    		$this->row['SSBSECT_TERM_CODE'].'/'.$this->row['SSBSECT_CRN'], 
    		'CourseSchedule/');
    }


    /**
     *  Gets the calendar for this course offering, which may be a root in a 
     *  calendar hierarchy. 
     *
     *  @return object osid_calendaring_Calendar a calendar 
     *  @throws osid_IllegalStateException <code> hasCalendar() </code> is 
     *          <code> false </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCalendar() {
    	throw new osid_IllegalStateException('This version of the OSID does not support Calendering');
    	return $this->session->getCalendarLookupSession()->getResource($this->getCalendarId());
    }


    /**
     *  Tests if this course offering has an associated learning objective. 
     *
     *  @return boolean <code> true </code> if this course offering has a 
     *          learning objective, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasLearningObjective() {
    	return false;
    }


    /**
     *  Gets the root node of a learning objective map for this course 
     *  offering. 
     *
     *  @return object osid_id_Id <code> Id </code> of a <code> l </code> 
     *          earning <code> Objective </code> 
     *  @throws osid_IllegalStateException <code> hasLearningObjective() 
     *          </code> is <code> false </code> 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function getLearningObjectiveId() {
    	throw new osid_IllegalStateException('This version of the OSID does not support Learning Objectives');
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the root node of a learning objective map for this course 
     *  offering. 
     *
     *  @return object osid_learning_Objective the returned learning <code> 
     *          Objective </code> 
     *  @throws osid_IllegalStateException <code> hasLearningObjective() 
     *          </code> is <code> false </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLearningObjective() {
    	throw new osid_IllegalStateException('This version of the OSID does not support Learning Objectives');
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets an external resource, such as a class web site, associated with 
     *  this offering. 
     *
     *  @return string a URL string 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getURL() {
    	throw new osid_UnimplementedException();
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
     *  @return object osid_course_CourseOfferingRecord the course offering 
     *          record 
     *  @throws osid_NullArgumentException <code> courseOfferingRecordType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(courseOfferingRecordType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingRecord(osid_type_Type $courseOfferingRecordType) {
    	if ($courseOfferingRecordType->isEqual($this->instructorsType))
    		return $this;
    	
    	throw new osid_UnsupportedException('Record type is not supported.');
    }
    
    /**
	 * Answer an Id object from a string database Id
	 * 
	 * @param string $databaseId
	 * @param string optional $prefix
	 * @return osid_id_Id
	 * @access protected
	 * @since 4/10/09
	 */
	protected function getOsidIdFromString ($databaseId, $prefix = null) {
		if (is_null($prefix))
			$prefix = $this->idPrefix;
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
     *  <code> getType(). </code> 
     *
     *  @param object osid_type_Type $recordType a type 
     *  @return boolean <code> true </code> if the given record <code> Type 
     *          </code> is implemented by this record, <code> false </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> recordType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function implementsRecordType(osid_type_Type $recordType) {
    	return $recordType->isEqual($this->instructorsType);
    }
    
    /**
     *  Gets the <code> CourseOffering </code> from which this record 
     *  originated. 
     *
     *  @return object osid_course_CourseOffering the course offering 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOffering() {
    	return $this;
    }
	
/*********************************************************
 * InstructorsRecord support
 *********************************************************/

	/**
     *  Gets the Ids of the instructors associated with this course offering
     *
     *  @return object osid_id_IdList the list of instructor ids.
     *  @compliance mandatory This method must be implemented. 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     */
    public function getInstructorIds() {
    	return $this->session->getInstructorIdsForOffering($this->getId());
    }
    
    /**
     *  Gets the <code> Resources </code> representing the instructors associated
     *  with this course offering.
     *
     *  @return object osid_resource_ResourceList the list of instructors.
     *  @compliance mandatory This method must be implemented. 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     */
    public function getInstructors() {
    	return $this->session->getInstructorsForOffering($this->getId());
    }

}
