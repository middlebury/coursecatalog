<?php
/**
 * @since 4/13/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * An iterator for retrieving all courses from a catalog.
 *
 * @since 4/13/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class banner_course_CourseOffering_AbstractList extends banner_course_CachingPdoQueryList implements osid_course_CourseOfferingList
{
    protected $session;
    private $catalogId;

    /**
     * Constructor.
     *
     * @param optional osid_id_Id $catalogDatabaseId
     *
     * @return void
     *
     * @since 4/13/09
     */
    public function __construct(PDO $db, banner_course_CourseOffering_SessionInterface $session, ?osid_id_Id $catalogId = null)
    {
        $this->session = $session;
        $this->catalogId = $catalogId;

        parent::__construct($db, $this->getQuery(), $this->getAllInputParameters());
    }

    /**
     * Answer a debugging string.
     *
     * @return string
     *
     * @since 5/27/09
     */
    public function debug()
    {
        return "\n\n".static::class."\nQuery:\n".$this->getQuery()."\nParameters:\n".print_r($this->getAllInputParameters(), true);
    }

    /**
     * Answer the query.
     *
     * @return string
     *
     * @since 4/17/09
     */
    protected function getQuery()
    {
        return '
SELECT
	SSBSECT_TERM_CODE,
	SSBSECT_CRN,
	SSBSECT_SUBJ_CODE,
	SSBSECT_CRSE_NUMB,
	SSBSECT_SEQ_NUMB,
	SSBSECT_PTRM_CODE,
	SSBSECT_CRSE_TITLE,
	SSBSECT_MAX_ENRL,
	SSBSECT_ENRL,
	SSBSECT_SEATS_AVAIL,
	SSBSECT_LINK_IDENT,
	SSBDESC_TEXT_NARRATIVE,
	SCBCRSE_TITLE,
	SCBDESC_TEXT_NARRATIVE,
	GTVINSM_CODE,
	GTVINSM_DESC,
	SSBSECT_CAMP_CODE,
	term_display_label,
	STVTERM_START_DATE,
	STVSCHD_CODE,
	STVSCHD_DESC,
	SSRMEET_BLDG_CODE,
	SSRMEET_ROOM_CODE,
	SSRMEET_BEGIN_TIME,
	SSRMEET_END_TIME,
	GROUP_CONCAT(DISTINCT SSRMEET_SUN_DAY) as SSRMEET_SUN_DAY,
	GROUP_CONCAT(DISTINCT SSRMEET_MON_DAY) as SSRMEET_MON_DAY,
	GROUP_CONCAT(DISTINCT SSRMEET_TUE_DAY) as SSRMEET_TUE_DAY,
	GROUP_CONCAT(DISTINCT SSRMEET_WED_DAY) as SSRMEET_WED_DAY,
	GROUP_CONCAT(DISTINCT SSRMEET_THU_DAY) as SSRMEET_THU_DAY,
	GROUP_CONCAT(DISTINCT SSRMEET_FRI_DAY) as SSRMEET_FRI_DAY,
	GROUP_CONCAT(DISTINCT SSRMEET_SAT_DAY) as SSRMEET_SAT_DAY,
	SSRMEET_START_DATE,
	SSRMEET_END_DATE,
	COUNT(SSRMEET_TERM_CODE) as num_meet,
	STVBLDG_DESC,
	STVCAMP_DESC,
	SCBCRSE_EFF_TERM ,
	SCBCRSE_DEPT_CODE,
	SCBCRSE_DIVS_CODE'.$this->getAdditionalColumnsString().',
	SSRXLST_XLST_GROUP
FROM
	ssbsect_scbcrse_scbdesc
	INNER JOIN catalog_term ON SSBSECT_TERM_CODE = catalog_term.term_code
	INNER JOIN course_catalog_college ON course_catalog_college.coll_code = SCBCRSE_COLL_CODE
	INNER JOIN course_catalog ON course_catalog_college.catalog_id = course_catalog.catalog_id
	LEFT JOIN GTVINSM ON SSBSECT_INSM_CODE = GTVINSM_CODE
	LEFT JOIN STVTERM ON SSBSECT_TERM_CODE = STVTERM_CODE
	LEFT JOIN SSBDESC ON (SSBSECT_TERM_CODE = SSBDESC_TERM_CODE AND SSBSECT_CRN = SSBDESC_CRN)
	LEFT JOIN SSRMEET ON (SSBSECT_TERM_CODE = SSRMEET_TERM_CODE AND SSBSECT_CRN = SSRMEET_CRN)
	LEFT JOIN STVBLDG ON SSRMEET_BLDG_CODE = STVBLDG_CODE
	LEFT JOIN STVSCHD ON SSBSECT_SCHD_CODE = STVSCHD_CODE
	LEFT JOIN STVCAMP ON SSBSECT_CAMP_CODE = STVCAMP_CODE
	LEFT JOIN SSRXLST ON (SSBSECT_TERM_CODE = SSRXLST_TERM_CODE AND SSBSECT_CRN = SSRXLST_CRN)
	'.$this->getAdditionalTableJoins().'
WHERE
	'.$this->getAllWhereTerms().'
	AND SSBSECT_TERM_CODE IN (
		SELECT
			term_code
		FROM
			catalog_term
		WHERE
			'.$this->getCatalogWhereTerms().'
	)
	AND SCBCRSE_COLL_CODE IN (
		SELECT
			coll_code
		FROM
			course_catalog_college
		WHERE
			'.$this->getCatalogWhereTerms2()."
	)
	AND SSBSECT_SSTS_CODE = 'A'
	AND (course_catalog.prnt_ind_to_exclude IS NULL OR SSBSECT_PRNT_IND != course_catalog.prnt_ind_to_exclude)

GROUP BY SSBSECT_TERM_CODE, SSBSECT_CRN
HAVING ".$this->getAllHavingTerms().'
'.$this->getOrderByClause().'
'.$this->getLimitClause().'
';
    }

    /**
     * Answer a string to append to the column list of additional columns.
     *
     * @return string
     *
     * @since 6/10/09
     */
    protected function getAdditionalColumnsString()
    {
        $columns = $this->getAdditionalColumns();
        if (count($columns)) {
            return ",\n\t".implode(",\n\t", $columns);
        } else {
            return '';
        }
    }

    /**
     * Answer the input parameters.
     *
     * @return array
     *
     * @since 4/17/09
     */
    protected function getAllInputParameters()
    {
        $params = $this->getInputParameters();
        if (null !== $this->catalogId && !$this->catalogId->isEqual($this->session->getCombinedCatalogId())) {
            $params[':catalog_id'] = $this->session->getCatalogDatabaseId($this->catalogId);
            $params[':catalog_id2'] = $this->session->getCatalogDatabaseId($this->catalogId);
        }

        return $params;
    }

    /**
     * Answer a where clause.
     *
     * @return string
     *
     * @since 4/20/09
     */
    private function getAllWhereTerms()
    {
        $terms = $this->getWhereTerms();
        if (strlen(trim($terms))) {
            return $terms;
        } else {
            return 'TRUE';
        }
    }

    /**
     * Answer the catalog where terms.
     *
     * @return string
     *
     * @since 4/20/09
     */
    private function getCatalogWhereTerms()
    {
        if (null === $this->catalogId || $this->catalogId->isEqual($this->session->getCombinedCatalogId())) {
            return 'TRUE';
        } else {
            return 'catalog_id = :catalog_id';
        }
    }

    /**
     * Answer the catalog where terms.
     *
     * @return string
     *
     * @since 4/20/09
     */
    private function getCatalogWhereTerms2()
    {
        if (null === $this->catalogId || $this->catalogId->isEqual($this->session->getCombinedCatalogId())) {
            return 'TRUE';
        } else {
            return 'catalog_id = :catalog_id2';
        }
    }

    /**
     * Answer a HAVING clause.
     *
     * @return string
     *
     * @since 8/5/2024
     */
    private function getAllHavingTerms()
    {
        $terms = $this->getHavingTerms();
        if (strlen(trim($terms))) {
            return $terms;
        } else {
            return 'TRUE';
        }
    }

    /**
     * Answer any additional table join clauses to use.
     *
     * @return string
     *
     * @since 4/29/09
     */
    protected function getAdditionalTableJoins()
    {
        return '';
    }

    /**
     * Answer the ORDER BY clause to use.
     *
     * @return string
     *
     * @since 5/28/09
     */
    protected function getOrderByClause()
    {
        return 'ORDER BY SSBSECT_TERM_CODE DESC, SSBSECT_SUBJ_CODE, SSBSECT_CRSE_NUMB, SSBSECT_PTRM_CODE, SSBSECT_SEQ_NUMB';
    }

    /**
     * Answer the LIMIT clause to use.
     *
     * Override this method in child classes to add functionality.
     *
     * @return string
     *
     * @since 5/28/09
     */
    protected function getLimitClause()
    {
        return '';
    }

    /**
     * Answer an array of additional columns to return.
     *
     * Override this method in child classes to add functionality.
     *
     * @return array
     *
     * @since 6/10/09
     */
    protected function getAdditionalColumns()
    {
        return [];
    }

    /**
     * Answer the input parameters.
     *
     * @return array
     *
     * @since 4/17/09
     */
    abstract protected function getInputParameters();

    /**
     * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'.
     *
     * @return array
     *
     * @since 4/17/09
     */
    abstract protected function getWhereTerms();

    /**
     * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'.
     *
     * @return array
     *
     * @since 4/17/09
     */
    protected function getHavingTerms()
    {
        return '';
    }

    /**
     * Answer an object from a result row.
     *
     * @since 4/13/09
     *
     * @return osid_course_CourseOffering
     *                                    The object from a row
     */
    final protected function getObjectFromRow(array $row)
    {
        return new banner_course_CourseOffering($row, $this->session);
    }

    /**
     *  Gets the next <code> CourseOffering </code> in this list.
     *
     * @return object osid_course_CourseOffering the next <code>
     *                CourseOffering </code> in this list. The <code> hasNext()
     *                </code> method should be used to test that a next <code>
     *                CourseOffering </code> is available before calling this
     *                method.
     *
     * @throws osid_IllegalStateException    no more elements available in this
     *                                       list or this list has been closed
     * @throws osid_OperationFailedException unable to complete request
     *
     *  @compliance mandatory This method must be implemented.
     */
    final public function getNextCourseOffering()
    {
        return $this->next();
    }

    /**
     *  Gets the next set of <code> CourseOffering </code> elements in this
     *  list. The specified amount must be less than or equal to the return
     *  from <code> available(). </code>.
     *
     * @param int $n the number of <code> CourseOffering </code> elements
     *               requested which must be less than or equal to <code>
     *               available() </code>
     *
     * @return array of osid_course_CourseOffering objects  an array of
     *               <code> CourseOffering </code> elements. <code> </code> The
     *               length of the array is less than or equal to the number
     *               specified.
     *
     * @throws osid_IllegalStateException    no more elements available in this
     *                                       list or this list has been closed
     * @throws osid_OperationFailedException unable to complete request
     * @throws osid_NullArgumentException    null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    final public function getNextCourseOfferings($n)
    {
        return $this->getNext($n);
    }
}
