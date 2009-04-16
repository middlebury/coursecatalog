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
 * @package org.osid.course
 */
class banner_course_CourseOffering
    extends phpkit_AbstractOsidObject
    implements osid_course_CourseOffering
{
	/**
	 * @var array $requiredFields;
	 * @access private
	 * @since 4/16/09
	 * @static
	 */
	private static $requiredFields = array(
			// From ssbsect
			'SSBSECT_TERM_CODE',
			'SSBSECT_CRN',
			'SSBSECT_SUBJ_CODE',
			'SSBSECT_CRSE_NUMB',
			'SSBSECT_SEQ_NUMB',
			
			// From stvterm
			'STVTERM_TRMT_CODE',
			'STVTERM_START_DATE'
		);
	
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
		parent::__construct();
		$this->checkRow($row);
		$this->row = $row;
		$this->session = $session;
		$this->setId($this->session->getIdFromTermCodeAndCrn($row['SSBSECT_TERM_CODE'], $row['SSBSECT_CRN']));
		$this->setDisplayName(
			$row['SSBSECT_SUBJ_CODE']
			.$row['SSBSECT_CRSE_NUMB']
			.$row['SSBSECT_SEQ_NUMB']
			.'-'.$row['STVTERM_TRMT_CODE']
			.substr($row['STVTERM_START_DATE'], 2, 2));
		$this->setDescription('');
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
		 	if (!isset($row[$field]))
		 		throw new OperationFailedException("Required field, $field not found in data row.");
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
    	else
    		return $this->getCourse()->getTitle();
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
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the canonical course associated with this course offering. 
     *
     *  @return object osid_course_Course the course 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourse() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the <code> Id </code> of the <code> Term </code> of this 
     *  offering. 
     *
     *  @return object osid_id_Id the <code> Term </code> <code> Id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermId() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the <code> Term </code> of this offering. 
     *
     *  @return object osid_course_Term the term 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTerm() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets a string describing the location of this course offering. 
     *
     *  @return string location info 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLocationInfo() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Tests if this course offering has an associated location resource. 
     *
     *  @return boolean <code> true </code> if this course offering has a 
     *          location resource, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasLocation() {
    	throw new osid_UnimplementedException();
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
    	throw new osid_UnimplementedException();
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
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets a string describing the schedule of this course offering. 
     *
     *  @return string schedule info 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getScheduleInfo() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Tests if this course offering has an associated calendar. 
     *
     *  @return boolean <code> true </code> if this course offering has a 
     *          calendar, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasCalendar() {
    	throw new osid_UnimplementedException();
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
    	throw new osid_UnimplementedException();
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
    	throw new osid_UnimplementedException();
    }


    /**
     *  Tests if this course offering has an associated learning objective. 
     *
     *  @return boolean <code> true </code> if this course offering has a 
     *          learning objective, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasLearningObjective() {
    	throw new osid_UnimplementedException();
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
    	throw new osid_UnimplementedException();
    }

}
