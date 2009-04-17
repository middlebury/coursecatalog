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
class banner_course_CombinedAllCoursesList
	extends banner_course_AllCoursesList
	implements osid_course_CourseList
{

	/**
	 * Constructor
	 * 
	 * @param PDO $db
	 * @param string $catalogDatabaseId
	 * @return void
	 * @access public
	 * @since 4/13/09
	 */
	public function __construct (PDO $db, $idAuthority, $idPrefix) {
		parent::__construct($db, '', $idAuthority, $idPrefix);
	}

	/**
	 * Answer the query
	 * 
	 * @return string
	 * @access protected
	 * @since 4/17/09
	 */
	protected function getQuery () {
		return "SELECT 
	SCBCRSE_SUBJ_CODE , 
	SCBCRSE_CRSE_NUMB , 
	MAX( SCBCRSE_EFF_TERM ) AS SCBCRSE_EFF_TERM , 
	SCBCRSE_COLL_CODE , 
	SCBCRSE_DIVS_CODE , 
	SCBCRSE_DEPT_CODE , 
	SCBCRSE_CSTA_CODE , 
	SCBCRSE_TITLE ,
	SCBCRSE_CREDIT_HR_HIGH
FROM 
	scbcrse
WHERE 
	SCBCRSE_CSTA_CODE NOT IN (
		'C', 'I', 'P', 'T', 'X'
	)
	AND SCBCRSE_COLL_CODE IN (
		SELECT
			coll_code
		FROM
			course_catalog_college
	)
GROUP BY SCBCRSE_SUBJ_CODE , SCBCRSE_CRSE_NUMB
ORDER BY SCBCRSE_SUBJ_CODE ASC , SCBCRSE_CRSE_NUMB ASC	
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