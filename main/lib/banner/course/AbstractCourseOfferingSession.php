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
abstract class banner_course_AbstractCourseOfferingSession
	extends banner_course_AbstractCourseSession
	implements banner_course_CourseOfferingSessionInterface 
{
	
	/**
	 * Answer the term code from an id object
	 * 
	 * @param osid_id_Id $id
	 * @return string
	 * @throws an osid_NotFoundException if the id cannot match.
	 * @access public
	 * @since 4/16/09
	 */
	public function getTermCodeFromOfferingId (osid_id_Id $id) {
		$string = $this->getDatabaseIdString($id, 'section/');
		if (!preg_match('#^([0-9]{6})/([0-9]{1,5})$#', $string, $matches))
			throw new osid_NotFoundException("String '$string' cannot be broken into a term-code and CRN.");
		return $matches[1];
	}
	
	/**
	 * Answer the CRN from an id object
	 * 
	 * @param osid_id_Id $id
	 * @return string
	 * @throws an osid_NotFoundException if the id cannot match.
	 * @access public
	 * @since 4/16/09
	 */
	public function getCrnFromOfferingId (osid_id_Id $id) {
		$string = $this->getDatabaseIdString($id, 'section/');
		if (!preg_match('#^([0-9]{6})/([0-9]{1,5})$#', $string, $matches))
			throw new osid_NotFoundException("String '$string' cannot be broken into a term-code and CRN.");
		return $matches[2];
	}
	
	/**
	 * Answer an id object from a CRN and term-code
	 * 
	 * @param string $termCode
	 * @param string $id
	 * @return osid_id_Id
	 * @access public
	 * @since 4/16/09
	 */
	public function getOfferingIdFromTermCodeAndCrn ($termCode, $crn) {
		if (!strlen($termCode) || !strlen($crn))
			throw new osid_OperationFailedException('Both termCode and CRN must be specified.');
		
		return $this->getOsidIdFromString($termCode.'/'.$crn, 'section/');
	}
	
	/**
	 * Answer an id object from a subject code and course number
	 * 
	 * @param string $subjectCode
	 * @param string $courseNumber
	 * @return osid_id_Id
	 * @access public
	 * @since 4/16/09
	 */
	public function getCourseIdFromSubjectAndNumber ($subjectCode, $number) {
		if (!strlen($subjectCode) || !strlen($number))
			throw new osid_OperationFailedException('Both subjectCode and number must be specified.');
		
		return $this->getOsidIdFromString($subjectCode.$number, 'course/');
	}
	
	/**
	 * Answer the id authority for this session
	 * 
	 * @return string
	 * @access public
	 * @since 4/16/09
	 */
	public function getIdAuthority () {
		return $this->manager->getIdAuthority();
	}
	
	/**
	 * Answer the course lookup session
	 * 
	 * @return osid_course_CourseLookupSession
	 * @access public
	 * @since 4/16/09
	 */
	public function getCourseLookupSession () {
		if (!isset($this->courseLookupSession))
			$this->courseLookupSession = $this->manager->getCourseLookupSessionForCatalog($this->getCourseCatalogId());
		
		return $this->courseLookupSession;
	}
	
	/**
	 * Answer a term lookup session
	 * 
	 * @return osid_course_TermLookupSession
	 * @access public
	 * @since 4/16/09
	 */
	public function getTermLookupSession () {
		if (!isset($this->termLookupSession))
// 			$this->termLookupSession = $this->manager->getTermLookupSessionForCatalog($this->getCourseCatalogId());
			$this->termLookupSession = $this->manager->getTermLookupSession();
		
		return $this->termLookupSession;
	}
	
	/**
	 * Answer a Resource lookup session
	 * 
	 * @return osid_resource_ResourceLookupSession
	 * @access public
	 * @since 4/16/09
	 */
	public function getResourceLookupSession () {
		if (!isset($this->resourceLookupSession))
			$this->resourceLookupSession = $this->manager->getResourceManager()->getResourceLookupSession();
		
		return $this->resourceLookupSession;
	}
	
	
}

?>