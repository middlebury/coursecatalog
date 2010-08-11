<?php
/**
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 *  <p>A <code> CourseOffering </code> represents a learning unit offered 
 *  duing a <code> Term. </code> A <code> Course </code> is instantiated at a 
 *  time and place through the creation of a <code> CourseOffering. </code> 
 *  </p>
 */
class apc_course_CourseOffering
    extends apc_Cachable
    implements osid_course_CourseOffering,
    middlebury_course_CourseOffering_InstructorsRecord,
    middlebury_course_CourseOffering_AlternatesRecord,
	middlebury_course_CourseOffering_LinkRecord
{
	
	/**
	 * Constructor
	 * 
	 * @param osid_course_CourseLookupSession $session
	 * @param osid_id_Id $id
	 * @return void
	 * @access public
	 * @since 8/10/10
	 */
	public function __construct (apc_course_CourseOffering_Lookup_Session $apcSession, osid_course_CourseOfferingLookupSession $session, osid_id_Id $id) {
		parent::__construct($id->getIdentifierNamespace().':'.$id->getAuthority().':'.$id->getIdentifier());
		
		$this->apcSession = $apcSession;
		$this->session = $session;
		$this->id = $id;
		
		$this->localRecordTypes = array(
			new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:instructors'),
			new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:weekly_schedule'),
			new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:alternates'),
			new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:link'),
		);
	}
	private $courseOffering;
	private $session;
	private $apcSession;
	private $id;
	private $localRecordTypes;
	
	/**
	 * Answer our internal course offering object
	 * 
	 * @return osid_course_CourseOffering
	 * @access private
	 * @since 8/10/10
	 */
	private function getOffering () {
		if (!isset($this->courseOffering))
			$this->courseOffering = $this->session->getCourseOffering($this->getId());
		return $this->courseOffering;
	}
	
/*********************************************************
 * Interface Methods
 *********************************************************/
 
/*********************************************************
 * osid_OsidObject
 *********************************************************/


	/**
     *  Gets the <code> Id </code> associated with this instance of this OSID 
     *  object. Persisting any reference to this object is done by persisting 
     *  the <code> Id </code> returned from this method. The <code> Id </code> 
     *  returned may be different than the <code> Id </code> used to query 
     *  this object. In this case, the new <code> Id </code> should be 
     *  preferred over the old one for future queries. 
     *
     *  @return the <code> Id </code> 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  The <code> Id </code> is intended to be constant and 
     *          persistent. A consumer may at any time persist the <code> Id 
     *          </code> for retrieval at any future time. Ideally, the <code> 
     *          Id </code> should consistently resolve into the designated 
     *          object and not be reused. In cases where objects are 
     *          deactivated after a certain lifetime the provider should 
     *          endeavor not to obliterate the object or its <code> Id </code> 
     *          but instead should update the properties of the object 
     *          including the deactiavted status and the elimination of any 
     *          unwanted pieces of data. As such, there is no means for 
     *          updating an <code> Id </code> and providers should consider 
     *          carefully the identification scheme to implement. 
     *          <br/><br/>
     *          <code> Id </code> assignments for objects are strictly in the 
     *          realm of the provider and any errors should be fixed directly 
     *          with the backend supporting system. Once an <code> Id </code> 
     *          has been assigned in a production service it should be honored 
     *          such that it may be necessary for the backend system to 
     *          support <code> Id </code> aliasing to redirect the lookup to 
     *          the current <code> Id. </code> Use of an <code> Id </code> 
     *          OSID may be helpful to accomplish this task in a modular 
     *          manner. 
     */
    public function getId() {
		return $this->id;
	}

	/**
     *  Gets the preferred display name associated with this instance of this 
     *  OSID object appropriate for display to the user. 
     *
     *  @return the display name 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  A display name is a string used for identifying an object in 
     *          human terms. A provider may wish to initialize the display 
     *          name based on one or more object attributes. In some cases, 
     *          the display name may not map to a specific or significant 
     *          object attribute but simply be used as a preferred display 
     *          name that can be modified. A provider may also wish to 
     *          translate the display name into a specific locale using the 
     *          Locale service. Some OSIDs define methods for more detailed 
     *          naming. 
     */
    public function getDisplayName() {
		$val = $this->cacheGetPlain('displayName');
    	if (is_null($val))
    		return $this->cacheSetPlain('displayName', $this->getOffering()->getDisplayName());
    	else
    		return $val;
	}

    /**
     *  Gets the description associated with this instance of this OSID 
     *  object. 
     *
     *  @return the description 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  A description is a string used for describing an object in 
     *          human terms and may not have significance in the underlying 
     *          system. A provider may wish to initialize the description 
     *          based on one or more object attributes and/or treat it as an 
     *          auxiliary piece of data that can be modified. A provider may 
     *          also wish to translate the description into a specific locale 
     *          using the Locale service. 
     */
    public function getDescription() {
    	$val = $this->cacheGetPlain('description');
    	if (is_null($val))
    		return $this->cacheSetPlain('description', $this->getOffering()->getDescription());
    	else
    		return $val;
	}
	
    /**
     *  Gets the record types available in this object. A record <code> Type 
     *  </code> explicitly indicates the specification of an interface to the 
     *  record. A record may or may not inherit other record interfaces 
     *  through interface inheritance in which case support of a record type 
     *  may not be explicit in the returned list. Interoperability with the 
     *  typed interface to this object should be performed through <code> 
     *  hasRecordType(). </code> 
     *
     *  @return the record types available through this object 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRecordTypes() {
    	$val = $this->cacheGetObj('recordTypes');
    	if (is_null($val)) {
    		$val = array();
    		$types = $this->getOffering()->getRecordTypes();
    		while ($types->hasNext()) {
    			$val[] = $types->getNextType();
    		}
    		$this->cacheSetObj('recordTypes', $val);
    	}
    	return new phpkit_type_ArrayTypeList($val);
	}


    /**
     *  Tests if this object supports the given record <code> Type. </code> 
     *  The given record type may be supported by the object through 
     *  interface/type inheritence. This method should be checked before 
     *  retrieving the record interface. 
     *
     *  @param object osid_type_Type $recordType a type 
     *  @return <code> true </code> if a record of the given record <code> 
     *          Type </code> is available, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> recordType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasRecordType(osid_type_Type $recordType) {
    	$types = $this->getRecordTypes();
    	while ($types->hasNext()) {
    		if ($recordType->isEqual($types->getNextType()))
    			return true;
    	}
    	return false;
	}

    /**
     *  Gets the genus type of this object. 
     *
     *  @return the genus type of this object 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getGenusType() {
    	$val = $this->cacheGetObj('genusType');
    	if (is_null($val))
    		return $this->cacheSetObj('genusType', $this->getOffering()->getGenusType());
    	else
    		return $val;
	}

    /**
     *  Tests if this object is of the given genus <code> Type. </code> The 
     *  given genus type may be supported by the object through the type 
     *  hierarchy. 
     *
     *  @param object osid_type_Type $genusType a genus type 
     *  @return <code> true </code> if this object is of the given genus 
     *          <code> Type, </code> <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> genusType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isOfGenusType(osid_type_Type $genusType) {
    	return $this->getGenusType()->isEqual($genusType);
	}


    /**
     *  Tests to see if the last method invoked retrieved up-to-date data. 
     *  Simple retrieval methods do not specify errors as, generally, the data 
     *  is retrieved once at the time this object is instantiated. Some 
     *  implementations may provide real-time data though the application may 
     *  not always care. An implementation providing a real-time service may 
     *  fall back to a previous snapshot in case of error. This method returns 
     *  false if the data last retrieved was stale. 
     *
     *  @return <code> true </code> if the last data retrieval was up to date, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  Providers should return false unless all getters are 
     *          implemented using real-time queries, or some trigger process 
     *          keeps the data in this object current. Providers should 
     *          populate basic data elements at the time this object is 
     *          instantiated, or set an error, to ensure some data 
     *          availability. 
     */
    public function isCurrent() {
    	return false;
	}


    /**
     *  Gets a list of all properties of this object including those 
     *  corresponding to data within this object's records. Properties provide 
     *  a means for applications to display a representation of the contents 
     *  of an object without understanding its record interface 
     *  specifications. Applications needing to examine a specific property or 
     *  perform updates should use the methods defined by the object's record 
     *  <code> Type. </code> 
     *
     *  @return a list of properties 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException an authorization failure 
     *          occurred 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getProperties() {
    	return $this->getOffering()->getProperties();
	}


    /**
     *  Gets a list of properties corresponding to the specified record type. 
     *  Properties provide a means for applications to display a 
     *  representation of the contents of an object without understanding its 
     *  record interface specifications. Applications needing to examine a 
     *  specific property or perform updates should use the methods defined by 
     *  the object record <code> Type. </code> The resulting set includes 
     *  properties specified by parents of the record <code> type </code> in 
     *  the case a record's interface extends another. 
     *
     *  @param object osid_type_Type $recordType the record type corresponding 
     *          to the properties set to retrieve 
     *  @return a list of properties 
     *  @throws osid_NullArgumentException <code> recordType </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException an authorization failure 
     *          occurred 
     *  @throws osid_UnsupportedException <code> hasRecordType(recordType) 
     *          </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPropertiesByRecordType(osid_type_Type $recordType) {
    	if (!$this->hasRecordType($recordType))
			throw new osid_UnsupportedException("record type not supported");
		
    	return $this->getOffering()->getPropertiesByRecordType($recordType);
	}
    
    /**
     * Convert a type to a string.
     * 
     * @param osid_type_Type $type
     * @return string
     * @access private
     * @since 4/30/09
     */
    private function typeToString (osid_type_Type $type) {
    	return $type->getIdentifierNamespace().":".$type->getAuthority().":".$type->getIdentifier();
    }


/*********************************************************
 * osid_course_CourseOffering
 *********************************************************/

	
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
    	$val = $this->cacheGetPlain('title');
    	if (is_null($val))
    		return $this->cacheSetPlain('title', $this->getOffering()->getTitle());
    	else
    		return $val;
	}


    /**
     *  Gets the course number which is a label generally used to indedx the 
     *  course in a catalog, such as T101 or 16.004. 
     *
     *  @return string the course number 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNumber() {
    	$val = $this->cacheGetPlain('number');
    	if (is_null($val))
    		return $this->cacheSetPlain('number', $this->getOffering()->getNumber());
    	else
    		return $val;
	}


    /**
     *  Gets the number of credits in this course. 
     *
     *  @return float the number of credits 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCredits() {
    	$val = $this->cacheGetPlain('credits');
    	if (is_null($val))
    		return $this->cacheSetPlain('credits', $this->getOffering()->getCredits());
    	else
    		return $val;
	}


    /**
     *  Gets the an informational string for the course prerequisites. 
     *
     *  @return string the prerequisites 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPrereqInfo() {
    	$val = $this->cacheGetPlain('prerequisites');
    	if (is_null($val))
    		return $this->cacheSetPlain('prerequisites', $this->getOffering()->getPrereqInfo());
    	else
    		return $val;
	}


    /**
     *  Gets the canonical course <code> Id </code> associated with this 
     *  course offering. 
     *
     *  @return object osid_id_Id the course <code> Id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseId() {
    	$val = $this->cacheGetObj('course_id');
    	if (is_null($val))
    		return $this->cacheSetObj('course_id', $this->getOffering()->getCourseId());
    	else
    		return $val;
	}


    /**
     *  Gets the canonical course associated with this course offering. 
     *
     *  @return object osid_course_Course the course 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourse() {
    	return $this->apcSession->getCourseLookupSession()->getCourse($this->getCourseId());
	}
	
    /**
     *  Gets the <code> Id </code> of the <code> Term </code> of this 
     *  offering. 
     *
     *  @return object osid_id_Id the <code> Term </code> <code> Id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermId() {
		$val = $this->cacheGetObj('term_id');
    	if (is_null($val))
    		return $this->cacheSetObj('term_id', $this->getOffering()->getTermId());
    	else
    		return $val;
	}


    /**
     *  Gets the <code> Term </code> of this offering. 
     *
     *  @return object osid_course_Term the term 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTerm() {
    	return $this->getOffering()->getTerm();
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
    	$val = $this->cacheGetObj('topic_ids');
    	if (is_null($val)) {
    		$val = array();
    		$ids = $this->getOffering()->getTopicIds();
    		while ($ids->hasNext()) {
    			$val[] = $ids->getNextId();
    		}
    		$this->cacheSetObj('topic_ids', $val);
    	}
    	return new phpkit_id_ArrayIdList($val);
	}

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
    	return $this->getOffering()->getTopics();
	}


    /**
     *  Gets a string describing the location of this course offering. 
     *
     *  @return string location info 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLocationInfo() {
    	$val = $this->cacheGetPlain('location_info');
    	if (is_null($val))
    		return $this->cacheSetPlain('location_info', $this->getOffering()->getLocationInfo());
    	else
    		return $val;
	}


    /**
     *  Tests if this course offering has an associated location resource. 
     *
     *  @return boolean <code> true </code> if this course offering has a 
     *          location resource, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasLocation() {
    	$val = $this->cacheGetPlain('has_location');
    	if (is_null($val))
    		return $this->cacheSetPlain('has_location', $this->getOffering()->hasLocation());
    	else
    		return $val;
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
    	$val = $this->cacheGetObj('lcoation_id');
    	if (is_null($val))
    		return $this->cacheSetObj('lcoation_id', $this->getOffering()->getLocationId());
    	else
    		return $val;
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
    	try {
	    	return $this->apcSession->getResourceLookupSession()->getResource($this->getLocationId());
	    } catch (osid_NotFoundException $e) {
	    	throw new osid_OperationFailedException($e->getMessage());
	    }
	}


    /**
     *  Gets a string describing the schedule of this course offering. 
     *
     *  @return string schedule info 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getScheduleInfo() {
    	$val = $this->cacheGetPlain('schedule_info');
    	if (is_null($val))
    		return $this->cacheSetPlain('schedule_info', $this->getOffering()->getScheduleInfo());
    	else
    		return $val;
	}
    
    /**
     *  Tests if this course offering has an associated calendar. 
     *
     *  @return boolean <code> true </code> if this course offering has a 
     *          calendar, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasCalendar() {
    	$val = $this->cacheGetPlain('has_calendar');
    	if (is_null($val))
    		return $this->cacheSetPlain('has_calendar', $this->getOffering()->hasCalendar());
    	else
    		return $val;
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
    	if (!$this->hasCalendar())
    		throw new osid_IllegalStateException('hasCalendar() is false.');
    	
    	$val = $this->cacheGetObj('schedule_info');
    	if (is_null($val))
    		return $this->cacheSetObj('schedule_info', $this->getOffering()->getScheduleInfo());
    	else
    		return $val;
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
    	if (!$this->hasCalendar())
    		throw new osid_IllegalStateException('hasCalendar() is false.');
    	
    	return $this->getOffering()->getCalendar();
	}


    /**
     *  Tests if this course offering has an associated learning objective. 
     *
     *  @return boolean <code> true </code> if this course offering has a 
     *          learning objective, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasLearningObjective() {
    	$val = $this->cacheGetPlain('hasLearningObjective');
    	if (is_null($val))
    		return $this->cacheSetPlain('hasLearningObjective', $this->getOffering()->hasLearningObjective());
    	else
    		return $val;
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
    	if (!$this->hasLearningObjective())
    		throw new osid_IllegalStateException('hasLearningObjective() is false.');
    	
    	$val = $this->cacheGetObj('learningObjectiveId');
    	if (is_null($val))
    		return $this->cacheSetObj('learningObjectiveId', $this->getOffering()->getLearningObjectiveId());
    	else
    		return $val;
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
    	if (!$this->hasLearningObjective())
    		throw new osid_IllegalStateException('hasLearningObjective() is false.');
    	
    	return $this->getOffering()->getLearningObjective();
	}


    /**
     *  Gets an external resource, such as a class web site, associated with 
     *  this offering. 
     *
     *  @return string a URL string 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getURL() {
    	$val = $this->cacheGetPlain('url');
    	if (is_null($val))
    		return $this->cacheSetPlain('url', $this->getOffering()->getURL());
    	else
    		return $val;
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
    	if ($this->implementsRecordType($courseOfferingRecordType))
    		return $this;
    	
    	return $this->getOffering()->getCourseOfferingRecord($courseOfferingRecordType);
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
    	foreach ($this->localRecordTypes as $type) {
    		if ($type->isEqual($recordType))
    			return true;
    	}
    	return false;
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
    	$val = $this->cacheGetObj('instructor_ids');
    	if (is_null($val))
    		return $this->cacheSetObj('instructor_ids', $this->getOffering()->getInstructorIds());
    	else
    		return $val;
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
    	return $this->getOffering()->getInstructors();
    }
    
/*********************************************************
 * LinkRecord support
 *********************************************************/

	/**
	 * Answer the link identifier for an Offering. When registering
	 * for a Course that has multiple Offerings (such as lecture + lab or 
	 * lectures at different times), they must register for one Offering for 
	 * each link identifier present.
	 * 
	 * 
	 * @return osid_id_Id
	 * @access public
	 * @since 8/3/10
	 */
	public function getLinkId () {
		$val = $this->cacheGetPlain('link_id');
    	if (is_null($val))
    		return $this->cacheSetPlain('link_id', $this->getOffering()->getLinkId());
    	else
    		return $val;
	}
    
/*********************************************************
 * AlternatesRecord support
 *********************************************************/
 	/**
	 * Tests if this course offering has any alternate course offerings.
	 * 
	 * @return boolean <code> true </code> if this course offering has any
     *          alternates, <code> false </code> otherwise 
	 * @access public
     * @compliance mandatory This method must be implemented. 
	 */
	public function hasAlternates () {
		$val = $this->cacheGetPlain('hasAlternates');
    	if (is_null($val))
    		return $this->cacheSetPlain('hasAlternates', $this->getOffering()->hasAlternates());
    	else
    		return $val;
	}
	
	/**
     *  Gets the Ids of any alternate course offerings
     *
     *  @return object osid_id_IdList the list of alternate ids.
     *  @compliance mandatory This method must be implemented. 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     */
    public function getAlternateIds() {
    	$val = $this->cacheGetObj('alternate_ids');
    	if (is_null($val))
    		return $this->cacheSetObj('alternate_ids', $this->getOffering()->getAlternateIds());
    	else
    		return $val;
    }
    
    /**
     *  Gets the alternate <code> CourseOfferings </code>.
     *
     *  @return object osid_course_CourseOfferingList The list of alternates.
     *  @compliance mandatory This method must be implemented. 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     */
    public function getAlternates() {
    	return $this->session->getCourseOfferingsByIds($this->getAlternateIds());
    }
    
    /**
	 * Answer <code> true </code> if this course is the primary version in a group of
	 * alternates.
	 * 
	 *  @return boolean
     *  @compliance mandatory This method must be implemented. 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
	 */
	public function isPrimary () {
		$val = $this->cacheGetPlain('isPrimary');
    	if (is_null($val))
    		return $this->cacheSetPlain('isPrimary', $this->getOffering()->isPrimary());
    	else
    		return $val;
	}
    
/*********************************************************
 * WeeklyScheduleRecord support
 *********************************************************/
 
 	/**
     * Answer true if this CourseOffering meets on Sunday
     * 
     * @return boolean
     * @access public
     * @compliance mandatory This method must be implemented. 
     */
    public function meetsOnSunday () {
    	$val = $this->cacheGetPlain('meetsOnSunday');
    	if (is_null($val))
    		return $this->cacheSetPlain('meetsOnSunday', $this->getOffering()->meetsOnSunday());
    	else
    		return $val;
	}
    
    /**
     * Answer time the meeting starts on Sunday
     * 
     * @return array An array of start-times whose order matches those returned by getSundayEndTimes()
	 *		Times are  in seconds from midnight Sunday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnSunday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getSundayStartTimes () {
    	$val = $this->cacheGetPlain('SundayStartTimes');
    	if (is_null($val))
    		return $this->cacheSetPlain('SundayStartTimes', $this->getOffering()->getSundayStartTimes());
    	else
    		return $val;
	}
    
    /**
     * Answer time the meeting ends on Sunday
     * 
     * @return array An array of end-times whose order matches those returned by getSundayStartTimes()
	 *		Times are  in seconds from midnight Sunday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnSunday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getSundayEndTimes () {
    	$val = $this->cacheGetPlain('SundayEndTimes');
    	if (is_null($val))
    		return $this->cacheSetPlain('SundayEndTimes', $this->getOffering()->getSundayEndTimes());
    	else
    		return $val;
	}
    
    /**
     * Answer true if this CourseOffering meets on Monday
     * 
     * @return boolean
     * @access public
     * @compliance mandatory This method must be implemented. 
     */
    public function meetsOnMonday () {
    	$val = $this->cacheGetPlain('meetsOnMonday');
    	if (is_null($val))
    		return $this->cacheSetPlain('meetsOnMonday', $this->getOffering()->meetsOnMonday());
    	else
    		return $val;
	}
    
    /**
     * Answer time the meeting starts on Monday
     * 
     * @return array An array of start-times whose order matches those returned by getMondayEndTimes()
	 *		Times are  in seconds from midnight Monday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnMonday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getMondayStartTimes () {
    	$val = $this->cacheGetPlain('MondayStartTimes');
    	if (is_null($val))
    		return $this->cacheSetPlain('MondayStartTimes', $this->getOffering()->getMondayStartTimes());
    	else
    		return $val;
	}
    
    /**
     * Answer time the meeting ends on Monday
     * 
     * @return array An array of end-times whose order matches those returned by getMondayStartTimes()
	 *		Times are  in seconds from midnight Monday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnMonday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getMondayEndTimes () {
    	$val = $this->cacheGetPlain('MondayEndTimes');
    	if (is_null($val))
    		return $this->cacheSetPlain('MondayEndTimes', $this->getOffering()->getMondayEndTimes());
    	else
    		return $val;
	}
    
    /**
     * Answer true if this CourseOffering meets on Tuesday
     * 
     * @return boolean
     * @access public
     * @compliance mandatory This method must be implemented. 
     */
    public function meetsOnTuesday () {
    	$val = $this->cacheGetPlain('meetsOnTuesday');
    	if (is_null($val))
    		return $this->cacheSetPlain('meetsOnTuesday', $this->getOffering()->meetsOnTuesday());
    	else
    		return $val;
	}
    
    /**
     * Answer time the meeting starts on Tuesday
     * 
     * @return array An array of start-times whose order matches those returned by getTuesdayEndTimes()
	 *		Times are  in seconds from midnight Tuesday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnTuesday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getTuesdayStartTimes () {
    	$val = $this->cacheGetPlain('TuesdayStartTimes');
    	if (is_null($val))
    		return $this->cacheSetPlain('TuesdayStartTimes', $this->getOffering()->getTuesdayStartTimes());
    	else
    		return $val;
	}
    
    /**
     * Answer time the meeting ends on Tuesday
     * 
     * @return array An array of end-times whose order matches those returned by getTuesdayStartTimes()
	 *		Times are  in seconds from midnight Tuesday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnTuesday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getTuesdayEndTimes () {
    	$val = $this->cacheGetPlain('TuesdayEndTimes');
    	if (is_null($val))
    		return $this->cacheSetPlain('TuesdayEndTimes', $this->getOffering()->getTuesdayEndTimes());
    	else
    		return $val;
	}

	/**
     * Answer true if this CourseOffering meets on Wednesday
     * 
     * @return boolean
     * @access public
     * @compliance mandatory This method must be implemented. 
     */
    public function meetsOnWednesday () {
    	$val = $this->cacheGetPlain('meetsOnWednesday');
    	if (is_null($val))
    		return $this->cacheSetPlain('meetsOnWednesday', $this->getOffering()->meetsOnWednesday());
    	else
    		return $val;
	}
    
    /**
     * Answer time the meeting starts on Wednesday
     * 
     * @return array An array of start-times whose order matches those returned by getWednesdayEndTimes()
	 *		Times are  in seconds from midnight Wednesday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnWednesday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getWednesdayStartTimes () {
    	$val = $this->cacheGetPlain('WednesdayStartTimes');
    	if (is_null($val))
    		return $this->cacheSetPlain('WednesdayStartTimes', $this->getOffering()->getWednesdayStartTimes());
    	else
    		return $val;
	}
    
    /**
     * Answer time the meeting ends on Wednesday
     * 
     * @return array An array of end-times whose order matches those returned by getWednesdayStartTimes()
	 *		Times are  in seconds from midnight Wednesday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnWednesday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getWednesdayEndTimes () {
    	$val = $this->cacheGetPlain('WednesdayEndTimes');
    	if (is_null($val))
    		return $this->cacheSetPlain('WednesdayEndTimes', $this->getOffering()->getWednesdayEndTimes());
    	else
    		return $val;
	}
    
    /**
     * Answer true if this CourseOffering meets on Thursday
     * 
     * @return boolean
     * @access public
     * @compliance mandatory This method must be implemented. 
     */
    public function meetsOnThursday () {
    	$val = $this->cacheGetPlain('meetsOnThursday');
    	if (is_null($val))
    		return $this->cacheSetPlain('meetsOnThursday', $this->getOffering()->meetsOnThursday());
    	else
    		return $val;
	}
    
    /**
     * Answer time the meeting starts on Thursday
     * 
     * @return array An array of start-times whose order matches those returned by getThursdayEndTimes()
	 *		Times are  in seconds from midnight Thursday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnThursday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getThursdayStartTimes () {
    	$val = $this->cacheGetPlain('ThursdayStartTimes');
    	if (is_null($val))
    		return $this->cacheSetPlain('ThursdayStartTimes', $this->getOffering()->getThursdayStartTimes());
    	else
    		return $val;
	}
    
    /**
     * Answer time the meeting ends on Thursday
     * 
     * @return array An array of end-times whose order matches those returned by getThursdayStartTimes()
	 *		Times are  in seconds from midnight Thursday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnThursday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getThursdayEndTimes () {
    	$val = $this->cacheGetPlain('ThursdayEndTimes');
    	if (is_null($val))
    		return $this->cacheSetPlain('ThursdayEndTimes', $this->getOffering()->getThursdayEndTimes());
    	else
    		return $val;
	}
    
    /**
     * Answer true if this CourseOffering meets on Friday
     * 
     * @return boolean
     * @access public
     * @compliance mandatory This method must be implemented. 
     */
    public function meetsOnFriday () {
    	$val = $this->cacheGetPlain('meetsOnFriday');
    	if (is_null($val))
    		return $this->cacheSetPlain('meetsOnFriday', $this->getOffering()->meetsOnFriday());
    	else
    		return $val;
	}
    
    /**
     * Answer time the meeting starts on Friday
     * 
     * @return array An array of start-times whose order matches those returned by getFridayEndTimes()
	 *		Times are  in seconds from midnight Friday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnFriday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getFridayStartTimes () {
    	$val = $this->cacheGetPlain('FridayStartTimes');
    	if (is_null($val))
    		return $this->cacheSetPlain('FridayStartTimes', $this->getOffering()->getFridayStartTimes());
    	else
    		return $val;
	}
    
    /**
     * Answer time the meeting ends on Friday
     * 
     * @return array An array of end-times whose order matches those returned by getFridayStartTimes()
	 *		Times are  in seconds from midnight Friday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnFriday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getFridayEndTimes () {
    	$val = $this->cacheGetPlain('FridayEndTimes');
    	if (is_null($val))
    		return $this->cacheSetPlain('FridayEndTimes', $this->getOffering()->getFridayEndTimes());
    	else
    		return $val;
	}
    
    /**
     * Answer true if this CourseOffering meets on Saturday
     * 
     * @return boolean
     * @access public
     * @compliance mandatory This method must be implemented. 
     */
    public function meetsOnSaturday () {
    	$val = $this->cacheGetPlain('meetsOnSaturday');
    	if (is_null($val))
    		return $this->cacheSetPlain('meetsOnSaturday', $this->getOffering()->meetsOnSaturday());
    	else
    		return $val;
	}
    
    /**
     * Answer time the meeting starts on Saturday
     * 
     * @return array An array of start-times whose order matches those returned by getSaturdayEndTimes()
	 *		Times are  in seconds from midnight Saturday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnSaturday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getSaturdayStartTimes () {
    	$val = $this->cacheGetPlain('SaturdayStartTimes');
    	if (is_null($val))
    		return $this->cacheSetPlain('SaturdayStartTimes', $this->getOffering()->getSaturdayStartTimes());
    	else
    		return $val;
	}
    
    /**
     * Answer time the meeting ends on Saturday
     * 
     * @return array An array of end-times whose order matches those returned by getSaturdayStartTimes()
	 *		Times are  in seconds from midnight Saturday morning.
     * @compliance mandatory This method must be implemented. 
     * @throws osid_IllegalStateException <code>meetsOnSaturday()</code> is <code>false</code> 
     * @access public
     * @since 6/10/09
     */
    public function getSaturdayEndTimes () {
    	$val = $this->cacheGetPlain('SaturdayEndTimes');
    	if (is_null($val))
    		return $this->cacheSetPlain('SaturdayEndTimes', $this->getOffering()->getSaturdayEndTimes());
    	else
    		return $val;
	}

}
