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
class banner_course_CourseOfferingsByTermForCourseList
	extends banner_course_AbstractCourseOfferingList
	implements osid_course_CourseOfferingList
{

	/**
	 * Constructor
	 * 
	 * @param PDO $db
	 * @param banner_course_CourseOfferingSessionInterface $session
	 * @param osid_id_Id $catalogDatabaseId
	 * @param osid_id_Id $termId
	 * @param osid_id_Id $courseId
	 * @return void
	 * @access public
	 * @since 4/13/09
	 */
	public function __construct (PDO $db, banner_course_CourseOfferingSessionInterface $session, osid_id_Id $catalogId, osid_id_Id $termId, osid_id_Id $courseId) {
		$this->termId = $termId;
		$this->courseId = $courseId;
		
		parent::__construct($db, $session, $catalogId);
	}
		
	/**
	 * Answer the input parameters
	 * 
	 * @return array
	 * @access protected
	 * @since 4/17/09
	 */
	protected function getInputParameters () {
		return array(	':subj_code' => $this->session->getSubjectFromCourseId($this->courseId),
						':crse_numb' => $this->session->getNumberFromCourseId($this->courseId),
						':term_code' => $this->session->getTermCodeFromTermId($this->termId));
	}
	
	/**
	 * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'
	 * 
	 * @return array
	 * @access protected
	 * @since 4/17/09
	 */
	protected function getWhereTerms() {
		return 'SSBSECT_SUBJ_CODE = :subj_code
	AND SSBSECT_CRSE_NUMB = :crse_numb
	AND SSBSECT_TERM_CODE = :term_code';
	}
}

?>