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
	 * Answer a course subject code from an id.
	 * 
	 * @param osid_id_Id $id
	 * @return string
	 * @access public
	 * @since 4/17/09
	 */
	public function getSubjectFromCourseId (osid_id_Id $id) {
		$string = $this->getDatabaseIdString($id, 'course/');
		if (!preg_match('#^([A-Z]{4})([0-9]{4})$#', $string, $matches))
			throw new osid_NotFoundException("String '$string' cannot be broken into a subject-code and Number.");
		return $matches[1];
	}
	
	/**
	 * Answer a course number from an id.
	 * 
	 * @param osid_id_Id $id
	 * @return string
	 * @access public
	 * @since 4/17/09
	 */
	public function getNumberFromCourseId (osid_id_Id $id) {
		$string = $this->getDatabaseIdString($id, 'course/');
		if (!preg_match('#^([A-Z]{4})([0-9]{4})$#', $string, $matches))
			throw new osid_NotFoundException("String '$string' cannot be broken into a subject-code and Number.");
		return $matches[2];
	}
	
	/**
	 * Answer a term code from an id.
	 * 
	 * @param osid_id_Id $id
	 * @return string
	 * @access public
	 * @since 4/17/09
	 */
	public function getTermCodeFromTermId (osid_id_Id $id) {
		$string = $this->getDatabaseIdString($id, 'term/');
		if (!preg_match('#^([0-9]{6})$#', $string, $matches))
			throw new osid_NotFoundException("String '$string' cannot be converted into a valid term code.");
		return $matches[1];
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
		if (!isset($this->courseLookupSession)) {
			$this->courseLookupSession = $this->manager->getCourseLookupSessionForCatalog($this->getCourseCatalogId());
			$this->courseLookupSession->useFederatedCourseCatalogView();
		}
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
		if (!isset($this->termLookupSession)) {
			$this->termLookupSession = $this->manager->getTermLookupSessionForCatalog($this->getCourseCatalogId());
// 			$this->termLookupSession = $this->manager->getTermLookupSession();
			$this->termLookupSession->useFederatedCourseCatalogView();
		}
		
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
	
	/**
	 * Answer a list of instructors for the course offering id passed
	 * 
	 * @param osid_id_Id $offeringId
	 * @return osid_id_IdList
	 * @access public
	 * @since 4/30/09
	 */
	public function getInstructorIdsForOffering (osid_id_Id $offeringId) {
		$ids = array();
		foreach ($this->getInstructorDataForOffering($offeringId) as $row) {
			$ids[] = $this->getOsidIdFromString($row['SYVINST_PIDM'], 'people/');
		}
		return new phpkit_id_ArrayIdList($ids);
	}
	
	/**
	 * Answer a list of instructors for the course offering id passed
	 * 
	 * @param osid_id_Id $offeringId
	 * @return osid_resource_ResourceList
	 * @access public
	 * @since 4/30/09
	 */
	public function getInstructorsForOffering (osid_id_Id $offeringId) {
		$people = array();
		foreach ($this->getInstructorDataForOffering($offeringId) as $row) {
			$people[] = new banner_resource_PersonResource(
								$this->getOsidIdFromString($row['SYVINST_PIDM'], 'people/'),
								$row['SYVINST_LAST_NAME'],
								$row['SYVINST_FIRST_NAME']
							);
		}
		return new phpkit_resource_ArrayResourceList($people);
	}
	
	/**
	 * Answer the instructor data rows for an offering id
	 * 
	 * @param osid_id_Id $offeringId
	 * @return array
	 * @access private
	 * @since 5/1/09
	 */
	private function getInstructorDataForOffering (osid_id_Id $offeringId) {
		$stmt = $this->getInstructorsForOfferingStatment();
		$stmt->execute(array(
			':term_code' => $this->getTermCodeFromOfferingId($offeringId),
			':crn' => $this->getCrnFromOfferingId($offeringId)
		));
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	private static $instructorsForOffering_stmt;
	/**
	 * Answer the instructors statement
	 * 
	 * @return PDOStatement
	 * @access private
	 * @since 5/1/09
	 */
	private function getInstructorsForOfferingStatment () {
		if (!isset(self::$instructorsForOffering_stmt)) {
			$query = "
SELECT
	SYVINST_PIDM,
	SYVINST_LAST_NAME,
	SYVINST_FIRST_NAME
FROM
	syvinst
WHERE
	SYVINST_TERM_CODE = :term_code
	AND SYVINST_CRN = :crn
ORDER BY
	SYVINST_LAST_NAME, SYVINST_FIRST_NAME
";
			self::$instructorsForOffering_stmt = $this->manager->getDB()->prepare($query);
		}
		return self::$instructorsForOffering_stmt;
	}
	
}

?>