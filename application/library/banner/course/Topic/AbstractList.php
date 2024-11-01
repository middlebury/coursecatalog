<?php
/**
 * @since 4/13/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * An iterator for retrieving all topics from a catalog.
 *
 * @since 4/13/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class banner_course_Topic_AbstractList extends banner_course_CachingPdoQueryList implements osid_course_TopicList
{
    protected $session;
    private $catalogId;

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
    public function __construct(PDO $db, banner_course_SessionInterface $session, ?osid_id_Id $catalogId = null)
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
        $subqueries = [];
        if ($this->includeRequirements()) {
            $subqueries[] = "
(SELECT
	'requirement' AS type,
	STVATTR_CODE AS id,
	STVATTR_DESC AS display_name
FROM
	course_catalog_college
	INNER JOIN course_catalog ON course_catalog_college.catalog_id = course_catalog.catalog_id
	INNER JOIN ssbsect_scbcrse ON course_catalog_college.coll_code = SCBCRSE_COLL_CODE
	INNER JOIN SSRATTR ON (SSBSECT_TERM_CODE = SSRATTR_TERM_CODE AND SSBSECT_CRN = SSRATTR_CRN)
	INNER JOIN STVATTR ON SSRATTR_ATTR_CODE = STVATTR_CODE
WHERE
	".$this->getAllRequirementWhereTerms().'
	AND '.$this->getCatalogWhereTerms('req')."
	AND SSBSECT_SSTS_CODE = 'A'
	AND (course_catalog.prnt_ind_to_exclude IS NULL OR SSBSECT_PRNT_IND != course_catalog.prnt_ind_to_exclude)
GROUP BY STVATTR_CODE)
";
        }

        if ($this->includeLevels()) {
            $subqueries[] = "
(SELECT
	'level' AS type,
	STVLEVL_CODE AS id,
	STVLEVL_DESC AS display_name
FROM
	course_catalog_college
	INNER JOIN course_catalog ON course_catalog_college.catalog_id = course_catalog.catalog_id
	INNER JOIN ssbsect_scbcrse ON course_catalog_college.coll_code = SCBCRSE_COLL_CODE
	INNER JOIN scrlevl_recent ON (SSBSECT_SUBJ_CODE = SCRLEVL_SUBJ_CODE AND SSBSECT_CRSE_NUMB = SCRLEVL_CRSE_NUMB)
	INNER JOIN STVLEVL ON SCRLEVL_LEVL_CODE = STVLEVL_CODE
WHERE
	".$this->getAllLevelWhereTerms().'
	AND '.$this->getCatalogWhereTerms('level')."
	AND SSBSECT_SSTS_CODE = 'A'
	AND (course_catalog.prnt_ind_to_exclude IS NULL OR SSBSECT_PRNT_IND != course_catalog.prnt_ind_to_exclude)
GROUP BY STVLEVL_CODE)
";
        }

        if ($this->includeBlocks()) {
            $subqueries[] = "
(SELECT
	'block' AS type,
	STVBLCK_CODE AS id,
	STVBLCK_DESC AS display_name
FROM
	course_catalog_college
	INNER JOIN course_catalog ON course_catalog_college.catalog_id = course_catalog.catalog_id
	INNER JOIN ssbsect_scbcrse ON course_catalog_college.coll_code = SCBCRSE_COLL_CODE
	INNER JOIN SSRBLCK ON (SSBSECT_TERM_CODE = SSRBLCK_TERM_CODE AND SSBSECT_CRN = SSRBLCK_CRN)
	INNER JOIN STVBLCK ON SSRBLCK_BLCK_CODE = STVBLCK_CODE
WHERE
	".$this->getAllBlockWhereTerms().'
	AND '.$this->getCatalogWhereTerms('block')."
	AND SSBSECT_SSTS_CODE = 'A'
	AND (course_catalog.prnt_ind_to_exclude IS NULL OR SSBSECT_PRNT_IND != course_catalog.prnt_ind_to_exclude)
GROUP BY STVBLCK_CODE)
";
        }

        if ($this->includeInstructionMethods()) {
            $subqueries[] = "
(SELECT
	'instruction_method' AS type,
	GTVINSM_CODE AS id,
	GTVINSM_DESC AS display_name
FROM
	course_catalog_college
	INNER JOIN course_catalog ON course_catalog_college.catalog_id = course_catalog.catalog_id
	INNER JOIN ssbsect_scbcrse ON course_catalog_college.coll_code = SCBCRSE_COLL_CODE
	INNER JOIN GTVINSM ON SSBSECT_INSM_CODE = GTVINSM_CODE
WHERE
	".$this->getAllInstructionMethodWhereTerms().'
	AND '.$this->getCatalogWhereTerms('instruction_method')."
	AND SSBSECT_SSTS_CODE = 'A'
	AND (course_catalog.prnt_ind_to_exclude IS NULL OR SSBSECT_PRNT_IND != course_catalog.prnt_ind_to_exclude)
GROUP BY GTVINSM_CODE)
";
        }

        if ($this->includeDivisions()) {
            $subqueries[] = "
(SELECT
	'division' AS type,
	STVDIVS_CODE AS id,
	STVDIVS_DESC AS display_name
FROM
	course_catalog_college
	INNER JOIN course_catalog ON course_catalog_college.catalog_id = course_catalog.catalog_id
	INNER JOIN ssbsect_scbcrse ON course_catalog_college.coll_code = SCBCRSE_COLL_CODE
	INNER JOIN STVDIVS ON SCBCRSE_DIVS_CODE = STVDIVS_CODE
WHERE
	".$this->getAllDivisionWhereTerms().'
	AND '.$this->getCatalogWhereTerms('div')."
	AND SSBSECT_SSTS_CODE = 'A'
	AND (course_catalog.prnt_ind_to_exclude IS NULL OR SSBSECT_PRNT_IND != course_catalog.prnt_ind_to_exclude)
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
	course_catalog_college
	INNER JOIN course_catalog ON course_catalog_college.catalog_id = course_catalog.catalog_id
	INNER JOIN ssbsect_scbcrse ON course_catalog_college.coll_code = SCBCRSE_COLL_CODE
	INNER JOIN STVDEPT ON SCBCRSE_DEPT_CODE = STVDEPT_CODE
WHERE
	".$this->getAllDepartmentWhereTerms().'
	AND '.$this->getCatalogWhereTerms('dep')."
	AND SSBSECT_SSTS_CODE = 'A'
	AND (course_catalog.prnt_ind_to_exclude IS NULL OR SSBSECT_PRNT_IND != course_catalog.prnt_ind_to_exclude)
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
	course_catalog_college
	INNER JOIN course_catalog ON course_catalog_college.catalog_id = course_catalog.catalog_id
	INNER JOIN ssbsect_scbcrse ON course_catalog_college.coll_code = SCBCRSE_COLL_CODE
	INNER JOIN STVSUBJ ON SCBCRSE_SUBJ_CODE = STVSUBJ_CODE
WHERE
	".$this->getAllSubjectWhereTerms().'
	AND '.$this->getCatalogWhereTerms('sub')."
	AND STVSUBJ_DISP_WEB_IND = 'Y'
	AND SSBSECT_SSTS_CODE = 'A'
	AND (course_catalog.prnt_ind_to_exclude IS NULL OR SSBSECT_PRNT_IND != course_catalog.prnt_ind_to_exclude)
GROUP BY SCBCRSE_SUBJ_CODE)
";
        }

        return implode("\nUNION\n", $subqueries)
            ."\n".$this->getOrderByClause()
            ."\n".$this->getLimitClause()
            ."\n";
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
            if ($this->includeRequirements()) {
                $params[':req_catalog_id'] = $this->session->getCatalogDatabaseId($this->catalogId);
            }
            if ($this->includeLevels()) {
                $params[':level_catalog_id'] = $this->session->getCatalogDatabaseId($this->catalogId);
            }
            if ($this->includeBlocks()) {
                $params[':block_catalog_id'] = $this->session->getCatalogDatabaseId($this->catalogId);
            }
            if ($this->includeInstructionMethods()) {
                $params[':instruction_method_catalog_id'] = $this->session->getCatalogDatabaseId($this->catalogId);
            }
            if ($this->includeDepartments()) {
                $params[':dep_catalog_id'] = $this->session->getCatalogDatabaseId($this->catalogId);
            }
            if ($this->includeDivisions()) {
                $params[':div_catalog_id'] = $this->session->getCatalogDatabaseId($this->catalogId);
            }
            if ($this->includeSubjects()) {
                $params[':sub_catalog_id'] = $this->session->getCatalogDatabaseId($this->catalogId);
            }
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
    private function getAllRequirementWhereTerms()
    {
        $terms = $this->getRequirementWhereTerms();
        if (strlen(trim($terms))) {
            return $terms;
        } else {
            return 'TRUE';
        }
    }

    /**
     * Answer a where clause.
     *
     * @return string
     *
     * @since 4/20/09
     */
    private function getAllLevelWhereTerms()
    {
        $terms = $this->getLevelWhereTerms();
        if (strlen(trim($terms))) {
            return $terms;
        } else {
            return 'TRUE';
        }
    }

    /**
     * Answer a where clause.
     *
     * @return string
     *
     * @since 4/20/09
     */
    private function getAllBlockWhereTerms()
    {
        $terms = $this->getBlockWhereTerms();
        if (strlen(trim($terms))) {
            return $terms;
        } else {
            return 'TRUE';
        }
    }

    /**
     * Answer a where clause.
     *
     * @return string
     *
     * @since 4/20/09
     */
    private function getAllInstructionMethodWhereTerms()
    {
        $terms = $this->getInstructionMethodWhereTerms();
        if (strlen(trim($terms))) {
            return $terms;
        } else {
            return 'TRUE';
        }
    }

    /**
     * Answer a where clause.
     *
     * @return string
     *
     * @since 4/20/09
     */
    private function getAllDivisionWhereTerms()
    {
        $terms = $this->getDivisionWhereTerms();
        if (strlen(trim($terms))) {
            return $terms;
        } else {
            return 'TRUE';
        }
    }

    /**
     * Answer a where clause.
     *
     * @return string
     *
     * @since 4/20/09
     */
    private function getAllDepartmentWhereTerms()
    {
        $terms = $this->getDepartmentWhereTerms();
        if (strlen(trim($terms))) {
            return $terms;
        } else {
            return 'TRUE';
        }
    }

    /**
     * Answer a where clause.
     *
     * @return string
     *
     * @since 4/20/09
     */
    private function getAllSubjectWhereTerms()
    {
        $terms = $this->getSubjectWhereTerms();
        if (strlen(trim($terms))) {
            return $terms;
        } else {
            return 'TRUE';
        }
    }

    /**
     * Answer the catalog where terms.
     *
     * @param string $prefix
     *
     * @return string
     *
     * @since 4/20/09
     */
    private function getCatalogWhereTerms($prefix)
    {
        if (null === $this->catalogId || $this->catalogId->isEqual($this->session->getCombinedCatalogId())) {
            return 'TRUE';
        } else {
            return 'course_catalog_college.catalog_id = :'.$prefix.'_catalog_id';
        }
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
        return '';
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
    abstract protected function getRequirementWhereTerms();

    /**
     * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'.
     *
     * @return array
     *
     * @since 4/17/09
     */
    abstract protected function getLevelWhereTerms();

    /**
     * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'.
     *
     * @return array
     *
     * @since 4/17/09
     */
    abstract protected function getBlockWhereTerms();

    /**
     * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'.
     *
     * @return array
     *
     * @since 4/17/09
     */
    abstract protected function getInstructionMethodWhereTerms();

    /**
     * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'.
     *
     * @return array
     *
     * @since 4/17/09
     */
    abstract protected function getDivisionWhereTerms();

    /**
     * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'.
     *
     * @return array
     *
     * @since 4/17/09
     */
    abstract protected function getDepartmentWhereTerms();

    /**
     * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'.
     *
     * @return array
     *
     * @since 4/17/09
     */
    abstract protected function getSubjectWhereTerms();

    /**
     * Answer true if requirement topics should be included.
     *
     * @return bool
     *
     * @since 6/12/09
     */
    abstract protected function includeRequirements();

    /**
     * Answer true if level topics should be included.
     *
     * @return bool
     *
     * @since 6/12/09
     */
    abstract protected function includeLevels();

    /**
     * Answer true if block topics should be included.
     *
     * @return bool
     *
     * @since 6/12/09
     */
    abstract protected function includeBlocks();

    /**
     * Answer true if instruction_method topics should be included.
     *
     * @return bool
     *
     * @since 6/12/09
     */
    abstract protected function includeInstructionMethods();

    /**
     * Answer true if division topics should be included.
     *
     * @return bool
     *
     * @since 6/12/09
     */
    abstract protected function includeDivisions();

    /**
     * Answer true if department topics should be included.
     *
     * @return bool
     *
     * @since 6/12/09
     */
    abstract protected function includeDepartments();

    /**
     * Answer true if subject topics should be included.
     *
     * @return bool
     *
     * @since 6/12/09
     */
    abstract protected function includeSubjects();

    /**
     * Answer an object from a result row.
     *
     * @since 4/13/09
     *
     * @return osid_course_Topic
     *                           An object for the row data
     */
    final protected function getObjectFromRow(array $row)
    {
        return new banner_course_Topic(
            $this->session->getOsidIdFromString($row['type'].'.'.$row['id'], 'topic.'),
            trim($row['display_name']),
            '',
            new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:topic.'.$row['type'])
        );
    }

    /**
     *  Gets the next <code> Topic </code> in this list.
     *
     * @return object osid_course_Topic the next <code> Topic </code> in this
     *                list. The <code> hasNext() </code> method should be used to
     *                test that a next <code> Topic </code> is available before
     *                calling this method.
     *
     * @throws osid_IllegalStateException    no more elements available in this
     *                                       list or this list has been closed
     * @throws osid_OperationFailedException unable to complete request
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getNextTopic()
    {
        return $this->next();
    }

    /**
     *  Gets the next set of <code> Topic </code> elements in this list. The
     *  specified amount must be less than or equal to the return from <code>
     *  available(). </code>.
     *
     * @param int $n the number of <code> Topic </code> elements
     *               requested which must be less than or equal to <code>
     *               available() </code>
     *
     * @return array of osid_course_Topic objects  an array of <code> Topic
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
    public function getNextTopics($n)
    {
        return $this->getNext($n);
    }
}
