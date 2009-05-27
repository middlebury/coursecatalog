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
abstract class banner_course_AbstractCourseOfferingList
	extends banner_course_CachingPdoQueryList
	implements osid_course_CourseOfferingList
{

	protected $session;
	private $catalogId;

	/**
	 * Constructor
	 * 
	 * @param PDO $db
	 * @param banner_course_CourseOfferingSessionInterface $session
	 * @param optional osid_id_Id $catalogDatabaseId
	 * @return void
	 * @access public
	 * @since 4/13/09
	 */
	public function __construct (PDO $db, banner_course_CourseOfferingSessionInterface $session, osid_id_Id $catalogId = null) {
		$this->session = $session;
		$this->catalogId = $catalogId;
		
		parent::__construct($db, $this->getQuery(), $this->getAllInputParameters());
	}

 	/**
	 * Answer a debugging string.
	 * 
	 * @return string
	 * @access public
	 * @since 5/27/09
	 */
	public function debug () {
		return "\n\n".get_class($this)."\nQuery:\n".$this->getQuery()."\nParameters:\n".print_r($this->getAllInputParameters(), true);
	}

	/**
	 * Answer a debugging string.
	 * 
	 * @return string
	 * @access public
	 * @since 5/27/09
	 */
	public function debug () {
		return "\n\n".get_class($this)."\nQuery:\n".$this->getQuery()."\nParameters:\n".print_r($this->getAllInputParameters(), true);
	}
	
	/**
	 * Answer the query
	 * 
	 * @return string
	 * @access private
	 * @since 4/17/09
	 */
	private function getQuery () {
		return "
SELECT 
	SSBSECT_TERM_CODE,
	SSBSECT_CRN,
	SSBSECT_SUBJ_CODE,
	SSBSECT_CRSE_NUMB,
	SSBSECT_SEQ_NUMB,
	SSBSECT_CRSE_TITLE,
	SCBCRSE_TITLE,
	SSBSECT_CAMP_CODE,
	term_display_label,
	STVTERM_START_DATE,
	STVSCHD_CODE,
	STVSCHD_DESC,
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
	STVBLDG_DESC,
	MAX( SCBCRSE_EFF_TERM ) AS SCBCRSE_EFF_TERM , 
	SCBCRSE_DEPT_CODE,
	SCBCRSE_DIVS_CODE
FROM 
	ssbsect
	LEFT JOIN catalog_term ON SSBSECT_TERM_CODE = catalog_term.term_code
	LEFT JOIN stvterm ON SSBSECT_TERM_CODE = STVTERM_CODE
	LEFT JOIN ssrmeet ON (SSBSECT_TERM_CODE = SSRMEET_TERM_CODE AND SSBSECT_CRN = SSRMEET_CRN)
	LEFT JOIN stvbldg ON SSRMEET_BLDG_CODE = STVBLDG_CODE
	LEFT JOIN stvschd ON SSBSECT_SCHD_CODE = STVSCHD_CODE
	LEFT JOIN scbcrse ON (SSBSECT_SUBJ_CODE = SCBCRSE_SUBJ_CODE AND SSBSECT_CRSE_NUMB = SCBCRSE_CRSE_NUMB)
	".$this->getAdditionalTableJoins()."
WHERE 
	".$this->getAllWhereTerms()."
	AND SSBSECT_TERM_CODE IN (
		SELECT
			term_code
		FROM
			catalog_term
		WHERE
			".$this->getCatalogWhereTerms()."
	)

GROUP BY SSBSECT_TERM_CODE, SSBSECT_CRN
ORDER BY SSBSECT_TERM_CODE DESC, SSBSECT_SUBJ_CODE, SSBSECT_CRSE_NUMB, SSBSECT_SEQ_NUMB
";
	}
	
	/**
	 * Answer the input parameters
	 * 
	 * @return array
	 * @access private
	 * @since 4/17/09
	 */
	private function getAllInputParameters () {
		$params = $this->getInputParameters();
		if (!is_null($this->catalogId) && !$this->catalogId->isEqual($this->session->getCombinedCatalogId()))
			$params[':catalog_id'] = $this->session->getCatalogDatabaseId($this->catalogId);
		return $params;
	}
	
	/**
	 * Answer a where clause
	 * 
	 * @return string
	 * @access private
	 * @since 4/20/09
	 */
	private function getAllWhereTerms () {
		$terms = $this->getWhereTerms();
		if (strlen(trim($terms)))
			return $terms;
		else
			return 'TRUE';
	}
	
	/**
	 * Answer the catalog where terms
	 * 
	 * @return string
	 * @access private
	 * @since 4/20/09
	 */
	private function getCatalogWhereTerms () {
		if (is_null($this->catalogId) || $this->catalogId->isEqual($this->session->getCombinedCatalogId()))
			return 'TRUE';
		else
			return 'catalog_id = :catalog_id';
	}
	
	/**
	 * Answer any additional table join clauses to use
	 * 
	 * @return string
	 * @access protected
	 * @since 4/29/09
	 */
	protected function getAdditionalTableJoins () {
		return '';
	}
	
	/**
	 * Answer the input parameters
	 * 
	 * @return array
	 * @access protected
	 * @since 4/17/09
	 */
	abstract protected function getInputParameters ();
	
	/**
	 * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'
	 * 
	 * @return array
	 * @access protected
	 * @since 4/17/09
	 */
	abstract protected function getWhereTerms();
		
	/**
	 * Answer an object from a result row
	 * 
	 * @param array $row
	 * @return mixed
	 * @access protected
	 * @since 4/13/09
	 */
	final protected function getObjectFromRow (array $row) {
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
    final public function getNextCourseOffering() {
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
    final public function getNextCourseOfferings($n) {
    	return $this->getNext($n);
    }   
}

?>