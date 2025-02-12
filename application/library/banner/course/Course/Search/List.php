<?php

/**
 * @since 5/27/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * A List for retrieving sections based on search results.
 *
 * @since 5/27/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class banner_course_Course_Search_List extends banner_course_Course_AbstractList implements osid_course_CourseList, osid_course_CourseSearchResults
{
    private PDO $db;
    private osid_course_CourseQuery $courseQuery;
    private string $orderBy;
    private string $where;
    private ?string $limit;
    private array $additionalColumns;
    private array $additionalTableJoins;
    private array $parameters;

    /**
     * Constructor.
     *
     * @param osid_course_CourseQuery $courseQuery the search
     *                                             query
     *
     * @return void
     *
     * @since 4/13/09
     */
    public function __construct(PDO $db, banner_course_AbstractSession $session, osid_id_Id $catalogId, osid_course_CourseQuery $courseQuery, osid_course_CourseSearch $courseSearch)
    {
        $this->db = $db;
        $this->courseQuery = $courseQuery;

        // Set our parent list to search both inactive and active courses.
        // We'll rely on the query having a matchGenusType("urn:inet:middlebury.edu:status-active")
        // if we need to limit to just active courses.
        $this->includeInactive();

        $this->where = $courseQuery->getWhereClause();
        $searchWhere = $courseSearch->getWhereClause();
        if (strlen($searchWhere)) {
            $this->where .= "\n\tAND ".$searchWhere;
        }

        $this->additionalColumns = $courseQuery->getAdditionalColumns();

        $this->additionalTableJoins = array_unique(array_merge(
            $courseQuery->getAdditionalTableJoins(),
            $courseSearch->getAdditionalTableJoins()));

        $orderTerms = $courseSearch->getOrderByTerms();
        $orderTerms = array_merge($orderTerms, $courseQuery->getOrderByTerms());
        if (count($orderTerms)) {
            $this->orderBy = 'ORDER BY '.implode(', ', $orderTerms);
        } else {
            $this->orderBy = '';
        }
        $this->limit = $courseSearch->getLimitClause();

        $this->parameters = [];
        $parameters = array_merge($courseQuery->getParameters(), $courseSearch->getParameters());
        foreach ($parameters as $i => $val) {
            if (is_int($i)) {
                $name = ':c_search_'.$i;
                $this->parameters[$name] = $val;
                $this->where = preg_replace('/\?/', $name, $this->where, 1);
            } elseif (preg_match('/^:[a-z0-9_]+$/i', $i)) {
                $this->parameters[$i] = $val;
            } else {
                throw new osid_OperationFailedException("Invalid parameter name '$i'. Must be an integer or of the ':param_name' form.");
            }
        }

        parent::__construct($db, $session, $catalogId);
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
        return implode("\n\t", $this->additionalTableJoins);
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
        if (isset($this->additionalColumns)) {
            return $this->additionalColumns;
        } else {
            return parent::getAdditionalColumns();
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
        if ($this->orderBy) {
            return $this->orderBy;
        } else {
            return parent::getOrderByClause();
        }
    }

    /**
     * Answer the LIMIT clause to use.
     *
     * @return string
     *
     * @since 5/28/09
     */
    protected function getLimitClause()
    {
        if ($this->limit) {
            return $this->limit;
        } else {
            return parent::getLimitClause();
        }
    }

    /**
     * Answer the input parameters.
     *
     * @return array
     *
     * @since 4/17/09
     */
    protected function getInputParameters()
    {
        return $this->parameters;
    }

    /**
     * Answer additional where terms. E.g. 'SSRMEET_MON_DAY = true AND SSRMEET_TUE_DAY = false'.
     *
     * @return array
     *
     * @since 4/17/09
     */
    protected function getWhereTerms()
    {
        return $this->where;
    }

    /*********************************************************
     * Methods from osid_course_CourseSearchResults
     *********************************************************/

    /**
     *  Returns the size of a result set from a search query. This number
     *  serves as an estimate to provide feedback for refining search queries
     *  and may not be the number of elements available through an <code>
     *  OsidList. </code>.
     *
     * @return int the result size
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getResultSize()
    {
        if (!isset($this->resultSize)) {
            if ($this->limit) {
                $tmpLimit = $this->limit;
                $this->limit = null;

                $stmt = $this->db->prepare($this->getCountQuery($this->getQuery()));
                $stmt->execute($this->getAllInputParameters());
                $this->resultSize = (int) $stmt->fetchColumn();
                $stmt->closeCursor();

                $this->limit = $tmpLimit;
            } else {
                $this->resultSize = $this->available();
            }
        }

        return $this->resultSize;
    }

    /**
     *  Gets the search record types available in this search. A record <code>
     *  Type </code> explicitly indicates the specification of an interface to
     *  the record. A record may or may not inherit other record interfaces
     *  through interface inheritance in which case support of a record type
     *  may not be explicit in the returned list. Interoperability with the
     *  typed interface to this object should be performed through <code>
     *  hasSearchRecordType(). </code>.
     *
     * @return object osid_type_TypeList the search record types available
     *                through this object
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getSearchRecordTypes()
    {
        return new phpkit_EmptyList('osid_type_TypeList');
    }

    /**
     *  Tests if this search results supports the given record <code> Type.
     *  </code> The given record type may be supported by the object through
     *  interface/type inheritence. This method should be checked before
     *  retrieving the record interface.
     *
     *  @param object osid_type_Type $searchRecordType a type
     *
     * @return bool <code> true </code> if a search record the given
     *                     record <code> Type </code> is available, <code> false </code>
     *                     otherwise
     *
     * @throws osid_NullArgumentException <code> searchRecordType </code> is
     *                                           <code> null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function hasSearchRecordType(osid_type_Type $searchRecordType)
    {
        return false;
    }

    /**
     *  Gets a list of properties. Properties provide a means for applications
     *  to display a representation of the contents of a search record without
     *  understanding its <code> Type </code> specification. Applications
     *  needing to examine a specific property should use the extension
     *  interface defined by its <code> Type. </code>.
     *
     * @return object osid_PropertyList a list of properties
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException an authorization failure
     *                                        occurred
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getProperties()
    {
        return new phpkit_EmptyList('osid_PropertyList');
    }

    /**
     *  Gets a list of properties corresponding to the specified search record
     *  type. Properties provide a means for applications to display a
     *  representation of the contents of a search record without
     *  understanding its record interface specification. Applications needing
     *  to examine a specific propertyshould use the methods defined by the
     *  search record <code> Type. </code> The resulting set includes
     *  properties specified by parents of the record <code> type </code> in
     *  the case a record's interface extends another.
     *
     *  @param object osid_type_Type $searchRecordType the search record type
     *          corresponding to the properties set to retrieve
     *
     * @return object osid_PropertyList a list of properties
     *
     * @throws osid_NullArgumentException <code> searchRecordType </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    an authorization failure
     *                                           occurred
     * @throws osid_UnsupportedException <code>
     *                                           hasSearchRecordType(searchRecordType) </code> is <code> false
     *                                           </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getPropertiesBySearchRecordType(osid_type_Type $searchRecordType)
    {
        throw new osid_UnsupportedException('The search-record-type passed is not supported.');
    }

    /**
     *  Gets the course list resulting from a search.
     *
     * @return object osid_course_CourseList the course list
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourses()
    {
        return $this;
    }

    /**
     *  Gets the record corresponding to the given course search
     *  record <code> Type. </code> This method must be used to retrieve an
     *  object implementing the requested record interface along with all of
     *  its ancestor interfaces.
     *
     *  @param object osid_type_Type $courseSearchRecordType a course
     *          search record type
     *
     * @return object osid_course_CourseSearchResultsRecord the
     *                course search interface
     *
     * @throws osid_NullArgumentException <code>
     *                                           courseSearchRecordType </code> is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure occurred
     * @throws osid_UnsupportedException <code>
     *                                           hasSearchRecordType(courseSearchRecordType) </code> is
     *                                           <code> false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseSearchResultsRecord(osid_type_Type $courseSearchRecordType)
    {
        throw new osid_UnsupportedException('The search-record-type passed is not supported.');
    }
}
