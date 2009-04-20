<?php
/**
 * @since 4/13/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * An iterator for retrieving all courses from a catalog
 * 
 * @since 4/13/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class banner_course_CourseOfferingsForCourseList
	extends phpkit_PdoQueryList
	implements osid_course_CourseOfferingList
{

	/**
	 * Constructor
	 * 
	 * @param PDO $db
	 * @param string $catalogDatabaseId
	 * @param banner_course_CourseOfferingSessionInterface $session
	 * @return void
	 * @access public
	 * @since 4/13/09
	 */
	public function __construct (PDO $db, $catalogDatabaseId, banner_course_CourseOfferingSessionInterface $session, osid_id_Id $courseId) {
		$this->catalogDatabaseId = $catalogDatabaseId;
		$this->session = $session;
		$this->courseId = $courseId;
		
		parent::__construct($db, $this->getQuery(), $this->getInputParameters());
	}
	
	/**
	 * Answer the query
	 * 
	 * @return string
	 * @access protected
	 * @since 4/17/09
	 */
	protected function getQuery () {
		return "
SELECT 
    section_coll_code,
	SSBSECT_TERM_CODE,
	SSBSECT_CRN,
	SSBSECT_SUBJ_CODE,
	SSBSECT_CRSE_NUMB,
	SSBSECT_SEQ_NUMB,
	SSBSECT_CAMP_CODE,
	STVTERM_TRMT_CODE,
	STVTERM_START_DATE,
	SSRMEET_BLDG_CODE,
	SSRMEET_ROOM_CODE,
	SSRMEET_BEGIN_TIME,
	SSRMEET_END_TIME,
	SSRMEET_SUN_DAY,
	SSRMEET_MON_DAY,
	SSRMEET_TUE_DAY,
	SSRMEET_WED_DAY,
	SSRMEET_THU_DAY,
	SSRMEET_FRI_DAY,
	SSRMEET_SAT_DAY,
	STVBLDG_DESC
FROM 
	course_section_college
	INNER JOIN ssbsect ON (section_term_code = SSBSECT_TERM_CODE AND section_crn = SSBSECT_CRN)
	LEFT JOIN stvterm ON SSBSECT_TERM_CODE = STVTERM_CODE
	LEFT JOIN ssrmeet ON (SSBSECT_TERM_CODE = SSRMEET_TERM_CODE AND SSBSECT_CRN = SSRMEET_CRN)
	LEFT JOIN stvbldg ON SSRMEET_BLDG_CODE = STVBLDG_CODE
WHERE 
	SSBSECT_SUBJ_CODE = :subj_code
	AND SSBSECT_CRSE_NUMB = :crse_numb
	AND section_coll_code IN (
		SELECT
			coll_code
		FROM
			course_catalog_college
		WHERE
			catalog_id = :catalog_id
	)

GROUP BY SSBSECT_TERM_CODE, SSBSECT_CRN
";
	}
	
	/**
	 * Answer the input parameters
	 * 
	 * @return array
	 * @access protected
	 * @since 4/17/09
	 */
	protected function getInputParameters () {
		return array(	':catalog_id' => $this->catalogDatabaseId,
						':subj_code' => $this->session->getSubjectFromCourseId($this->courseId),
						':crse_numb' => $this->session->getNumberFromCourseId($this->courseId));
	}
		
	/**
	 * Answer an object from a result row
	 * 
	 * @param array $row
	 * @return mixed
	 * @access protected
	 * @since 4/13/09
	 */
	protected function getObjectFromRow (array $row) {
		return new banner_course_CourseOffering($row, $this->session);
	}
	
	/**
     *  Gets the next <code> CourseOffering </code> in this list. 
     *
     *  @return object osid_course_CourseOffering the next <code> 
     *          CourseOffering </code> in this list. The <code> hasNext() 
     *          </code> method should be used to test that a next <code> 
     *          CourseOffering </code> is available before calling this 
     *          method. 
     *  @throws osid_IllegalStateException no more elements available in this 
     *          list or this list has been closed 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNextCourseOffering() {
    	return $this->next();
    }


    /**
     *  Gets the next set of <code> CourseOffering </code> elements in this 
     *  list. The specified amount must be less than or equal to the return 
     *  from <code> available(). </code> 
     *
     *  @param integer $n the number of <code> CourseOffering </code> elements 
     *          requested which must be less than or equal to <code> 
     *          available() </code> 
     *  @return array of osid_course_CourseOffering objects  an array of 
     *          <code> CourseOffering </code> elements. <code> </code> The 
     *          length of the array is less than or equal to the number 
     *          specified. 
     *  @throws osid_IllegalStateException no more elements available in this 
     *          list or this list has been closed 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNextCourseOfferings($n) {
    	return $this->getNext($n);
    }   
}

?>