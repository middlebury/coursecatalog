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
abstract class banner_course_Course_AbstractList
	extends banner_course_CachingPdoQueryList
	implements osid_course_CourseList
{

	protected $session;
	private $catalogId;

	/**
	 * Constructor
	 * 
	 * @param PDO $db
	 * @param banner_course_CourseOffering_SessionInterface $session
	 * @param optional osid_id_Id $catalogDatabaseId
	 * @return void
	 * @access public
	 * @since 4/13/09
	 */
	public function __construct (PDO $db, banner_course_AbstractSession $session, osid_id_Id $catalogId = null) {
		$this->session = $session;
		$this->catalogId = $catalogId;
		
		parent::__construct($db, $this->getQuery(), $this->getAllInputParameters());
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
	crse.*,
	SCBDESC_TEXT_NARRATIVE
FROM
	(SELECT 
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
		SCBCRSE
	WHERE
		".$this->getAllWhereTerms()."
		AND SCBCRSE_CSTA_CODE NOT IN (
			'C', 'I', 'P', 'T', 'X'
		)
		AND SCBCRSE_COLL_CODE IN (
			SELECT
				coll_code
			FROM
				course_catalog_college
			WHERE
				".$this->getCatalogWhereTerms()."
		)
	
	GROUP BY SCBCRSE_SUBJ_CODE , SCBCRSE_CRSE_NUMB
	ORDER BY SCBCRSE_SUBJ_CODE ASC , SCBCRSE_CRSE_NUMB ASC
	) as crse
	LEFT JOIN SCBDESC ON (SCBCRSE_SUBJ_CODE = SCBDESC_SUBJ_CODE AND SCBCRSE_CRSE_NUMB = SCBDESC_CRSE_NUMB AND SCBCRSE_EFF_TERM >= SCBDESC_TERM_CODE_EFF AND (SCBDESC_TERM_CODE_END IS NULL OR SCBCRSE_EFF_TERM <= SCBDESC_TERM_CODE_END))
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
		return new banner_course_Course(
					$this->session->getOsidIdFromString($row['SCBCRSE_SUBJ_CODE'].$row['SCBCRSE_CRSE_NUMB'], 'course/'),
					$row['SCBCRSE_SUBJ_CODE'].$row['SCBCRSE_CRSE_NUMB'],
					((is_null($row['SCBDESC_TEXT_NARRATIVE']))?'':$row['SCBDESC_TEXT_NARRATIVE']),	// Description
					$row['SCBCRSE_TITLE'], 
					$row['SCBCRSE_CREDIT_HR_HIGH'],
					array(
						$this->session->getOsidIdFromString($row['SCBCRSE_SUBJ_CODE'], 'topic/subject/'),
						$this->session->getOsidIdFromString($row['SCBCRSE_DEPT_CODE'], 'topic/department/'),
						$this->session->getOsidIdFromString($row['SCBCRSE_DIVS_CODE'], 'topic/division/')
					),
					$this->session);
	}
	
	/**
     *  Gets the next <code> Course </code> in this list. 
     *
     *  @return object osid_course_Course the next <code> Course </code> in 
     *          this list. The <code> hasNext() </code> method should be used 
     *          to test that a next <code> Course </code> is available before 
     *          calling this method. 
     *  @throws osid_IllegalStateException no more elements available in this 
     *          list or this list has been closed 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNextCourse() {
    	return $this->next();
    }


    /**
     *  Gets the next set of <code> Course </code> elements in this list. The 
     *  specified amount must be less than or equal to the return from <code> 
     *  available(). </code> 
     *
     *  @param integer $n the number of <code> Course </code> elements 
     *          requested which must be less than or equal to <code> 
     *          available() </code> 
     *  @return array of osid_course_Course objects  an array of <code> Course 
     *          </code> elements. <code> </code> The length of the array is 
     *          less than or equal to the number specified. 
     *  @throws osid_IllegalStateException no more elements available in this 
     *          list or this list has been closed 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNextCourses($n) {
    	return $this->getNext($n);
    }
}

?>