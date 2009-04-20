<?php
/**
 * @since 4/16/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * This interface defines a few methods to allow course offering objects to get back to
 * other data from sessions such as terms and courses.
 * 
 * @since 4/16/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface banner_course_CourseOfferingSessionInterface {
		
	/**
	 * Answer the term code from an id object
	 * 
	 * @param osid_id_Id $id
	 * @return string
	 * @throws an osid_NotFoundException if the id cannot match.
	 * @access public
	 * @since 4/16/09
	 */
	public function getTermCodeFromOfferingId (osid_id_Id $id);
	
	/**
	 * Answer the CRN from an id object
	 * 
	 * @param osid_id_Id $id
	 * @return string
	 * @throws an osid_NotFoundException if the id cannot match.
	 * @access public
	 * @since 4/16/09
	 */
	public function getCrnFromOfferingId (osid_id_Id $id);
	
	/**
	 * Answer an id object from a CRN
	 * 
	 * @param string $termCode
	 * @param string $id
	 * @return osid_id_Id
	 * @access public
	 * @since 4/16/09
	 */
	public function getOfferingIdFromTermCodeAndCrn ($termCode, $crn);
	
	/**
	 * Answer an id object from a subject code and course number
	 * 
	 * @param string $subjectCode
	 * @param string $courseNumber
	 * @return osid_id_Id
	 * @access public
	 * @since 4/16/09
	 */
	public function getCourseIdFromSubjectAndNumber ($subjectCode, $number);
	
	/**
	 * Answer a course subject code from an id.
	 * 
	 * @param osid_id_Id $id
	 * @return string
	 * @access public
	 * @since 4/17/09
	 */
	public function getSubjectFromCourseId (osid_id_Id $id);
	
	/**
	 * Answer a course number from an id.
	 * 
	 * @param osid_id_Id $id
	 * @return string
	 * @access public
	 * @since 4/17/09
	 */
	public function getNumberFromCourseId (osid_id_Id $id);
	
	/**
	 * Answer a term code from an id.
	 * 
	 * @param osid_id_Id $id
	 * @return string
	 * @access public
	 * @since 4/17/09
	 */
	public function getTermCodeFromTermId (osid_id_Id $id);
	
	/**
	 * Answer a catalog database id string.
	 * 
	 * @param osid_id_Id $id
	 * @return string
	 * @access public
	 * @since 4/20/09
	 */
	public function getCatalogDatabaseId (osid_id_Id $id);
	
	/**
	 * Answer the Id of the 'All'/'Combined' catalog.
	 * 
	 * @return osid_id_Id
	 * @access public
	 * @since 4/20/09
	 */
	public function getCombinedCatalogId ();
	
	/**
	 * Answer the id authority for this session
	 * 
	 * @return string
	 * @access public
	 * @since 4/16/09
	 */
	public function getIdAuthority ();
	
	/**
	 * Answer the course lookup session
	 * 
	 * @return osid_course_CourseLookupSession
	 * @access public
	 * @since 4/16/09
	 */
	public function getCourseLookupSession ();
	
	/**
	 * Answer a term lookup session
	 * 
	 * @return osid_course_TermLookupSession
	 * @access public
	 * @since 4/16/09
	 */
	public function getTermLookupSession ();
	
	/**
	 * Answer a resource lookup session
	 * 
	 * @return osid_resource_ResourceLookupSession
	 * @access public
	 * @since 4/16/09
	 */
	public function getResourceLookupSession ();
	
	
}

?>