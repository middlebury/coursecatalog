<?php
/**
 * @since 4/14/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 *  <p>A <code> Course </code> represents a canonical learning unit. A <code> 
 *  Course </code> is instantiated at a time and place through the creation of 
 *  a <code> CourseOffering. </code> </p>
 * 
 * @since 4/14/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class banner_course_Course 
    extends phpkit_AbstractOsidObject
    implements osid_course_Course
{


	/**
	 * Constructor
	 * 
	 * @param osid_id_Id $id
	 * @param string $displayName
	 * @return void
	 * @access public
	 * @since 4/13/09
	 */
	public function __construct (osid_id_Id $id, $displayName, $description, $title, $credits) {
		parent::__construct();
		$this->setId($id);
		$this->setDisplayName($displayName);
		$this->setDescription($description);
		$this->title = $title;
		$this->credits = floatval($credits);
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
    	return $this->title;
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
    	return $this->credits;
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
    	throw new osid_UnsupportedException('Course record type is not supported.');
    }

	
}
