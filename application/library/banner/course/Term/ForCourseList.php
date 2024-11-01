<?php
/**
 * @since 4/13/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * An iterator for retrieving all terms.
 *
 * @since 4/13/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class banner_course_Term_ForCourseList extends banner_course_CachingPdoQueryList implements osid_course_TermList, osid_id_IdList
{
    /**
     * Constructor.
     *
     * @param banner_course_CourseOffering_SessionInterface $session
     *
     * @return void
     *
     * @since 4/13/09
     */
    public function __construct(PDO $db, banner_course_AbstractSession $session, osid_id_Id $courseId)
    {
        $this->session = $session;
        $this->courseId = $courseId;

        $query =
'SELECT
	STVTERM_CODE,
	STVTERM_DESC,
	STVTERM_START_DATE,
	STVTERM_END_DATE
FROM
	STVTERM
WHERE
	STVTERM_CODE IN (
		SELECT
			term_code
		FROM
			catalog_term
		WHERE
			'.$this->getCatalogWhereTerms()."
	)
	AND STVTERM_CODE IN (
		SELECT
			SSBSECT_TERM_CODE
		FROM
			SSBSECT
			INNER JOIN catalog_term ON SSBSECT_TERM_CODE = catalog_term.term_code
			INNER JOIN course_catalog ON catalog_term.catalog_id = course_catalog.catalog_id
		WHERE
			SSBSECT_SUBJ_CODE = :subj_code
			AND SSBSECT_CRSE_NUMB = :crse_numb
			AND SSBSECT_SSTS_CODE = 'A'
			AND (course_catalog.prnt_ind_to_exclude IS NULL OR SSBSECT_PRNT_IND != course_catalog.prnt_ind_to_exclude)
		GROUP BY SSBSECT_TERM_CODE
	)
GROUP BY STVTERM_CODE
ORDER BY STVTERM_CODE ASC
";
        // var_dump($query);
        parent::__construct($db, $query, $this->getAllInputParameters());
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
        if ($this->session->getCourseCatalogId()->isEqual($this->session->getCombinedCatalogId())) {
            return 'TRUE';
        } else {
            return 'catalog_id = :catalog_id';
        }
    }

    /**
     * Answer the input parameters.
     *
     * @return array
     *
     * @since 4/17/09
     */
    private function getAllInputParameters()
    {
        // 		$params = $this->getInputParameters();
        $params = [];
        $catalogId = $this->session->getCourseCatalogId();
        if (!$catalogId->isEqual($this->session->getCombinedCatalogId())) {
            $params[':catalog_id'] = $this->session->getCatalogDatabaseId($catalogId);
        }

        $params[':subj_code'] = $this->session->getSubjectFromCourseId($this->courseId);
        $params[':crse_numb'] = $this->session->getNumberFromCourseId($this->courseId);

        return $params;
    }

    /**
     * Answer an object from a result row.
     *
     * @since 4/13/09
     *
     * @return osid_course_Term
     *                          An object for the row data
     */
    protected function getObjectFromRow(array $row)
    {
        return new banner_course_Term(
            $this->session->getOsidIdFromString($row['STVTERM_CODE'], 'term.'),
            $row['STVTERM_DESC'],
            $row['STVTERM_START_DATE'],
            $row['STVTERM_END_DATE']);
    }

    /**
     *  Gets the next <code> Term </code> in this list.
     *
     * @return object osid_course_Term the next <code> Term </code> in this
     *                list. The <code> hasNext() </code> method should be used to
     *                test that a next <code> Term </code> is available before
     *                calling this method.
     *
     * @throws osid_IllegalStateException    no more elements available in this
     *                                       list or this list has been closed
     * @throws osid_OperationFailedException unable to complete request
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getNextTerm()
    {
        return $this->next();
    }

    /**
     *  Gets the next set of <code> Term </code> elements in this list. The
     *  specified amount must be less than or equal to the return from <code>
     *  available(). </code>.
     *
     * @param int $n the number of <code> Term </code> elements requested
     *               which must be less than or equal to <code> available() </code>
     *
     * @return array of osid_course_Term objects  an array of <code> Term
     *               </code> elements. <code> </code> The length of the array is
     *               less than or equal to the number specified.
     *
     * @throws osid_IllegalStateException    no more elements available in this
     *                                       list or this list has been closed
     * @throws osid_OperationFailedException unable to complete request
     * @throws osid_NullArgumentException    null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getNextTerms($n)
    {
        return $this->getNext($n);
    }

    /**
     *  Gets the next <code> Id </code> in this list.
     *
     * @return object osid_id_Id the next <code> Id </code> in this list. The
     *                <code> hasNext() </code> method should be used to test that a
     *                next <code> Id </code> is available before calling this
     *                method.
     *
     * @throws osid_IllegalStateException    no more elements available in this
     *                                       list or this list has been closed
     * @throws osid_OperationFailedException unable to complete request
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getNextId()
    {
        return $this->getNextTerm()->getId();
    }

    /**
     *  Gets the next set of <code> Ids </code> in this list. The specified
     *  amount must be less than or equal to the return from <code>
     *  available(). </code>.
     *
     * @param int $n the number of <code> Id </code> elements requested
     *               which must be less than or equal to <code> available() </code>
     *
     * @return array of osid_id_Id objects  an array of <code> Id </code>
     *               elements. <code> </code> The length of the array is less than
     *               or equal to the number specified.
     *
     * @throws osid_IllegalStateException    no more elements available in this
     *                                       list or this list has been closed
     * @throws osid_OperationFailedException unable to complete request
     * @throws osid_NullArgumentException    null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getNextIds($n)
    {
        $ids = [];
        for ($i = 0; $i < $n; ++$i) {
            $ids[] = $this->getNextId();
        }

        return $ids;
    }
}
