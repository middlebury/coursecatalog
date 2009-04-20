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
class banner_course_CourseOfferingsByTermList
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
	 * @return void
	 * @access public
	 * @since 4/13/09
	 */
	public function __construct (PDO $db, banner_course_CourseOfferingSessionInterface $session, osid_id_Id $catalogId, osid_id_Id $termId) {
		$this->termId = $termId;
		
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
		return array(	':term_code' => $this->session->getTermCodeFromTermId($this->termId));
	}
	
	/**
	 * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'
	 * 
	 * @return array
	 * @access protected
	 * @since 4/17/09
	 */
	protected function getWhereTerms() {
		return 'SSBSECT_TERM_CODE = :term_code';
	}
}

?>