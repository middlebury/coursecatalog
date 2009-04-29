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
class banner_course_CourseOfferingsByTopicList
	extends banner_course_AbstractCourseOfferingList
	implements osid_course_CourseOfferingList
{

	/**
	 * Constructor
	 * 
	 * @param PDO $db
	 * @param banner_course_CourseOfferingSessionInterface $session
	 * @param osid_id_Id $catalogDatabaseId
	 * @param osid_id_Id $topicId
	 * @return void
	 * @access public
	 * @since 4/13/09
	 */
	public function __construct (PDO $db, banner_course_CourseOfferingSessionInterface $session, osid_id_Id $catalogId, osid_id_Id $topicId) {
		$this->topicId = $topicId;
		
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
		$type = $this->session->getTopicLookupSession()->getTopicType($this->topicId);
		$value = $this->session->getTopicLookupSession()->getTopicValue($this->topicId);
		switch ($type) {
			case 'subject':
   				return array(':subject_code' => $value);
   			case 'department':
   				return array(':department_code' => $value);
   			case 'division':
   				return array(':division_code' => $value);
   			case 'requirement':
   				return array(':requirement_code' => $value);
   			default:
   				throw new osid_NotFoundException('No topic found with category '.$type);
		}
	}
	
	/**
	 * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'
	 * 
	 * @return array
	 * @access protected
	 * @since 4/17/09
	 */
	protected function getWhereTerms() {
		$type = $this->session->getTopicLookupSession()->getTopicType($this->topicId);
		switch ($type) {
			case 'subject':
				return 'SSBSECT_SUBJ_CODE = :subject_code';
   			case 'department':
   				return 'SCBCRSE_DEPT_CODE = :department_code';
   			case 'division':
   				return 'SCBCRSE_DIVS_CODE = :division_code';
   			case 'requirement':
   				return 'SSRATTR_ATTR_CODE = :requirement_code';
   			default:
   				throw new osid_NotFoundException('No topic found with category '.$type);
		}
	}
	
	/**
	 * Answer any additional table join clauses to use
	 * 
	 * @return string
	 * @access protected
	 * @since 4/29/09
	 */
	protected function getAdditionalTableJoins () {
		if ('requirement' == $this->session->getTopicLookupSession()->getTopicType($this->topicId))
			return 'LEFT JOIN ssrattr ON (SSBSECT_TERM_CODE = SSRATTR_TERM_CODE AND SSBSECT_CRN = SSRATTR_CRN)';
		else
			return '';
	}
}

?>