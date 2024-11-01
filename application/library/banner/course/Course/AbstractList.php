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
abstract class banner_course_Course_AbstractList extends banner_course_CachingPdoQueryList implements osid_course_CourseList
{
    protected $session;
    private $catalogId;
    private $activeOnly = true;

    /**
     * Constructor.
     *
     * @param banner_course_CourseOffering_SessionInterface $session
     * @param optional osid_id_Id $catalogDatabaseId
     *
     * @return void
     *
     * @since 4/13/09
     */
    public function __construct(PDO $db, banner_course_AbstractSession $session, ?osid_id_Id $catalogId = null)
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
	SCBCRSE_SUBJ_CODE ,
	SCBCRSE_CRSE_NUMB ,
	SCBCRSE_EFF_TERM ,
	SCBCRSE_COLL_CODE ,
	SCBCRSE_DIVS_CODE ,
	SCBCRSE_DEPT_CODE ,
	SCBCRSE_CSTA_CODE ,
	SCBCRSE_TITLE ,
	SCBCRSE_CREDIT_HR_HIGH,
	SCBDESC_TEXT_NARRATIVE,
	has_alternates
	'.$this->getAdditionalColumnsString().'
FROM
	scbcrse_scbdesc_recent
	'.$this->getAdditionalTableJoins().'
WHERE
	'.$this->getAllWhereTerms().'
	AND SCBCRSE_COLL_CODE IN (
		SELECT
			coll_code
		FROM
			course_catalog_college
		WHERE
			'.$this->getCatalogWhereTerms().'
	)
GROUP BY SCBCRSE_SUBJ_CODE , SCBCRSE_CRSE_NUMB
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
    protected function getAllWhereTerms()
    {
        $terms = $this->getWhereTerms();
        if ($this->activeOnly) {
            $activeWhere = " AND SCBCRSE_CSTA_CODE NOT IN (
		'C', 'I', 'P', 'T', 'X'
	)";
        } else {
            $activeWhere = '';
        }
        if (strlen(trim($terms))) {
            return $terms.$activeWhere;
        } else {
            return 'TRUE'.$activeWhere;
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
        return 'ORDER BY SCBCRSE_SUBJ_CODE , SCBCRSE_CRSE_NUMB';
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
     * Include inactive courses in the list.
     *
     * @return void
     */
    protected function includeInactive()
    {
        $this->activeOnly = false;
    }

    /**
     * Answer an object from a result row.
     *
     * @since 4/13/09
     *
     * @return osid_course_Course
     *                            The object from a row
     */
    final protected function getObjectFromRow(array $row)
    {
        return new banner_course_Course(
            $this->session->getOsidIdFromString($row['SCBCRSE_SUBJ_CODE'].$row['SCBCRSE_CRSE_NUMB'], 'course.'),
            $row['SCBCRSE_SUBJ_CODE'].' '.$row['SCBCRSE_CRSE_NUMB'],
            (null === $row['SCBDESC_TEXT_NARRATIVE']) ? '' : $row['SCBDESC_TEXT_NARRATIVE'],	// Description
            $row['SCBCRSE_TITLE'],
            $row['SCBCRSE_CREDIT_HR_HIGH'],
            [
                $this->session->getOsidIdFromString($row['SCBCRSE_SUBJ_CODE'], 'topic.subject.'),
                $this->session->getOsidIdFromString($row['SCBCRSE_DEPT_CODE'], 'topic.department.'),
                $this->session->getOsidIdFromString($row['SCBCRSE_DIVS_CODE'], 'topic.division.'),
            ],
            $row['has_alternates'],
            $this->session);
    }

    /**
     *  Gets the next <code> Course </code> in this list.
     *
     * @return object osid_course_Course the next <code> Course </code> in
     *                this list. The <code> hasNext() </code> method should be used
     *                to test that a next <code> Course </code> is available before
     *                calling this method.
     *
     * @throws osid_IllegalStateException    no more elements available in this
     *                                       list or this list has been closed
     * @throws osid_OperationFailedException unable to complete request
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getNextCourse()
    {
        return $this->next();
    }

    /**
     *  Gets the next set of <code> Course </code> elements in this list. The
     *  specified amount must be less than or equal to the return from <code>
     *  available(). </code>.
     *
     * @param int $n the number of <code> Course </code> elements
     *               requested which must be less than or equal to <code>
     *               available() </code>
     *
     * @return array of osid_course_Course objects  an array of <code> Course
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
    public function getNextCourses($n)
    {
        return $this->getNext($n);
    }
}
