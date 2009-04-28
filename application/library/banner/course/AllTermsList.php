<?php
/**
 * @since 4/13/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * An iterator for retrieving all terms
 * 
 * @since 4/13/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class banner_course_AllTermsList
	extends banner_course_CachingPdoQueryList
	implements osid_course_TermList
{

	/**
	 * Constructor
	 * 
	 * @param PDO $db
	 * @param banner_course_CourseOfferingSessionInterface $session
	 * @return void
	 * @access public
	 * @since 4/13/09
	 */
	public function __construct (PDO $db, banner_course_CourseOfferingSessionInterface $session) {
		$query =
"SELECT 
    section_coll_code,
    STVTERM_CODE,
	STVTERM_DESC,
	STVTERM_START_DATE,
	STVTERM_END_DATE
FROM 
	course_section_college
	INNER JOIN stvterm ON section_term_code = STVTERM_CODE
	
WHERE 
	section_coll_code IN (
		SELECT
			coll_code
		FROM
			course_catalog_college
		WHERE
			".$this->getCatalogWhereTerms()."
	)
GROUP BY section_term_code
ORDER BY STVTERM_CODE DESC
";
		parent::__construct($db, $query, array());
		$this->idAuthority = $idAuthority;
		$this->idPrefix = $idPrefix;
	}
	
	/**
	 * Answer the catalog where terms
	 * 
	 * @return string
	 * @access private
	 * @since 4/20/09
	 */
	private function getCatalogWhereTerms () {
		if ($this->session->getCourseCatalogId()->isEqual($this->session->getCombinedCatalogId()))
			return 'TRUE';
		else
			return 'catalog_id = :catalog_id';
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
		return new banner_course_Term(
					$this->session->getOsidIdFromString($row['STVTERM_CODE'], 'term/')
					$row['STVTERM_DESC'],
					$row['STVTERM_START_DATE'], 
					$row['STVTERM_END_DATE']);
	}
	
	/**
     *  Gets the next <code> Term </code> in this list. 
     *
     *  @return object osid_course_Term the next <code> Term </code> in this 
     *          list. The <code> hasNext() </code> method should be used to 
     *          test that a next <code> Term </code> is available before 
     *          calling this method. 
     *  @throws osid_IllegalStateException no more elements available in this 
     *          list or this list has been closed 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNextTerm() {
    	return $this->next();
    }


    /**
     *  Gets the next set of <code> Term </code> elements in this list. The 
     *  specified amount must be less than or equal to the return from <code> 
     *  available(). </code> 
     *
     *  @param integer $n the number of <code> Term </code> elements requested 
     *          which must be less than or equal to <code> available() </code> 
     *  @return array of osid_course_Term objects  an array of <code> Term 
     *          </code> elements. <code> </code> The length of the array is 
     *          less than or equal to the number specified. 
     *  @throws osid_IllegalStateException no more elements available in this 
     *          list or this list has been closed 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNextTerms($n) {
    	return $this->getNext($n);
    }
    
}

?>