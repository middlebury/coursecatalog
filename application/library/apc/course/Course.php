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
    extends phpkit_AbstractOsidObject
    implements osid_course_Course
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
	public function __construct (osid_course_CourseLookupSession $session, osid_id_Id $id) {
		parent::__construct();
		
		$this->session = $session;
	
		$this->setId($id);
		$this->data = $this->fetchData();
		if (is_null($this->data)) {
			$this->data = $this->setData($this->getCourse());
		}
		
		$this->setDisplayName($this->data['displayName']);
		$this->setDescription($this->data['description']);
		foreach ($this->data['recordTypes'] as $type) {
			$this->addRecordType($type);
		}
	}
	private $data;
	private $course;
	private $session;
	
	/**
	 * Answer our internal course object
	 * 
	 * @return osid_course_Course
	 * @access private
	 * @since 8/10/10
	 */
	private function getCourse () {
		if (!isset($this->course))
			$this->course = $this->session->getCourse($this->getId());
		return $this->course;
	}
	
	/**
	 * Fetch data from cache
	 * 
	 * @return array or NULL if not found
	 * @access private
	 * @since 8/10/10
	 */
	private function fetchData () {
		$value = apc_fetch($this->hash('data'), $success);
		if (!$success)
			return null;
		return unserialize($value);
	}
	
	/**
	 * Set data from a course object
	 * 
	 * @param osid_course_Course $course
	 * @return void
	 * @access private
	 * @since 8/10/10
	 */
	private function setData (osid_course_Course $course) {
		$recordTypes = array();
		$types = $course->getRecordTypes();
		while($types->hasNext()) {
			$recordTypes[] = $types->getNextType();
		}
		$data = array(
			'displayName'	=> $course->getDisplayName(),
			'description'	=> $course->getDescription(),
			'title'			=> $course->getTitle(),
			'number'		=> $course->getNumber(),
			'credits	'	=> $course->getCredits(),
			'prereqInfo'	=> $course->getPrereqInfo(),
			'recordTypes'	=> $recordTypes,
		);
		apc_store($this->hash('data'), serialize($data));
		return $data;
	}
	
	/**
	 * Create a cache key
	 * 
	 * @param string $key
	 * @return string
	 * @access private
	 * @since 8/10/10
	 */
	private function hash ($key) {
		$id = $this->getId();
		return $id->getIdentifierNamespace().':'.$id->getAuthority().':'.$id->getIdentifier().'::'.$key;
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
    	return $this->data['title'];
    }


    /**
     *  Gets the course number which is a label generally used to indedx the 
     *  course in a catalog, such as T101 or 16.004. 
     *
     *  @return string the course number 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNumber() {
    	return $this->data['number'];
    }


    /**
     *  Gets the number of credits in this course. 
     *
     *  @return float the number of credits 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCredits() {
    	return $this->data['credits'];
    }


    /**
     *  Gets the an informational string for the course prerequisites. 
     *
     *  @return string the prerequisites 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPrereqInfo() {
    	return $this->data['prereqInfo'];
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
    	return $this->getCourse()->getTopicIds();
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
    	return $this->getCourse()->getTopics();
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
    	return $this->getCourse()->getCourseRecord($courseRecordType);
    }
}
