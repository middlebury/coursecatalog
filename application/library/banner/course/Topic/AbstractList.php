<?php
/**
 * @since 4/13/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * An iterator for retrieving all topics from a catalog
 * 
 * @since 4/13/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class banner_course_Topic_AbstractList
	extends banner_course_CachingPdoQueryList
	implements osid_course_TopicList
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
	public function __construct (PDO $db, banner_course_SessionInterface $session, osid_id_Id $catalogId = null) {
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
	 * Answer the query
	 * 
	 * @return string
	 * @access protected
	 * @since 4/17/09
	 */
	protected function getQuery () {
		$subqueries = array();
		if ($this->includeRequirements()) {
			$subqueries[] = "
(SELECT 
	'requirement' AS type,
	STVATTR_CODE AS id,
	STVATTR_DESC AS display_name
FROM 
	catalog_term
	INNER JOIN ssbsect ON term_code = SSBSECT_TERM_CODE 
	INNER JOIN ssrattr ON (SSBSECT_TERM_CODE = SSRATTR_TERM_CODE AND SSBSECT_CRN = SSRATTR_CRN)
	INNER JOIN stvattr ON SSRATTR_ATTR_CODE = STVATTR_CODE
WHERE
	".$this->getAllRequirementWhereTerms()."
	AND ".$this->getCatalogWhereTerms('req')."
GROUP BY STVATTR_CODE)
";
		}
		
		if ($this->includeDivisions()) {
			$subqueries[] = "
(SELECT 
	'division' AS type,
	STVDIVS_CODE AS id,
	STVDIVS_DESC AS display_name
FROM 
	catalog_term
	INNER JOIN ssbsect ON term_code = SSBSECT_TERM_CODE  
	INNER JOIN scbcrse ON (SSBSECT_SUBJ_CODE = SCBCRSE_SUBJ_CODE AND SSBSECT_CRSE_NUMB = SCBCRSE_CRSE_NUMB)
	INNER JOIN stvdivs ON SCBCRSE_DIVS_CODE = STVDIVS_CODE
WHERE
	".$this->getAllDivisionWhereTerms()."
	AND ".$this->getCatalogWhereTerms('div')."
GROUP BY SCBCRSE_DIVS_CODE)
";
		}
		
		if ($this->includeDepartments()) {
			$subqueries[] = "
(SELECT 
	'department' AS type,
	STVDEPT_CODE AS id,
	STVDEPT_DESC AS display_name
FROM 
	catalog_term
	INNER JOIN ssbsect ON term_code = SSBSECT_TERM_CODE  
	INNER JOIN scbcrse ON (SSBSECT_SUBJ_CODE = SCBCRSE_SUBJ_CODE AND SSBSECT_CRSE_NUMB = SCBCRSE_CRSE_NUMB)
	INNER JOIN stvdept ON SCBCRSE_DEPT_CODE = STVDEPT_CODE
WHERE
	".$this->getAllDepartmentWhereTerms()."
	AND ".$this->getCatalogWhereTerms('dep')."
GROUP BY SCBCRSE_DEPT_CODE)
";
		}
	
		if ($this->includeSubjects()) {
			$subqueries[] = "
(SELECT 
	'subject' AS type,
	STVSUBJ_CODE AS id,
	STVSUBJ_DESC AS display_name
FROM 
	catalog_term
	INNER JOIN ssbsect ON term_code = SSBSECT_TERM_CODE  
	INNER JOIN scbcrse ON (SSBSECT_SUBJ_CODE = SCBCRSE_SUBJ_CODE AND SSBSECT_CRSE_NUMB = SCBCRSE_CRSE_NUMB)
	INNER JOIN stvsubj ON SCBCRSE_SUBJ_CODE = STVSUBJ_CODE
WHERE
	".$this->getAllSubjectWhereTerms()."
	AND ".$this->getCatalogWhereTerms('sub')."
	AND STVSUBJ_DISP_WEB_IND = 'Y'
GROUP BY SCBCRSE_SUBJ_CODE)
";
		}
	
		return implode("\nUNION\n", $subqueries)
			."\n".$this->getOrderByClause()
			."\n".$this->getLimitClause()
			."\n";
	}
	
	/**
	 * Answer the input parameters
	 * 
	 * @return array
	 * @access protected
	 * @since 4/17/09
	 */
	protected function getAllInputParameters () {
		$params = $this->getInputParameters();
		if (!is_null($this->catalogId) && !$this->catalogId->isEqual($this->session->getCombinedCatalogId())) {
			if ($this->includeRequirements())
				$params[':req_catalog_id'] = $this->session->getCatalogDatabaseId($this->catalogId);
			if ($this->includeDepartments())
				$params[':dep_catalog_id'] = $this->session->getCatalogDatabaseId($this->catalogId);
			if ($this->includeDivisions())
				$params[':div_catalog_id'] = $this->session->getCatalogDatabaseId($this->catalogId);
			if ($this->includeSubjects())
				$params[':sub_catalog_id'] = $this->session->getCatalogDatabaseId($this->catalogId);
		}
		return $params;
	}
	
	/**
	 * Answer a where clause
	 * 
	 * @return string
	 * @access private
	 * @since 4/20/09
	 */
	private function getAllRequirementWhereTerms () {
		$terms = $this->getRequirementWhereTerms();
		if (strlen(trim($terms)))
			return $terms;
		else
			return 'TRUE';
	}
	
	/**
	 * Answer a where clause
	 * 
	 * @return string
	 * @access private
	 * @since 4/20/09
	 */
	private function getAllDivisionWhereTerms () {
		$terms = $this->getDivisionWhereTerms();
		if (strlen(trim($terms)))
			return $terms;
		else
			return 'TRUE';
	}
	
	/**
	 * Answer a where clause
	 * 
	 * @return string
	 * @access private
	 * @since 4/20/09
	 */
	private function getAllDepartmentWhereTerms () {
		$terms = $this->getDepartmentWhereTerms();
		if (strlen(trim($terms)))
			return $terms;
		else
			return 'TRUE';
	}
	
	/**
	 * Answer a where clause
	 * 
	 * @return string
	 * @access private
	 * @since 4/20/09
	 */
	private function getAllSubjectWhereTerms () {
		$terms = $this->getSubjectWhereTerms();
		if (strlen(trim($terms)))
			return $terms;
		else
			return 'TRUE';
	}
	
	/**
	 * Answer the catalog where terms
	 * 
	 * @param string $prefix
	 * @return string
	 * @access private
	 * @since 4/20/09
	 */
	private function getCatalogWhereTerms ($prefix) {
		if (is_null($this->catalogId) || $this->catalogId->isEqual($this->session->getCombinedCatalogId()))
			return 'TRUE';
		else
			return 'catalog_id = :'.$prefix.'_catalog_id';
	}
	
	/**
	 * Answer the ORDER BY clause to use
	 * 
	 * @return string
	 * @access protected
	 * @since 5/28/09
	 */
	protected function getOrderByClause () {
		return '';
	}
	
	/**
	 * Answer the LIMIT clause to use
	 * 
	 * Override this method in child classes to add functionality.
	 * 
	 * @return string
	 * @access protected
	 * @since 5/28/09
	 */
	protected function getLimitClause () {
		return '';
	}
	
	/**
	 * Answer an array of additional columns to return.
	 *
	 * Override this method in child classes to add functionality.
	 * 
	 * @return array
	 * @access protected
	 * @since 6/10/09
	 */
	protected function getAdditionalColumns () {
		return array();
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
	abstract protected function getRequirementWhereTerms();
	
	/**
	 * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'
	 * 
	 * @return array
	 * @access protected
	 * @since 4/17/09
	 */
	abstract protected function getDivisionWhereTerms();
	
	/**
	 * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'
	 * 
	 * @return array
	 * @access protected
	 * @since 4/17/09
	 */
	abstract protected function getDepartmentWhereTerms();
	
	/**
	 * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'
	 * 
	 * @return array
	 * @access protected
	 * @since 4/17/09
	 */
	abstract protected function getSubjectWhereTerms();
	
	/**
	 * Answer true if requirement topics should be included
	 * 
	 * @return boolean
	 * @access protected
	 * @since 6/12/09
	 */
	abstract protected function includeRequirements ();
	
	/**
	 * Answer true if division topics should be included
	 * 
	 * @return boolean
	 * @access protected
	 * @since 6/12/09
	 */
	abstract protected function includeDivisions ();
	
	/**
	 * Answer true if department topics should be included
	 * 
	 * @return boolean
	 * @access protected
	 * @since 6/12/09
	 */
	abstract protected function includeDepartments ();
	
	/**
	 * Answer true if subject topics should be included
	 * 
	 * @return boolean
	 * @access protected
	 * @since 6/12/09
	 */
	abstract protected function includeSubjects ();
		
	/**
	 * Answer an object from a result row
	 * 
	 * @param array $row
	 * @return mixed
	 * @access protected
	 * @since 4/13/09
	 */
	final protected function getObjectFromRow (array $row) {
		return new banner_course_Topic(
			$this->session->getOsidIdForString($row['type'].'/'.$row['id'], 'topic/'),
			$row['display_name'],
			'',
			new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic/'.$row['type'])
		);
	}
	
	    /**
     *  Gets the next <code> Topic </code> in this list. 
     *
     *  @return object osid_course_Topic the next <code> Topic </code> in this 
     *          list. The <code> hasNext() </code> method should be used to 
     *          test that a next <code> Topic </code> is available before 
     *          calling this method. 
     *  @throws osid_IllegalStateException no more elements available in this 
     *          list or this list has been closed 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNextTopic() {
    	return $this->next();
    }


    /**
     *  Gets the next set of <code> Topic </code> elements in this list. The 
     *  specified amount must be less than or equal to the return from <code> 
     *  available(). </code> 
     *
     *  @param integer $n the number of <code> Topic </code> elements 
     *          requested which must be less than or equal to <code> 
     *          available() </code> 
     *  @return array of osid_course_Topic objects  an array of <code> Topic 
     *          </code> elements. <code> </code> The length of the array is 
     *          less than or equal to the number specified. 
     *  @throws osid_IllegalStateException no more elements available in this 
     *          list or this list has been closed 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNextTopics($n) {
    	return $this->getNext($n);
    }   
}

?>