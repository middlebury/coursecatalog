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
class banner_course_CourseOffering_Lookup_ByTermByTopicList
	extends banner_course_CourseOffering_AbstractList
	implements osid_course_CourseOfferingList
{

	/**
	 * Constructor
	 * 
	 * @param PDO $db
	 * @param banner_course_CourseOffering_SessionInterface $session
	 * @param osid_id_Id $catalogDatabaseId
	 * @param osid_id_Id $termId
	 * @param osid_id_Id $topicId
	 * @return void
	 * @access public
	 * @since 4/13/09
	 */
	public function __construct (PDO $db, banner_course_CourseOffering_SessionInterface $session, osid_id_Id $catalogId, osid_id_Id $termId, osid_id_Id $topicId) {
		$this->termId = $termId;
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
		$params = array(':term_code' => $this->session->getTermCodeFromTermId($this->termId));
		switch ($type) {
			case 'subject':
   				$params[':subject_code'] = $value;
   				return $params;
   			case 'department':
   				$params[':department_code'] = $value;
   				return $params;
   			case 'division':
   				$params[':division_code'] = $value;
   				return $params;
			case 'requirement':
 				$params[':requirement_code'] = $value;
				return $params;
			case 'level':
 				$params[':level_code'] = $value;
				return $params;
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
				return 'SSBSECT_TERM_CODE = :term_code AND SSBSECT_SUBJ_CODE = :subject_code';
   			case 'department':
   				return 'SSBSECT_TERM_CODE = :term_code AND SCBCRSE_DEPT_CODE = :department_code';
   			case 'division':
   				return 'SSBSECT_TERM_CODE = :term_code AND SCBCRSE_DIVS_CODE = :division_code';
   			case 'requirement':
   				return 'SSBSECT_TERM_CODE = :term_code AND SSRATTR_ATTR_CODE = :requirement_code';
   			case 'level':
   				return 'SSBSECT_TERM_CODE = :term_code AND SCRLEVL_LEVL_CODE = :level_code';
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
			return 'LEFT JOIN SSRATTR ON (SSBSECT_TERM_CODE = SSRATTR_TERM_CODE AND SSBSECT_CRN = SSRATTR_CRN)';
		else if ('level' == $this->session->getTopicLookupSession()->getTopicType($this->topicId))
			return 'LEFT JOIN scrlevl_recent ON (SSBSECT_SUBJ_CODE = SCRLEVL_SUBJ_CODE AND SSBSECT_CRSE_NUMB = SCRLEVL_CRSE_NUMB)';
		else
			return '';
	}
}

?>