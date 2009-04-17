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
class banner_course_AllCourseOfferingssList
	extends phpkit_PdoQueryList
	implements osid_course_CourseList
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
	public function __construct (PDO $db, banner_course_CourseOfferingSessionInterface $session, $idAuthority, $idPrefix) {
		$this->catalogDatabaseId = '';
		$this->idAuthority = $idAuthority;
		$this->idPrefix = $idPrefix;
		$this->session = $session
		
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
	section_coll_code IN (
		SELECT
			coll_code
		FROM
			course_catalog_college
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
		return array();
	}
}

?>