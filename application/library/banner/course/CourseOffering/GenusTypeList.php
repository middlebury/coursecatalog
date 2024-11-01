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
class banner_course_CourseOffering_GenusTypeList extends banner_course_CachingPdoQueryList implements osid_type_TypeList
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
    public function __construct(PDO $db, banner_course_AbstractSession $session)
    {
        $this->session = $session;

        $query =
'SELECT
	STVSCHD_CODE,
	STVSCHD_DESC
FROM
	ssbsect_scbcrse
	LEFT JOIN STVSCHD ON SSBSECT_SCHD_CODE = STVSCHD_CODE
WHERE
	SSBSECT_TERM_CODE IN (
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
			'.$this->getCatalogWhereTerms2().'
	)
GROUP BY STVSCHD_CODE
ORDER BY STVSCHD_DESC ASC
';
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
     * Answer the catalog where terms.
     *
     * @return string
     *
     * @since 4/20/09
     */
    private function getCatalogWhereTerms2()
    {
        if ($this->session->getCourseCatalogId()->isEqual($this->session->getCombinedCatalogId())) {
            return 'TRUE';
        } else {
            return 'catalog_id = :catalog_id2';
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
            $params[':catalog_id2'] = $this->session->getCatalogDatabaseId($catalogId);
        }

        return $params;
    }

    /**
     * Answer an object from a result row.
     *
     * @since 4/13/09
     *
     * @return osid_type_Type
     *                        An object for the row data
     */
    protected function getObjectFromRow(array $row)
    {
        return new phpkit_type_Type(
            'urn', 										// namespace
            $this->session->getIdAuthority(), 			// id authority
            'genera:offering.'.$row['STVSCHD_CODE'], 	// identifier
            'Course Offerings', 						// domain
            $row['STVSCHD_DESC'], 						// display name
            $row['STVSCHD_CODE']						// display label
        );
    }

    /**
     *  Gets the next <code> Type </code> in this list.
     *
     * @return object osid_type_Type the next <code> Type </code> in this
     *                list. The <code> hasNext() </code> method should be used to
     *                test that a next <code> Type </code> is available before
     *                calling this method.
     *
     * @throws osid_IllegalStateException    no more elements available in this
     *                                       list or this list has been closed
     * @throws osid_OperationFailedException unable to complete request
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getNextType()
    {
        return $this->next();
    }

    /**
     *  Gets the next set of <code> Types </code> in this list. The specified
     *  amount must be less than or equal to the return from <code>
     *  available(). </code>.
     *
     * @param int $n the number of <code> Type </code> elements requested
     *               which must be less than or equal to <code> available() </code>
     *
     * @return array of osid_type_Type objects  an array of <code> Type
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
    public function getNextTypes($n)
    {
        return $this->getNext($n);
    }
}
