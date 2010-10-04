<?php
/**
 * 
 * @copyright Copyright &copy; 2010, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 *  <p>A <code> Course </code> represents a canonical learning unit. A <code> 
 *  Course </code> is instantiated at a time and place through the creation of 
 *  a <code> CourseOffering. </code> </p>
 * 
 * 
 * @copyright Copyright &copy; 2010, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class apc_course_Course 
    extends apc_Cachable
    implements osid_course_Course, 
    middlebury_course_Course_TermsRecord,
    middlebury_course_Course_AlternatesRecord,
    middlebury_course_Course_LinkRecord
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
	public function __construct (apc_course_Course_Lookup_Session $apcSession, osid_course_CourseLookupSession $session, osid_id_Id $id) {
		parent::__construct($id->getIdentifierNamespace().':'.$id->getAuthority().':'.$id->getIdentifier());
		
		$this->apcSession = $apcSession;
		$this->session = $session;
		$this->id = $id;
		
		$this->localRecordTypes = array(
			new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:terms'),
			new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:alternates'),
			new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:link'),
		);
	}
	private $course;
	private $session;
	private $apcSession;
	private $id;
	private $localRecordTypes;
	
	/**
	 * Answer our internal course object
	 * 
	 * @return osid_course_Course
	 * @access private
	 * @since 8/10/10
	 */
	private function getMyCourse () {
		if (!isset($this->course))
			$this->course = $this->session->getCourse($this->getId());
		return $this->course;
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
    		return $this->cacheSetPlain('displayName', $this->getMyCourse()->getDisplayName());
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
    		return $this->cacheSetPlain('description', $this->getMyCourse()->getDescription());
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
    		$types = $this->getMyCourse()->getRecordTypes();
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
    		return $this->cacheSetObj('genusType', $this->getMyCourse()->getGenusType());
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
    	return $this->getMyCourse()->getProperties();
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
		
    	return $this->getMyCourse()->getPropertiesByRecordType($recordType);
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
	 * osic_course_Course methods
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
    		return $this->cacheSetPlain('title', $this->getMyCourse()->getTitle());
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
    		return $this->cacheSetPlain('number', $this->getMyCourse()->getNumber());
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
    		return $this->cacheSetPlain('credits', $this->getMyCourse()->getCredits());
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
    		return $this->cacheSetPlain('prerequisites', $this->getMyCourse()->getPrereqInfo());
    	else
    		return $val;
    }
    
    /**
     *  WARNING: This method was not in the OSID trunk as of 2009-04-27. A 
     *  ticket requesting the addition of this method is available at: 
     *  http://oki.assembla.com/spaces/osid-dev/tickets/18-osid-course---No-way-to-map-Topics-to-Courses-or-CourseOfferings- 
     *  Gets a list of the <code> Id </code> s of the <code> Topic </code> s 
     *  this course is associated with. 
     *
     *  @return object osid_id_IdList the <code> Topic </code> <code> Id 
     *          </code> s 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopicIds() {
    	$val = $this->cacheGetObj('topic_ids');
    	if (is_null($val)) {
    		$val = array();
    		$ids = $this->getMyCourse()->getTopicIds();
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
     *  Gets the <code> Topic </code> s this course is associated with. 
     *
     *  @return object osid_course_TopicList the topics 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopics() {
    	return $this->getMyCourse()->getTopics();
    }


    /**
     *  Gets the record corresponding to the given <code> Course </code> 
     *  record <code> Type. </code> This method must be used to retrieve an 
     *  object implementing the requested record interface along with all of 
     *  its ancestor interfaces. The <code> courseRecordType </code> may be 
     *  the <code> Type </code> returned in <code> getRecordTypes() </code> or 
     *  any of its parents in a <code> Type </code> hierarchy where <code> 
     *  hasRecordType(courseRecordType) </code> is <code> true </code> . 
     *
     *  @param object osid_type_Type $courseRecordType the type of course 
     *          record to retrieve 
     *  @return object osid_course_CourseRecord the course record 
     *  @throws osid_NullArgumentException <code> courseRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(courseRecordType) </code> is <code> false 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseRecord(osid_type_Type $courseRecordType) {
    	if ($this->implementsRecordType($courseRecordType))
    		return $this;
    	
    	return $this->getMyCourse()->getCourseRecord($courseRecordType);
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
    
/*********************************************************
 * Methods from osid_course_CourseRecord
 *********************************************************/

	/**
     *  Gets the <code> Course </code> from which this record originated. 
     *
     *  @return object osid_course_Course the course 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourse() {
    	return $this;
    }
    
/*********************************************************
 * Methods from middlebury_course_Course_TermsRecord
 *********************************************************/

	/**
     * Gets the Ids of the Terms in which a <code>Course Offering</code> has been 
     * taught for a <code> Course. </code>
     *
     *  @return object osid_id_IdList the list of term ids.
     *  @compliance mandatory This method must be implemented. 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     */
    public function getTermIds() {
    	$val = $this->cacheGetObj('term_ids');
    	if (is_null($val)) {
    		$val = array();
    		$ids = $this->getMyCourse()->getTermIds();
    		while ($ids->hasNext()) {
    			$val[] = $ids->getNextId();
    		}
    		$this->cacheSetObj('term_ids', $val);
    	}
    	return new phpkit_id_ArrayIdList($val);
    }
    
    /**
     * Gets the <code> Terms </code> in which a <code>Course Offering</code> has 
     * been taught for a <code> Course. </code>
     *
     *  @return object osid_course_TermList the list of terms.
     *  @compliance mandatory This method must be implemented. 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     */
    public function getTerms() {
    	return $this->apcSession->getTermLookupSession()->getTermsByIds($this->getTermIds());
    }
    
/*********************************************************
 * Methods from middlebury_course_Course_AlternatesRecord
 *********************************************************/

    
    /**
	 * Tests if this course has any alternate courses.
	 * 
	 * @return boolean <code> true </code> if this course has any
     *          alternates, <code> false </code> otherwise 
	 * @access public
     * @compliance mandatory This method must be implemented. 
	 */
	public function hasAlternates () {
		$val = $this->cacheGetPlain('hasAlternates');
    	if (is_null($val))
    		return $this->cacheSetPlain('hasAlternates', $this->getMyCourse()->hasAlternates());
    	else
    		return $val;
	}

    /**
     *  Gets the Ids of any alternate courses
     *
     *  @return object osid_id_IdList the list of alternate ids.
     *  @compliance mandatory This method must be implemented. 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     */
    public function getAlternateIds() {
		$val = $this->cacheGetObj('alternate_ids');
    	if (is_null($val)) {
    		$val = array();
    		$ids = $this->getMyCourse()->getAlternateIds();
    		while ($ids->hasNext()) {
    			$val[] = $ids->getNextId();
    		}
    		$this->cacheSetObj('alternate_ids', $val);
    	}
    	return new phpkit_id_ArrayIdList($val);
	}
    
    /**
     *  Gets the alternate <code> Courses </code>.
     *
     *  @return object osid_course_CourseList The list of alternates.
     *  @compliance mandatory This method must be implemented. 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     */
    public function getAlternates() {
		$lookupSession = $this->apcSession->getCourseLookupSession();
		$lookupSession->useComparativeCourseView();
    	return $lookupSession->getCoursesByIds($this->getAlternateIds());
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
    		return $this->cacheSetPlain('isPrimary', $this->getMyCourse()->isPrimary());
    	else
    		return $val;
	}
	
/*********************************************************
 * 	Methods from middlebury_course_Course_LinkRecord
 *********************************************************/
	/**
	 * Answer the link-set ids for the offerings of this course in the term specified.
	 * 
	 * The offerings of a course in a term will be grouped into one or more link sets
	 * (set 1, set 2, set 3, etc).
	 * Each offering also has a link type (such as lecture, discussion, lab, etc).
	 *
	 * When registering for a Course that has multiple Offerings (such as lecture + lab or 
	 * lectures at different times), students must choose a link set and then one offering
	 * of each type within that set.
	 * 
	 * 
	 * @param osid_id_Id $termId
	 * @return osid_id_IdList
	 * @access public
	 * @since 8/3/10
	 */
	public function getLinkSetIdsForTerm (osid_id_Id $termId) {
		$cacheKey = 'link_set_ids::'
			.$termId->getIdentifierNamespace().'::'
			.$termId->getAuthority().'::'
			.$termId->getIdentifier();
		
		$val = $this->cacheGetObj($cacheKey);
    	if (is_null($val)) {
    		$val = array();
    		$ids = $this->getMyCourse()->getLinkSetIdsForTerm($termId);
    		while ($ids->hasNext()) {
    			$val[] = $ids->getNextId();
    		}
    		$this->cacheSetObj($cacheKey, $val);
    	}
    	return new phpkit_id_ArrayIdList($val);
	}

	/**
	 * Answer the link-type ids for the offerings of this course in the term specified.
	 *
	 * The offerings of a course in a term will be grouped into one or more link sets
	 * (set 1, set 2, set 3, etc).
	 * Each offering also has a link type (such as lecture, discussion, lab, etc).
	 *
	 * When registering for a Course that has multiple Offerings (such as lecture + lab or
	 * lectures at different times), students must choose a link set and then one offering
	 * of each type within that set.
	 *
	 *
	 * @param osid_id_Id $termId
	 * @param osid_id_Id $linkSetId
	 * @return osid_id_IdList
	 * @access public
	 * @since 8/3/10
	 */
	public function getLinkTypeIdsForTermAndSet (osid_id_Id $termId, osid_id_Id $linkSetId) {
		$cacheKey = 'link_type_ids::'
			.$termId->getIdentifierNamespace().'::'
			.$termId->getAuthority().'::'
			.$termId->getIdentifier().'::'
			.$linkSetId->getIdentifierNamespace().'::'
			.$linkSetId->getAuthority().'::'
			.$linkSetId->getIdentifier();

		$val = $this->cacheGetObj($cacheKey);
    	if (is_null($val)) {
    		$val = array();
    		$ids = $this->getMyCourse()->getLinkTypeIdsForTermAndSet($termId, $linkSetId);
    		while ($ids->hasNext()) {
    			$val[] = $ids->getNextId();
    		}
    		$this->cacheSetObj($cacheKey, $val);
    	}
    	return new phpkit_id_ArrayIdList($val);
	}

}
