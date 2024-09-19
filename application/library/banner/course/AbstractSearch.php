<?php
/**
 * @since 10/14/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * An abstract Search object.
 *
 * @since 10/14/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class banner_course_AbstractSearch
{
    protected $session;
    protected $limit;
    protected $order;
    private $clauseSets;
    private $parameters;

    /**
     * Constructor.
     *
     * @param banner_course_CourseOffering_AbstractSession $session
     *
     * @return void
     *
     * @since 5/28/09
     */
    public function __construct(banner_course_AbstractSession $session)
    {
        $this->session = $session;

        $this->limit = '';
        $this->order = null;

        $this->clauseSets = [];
        $this->parameters = [];
    }

    /**
     * Add a clause. All clauses in the same set will be OR'ed, sets will be AND'ed.
     *
     * @param string $set
     * @param string $where      a where clause with parameters in '?' form
     * @param array  $parameters An indexed array of parameters
     *
     * @return void
     *
     * @since 5/20/09
     */
    protected function addWhereClause($set, $where, array $parameters)
    {
        $numParams = preg_match_all('/\?/', $where, $matches);
        if (false === $numParams) {
            throw new osid_OperationFailedException('An error occured in matching.');
        }
        if ($numParams != count($parameters)) {
            throw new osid_InvalidArgumentException('The number of \'?\'s must match the number of parameters.');
        }

        if (!isset($this->clauseSets[$set])) {
            $this->clauseSets[$set] = [];
        }
        if (!isset($this->parameters[$set])) {
            $this->parameters[$set] = [];
        }

        $this->clauseSets[$set][] = $where;
        $this->parameters[$set][] = $parameters;
    }

    /**
     * Answer the LIMIT clause.
     *
     * @return string
     *
     * @since 5/28/09
     */
    public function getLimitClause()
    {
        return $this->limit;
    }

    /**
     * Answer th SQL ORDER BY clause.
     *
     * @return string
     *
     * @since 5/28/09
     */
    public function getOrderByClause()
    {
        $orderTerms = $this->getOrderByTerms();
        if (count($orderTerms)) {
            return 'ORDER BY '.implode(', ', $orderTerms);
        } else {
            return '';
        }
    }

    /**
     * Answer an array column/direction terms for a SQL ORDER BY clause.
     *
     * @return array
     *
     * @since 5/28/09
     */
    public function getOrderByTerms()
    {
        if (is_null($this->order)) {
            return [];
        } else {
            return $this->order->getOrderByTerms();
        }
    }

    /**
     * Answer the SQL WHERE clause that reflects our current state.
     *
     * @return string
     *
     * @since 5/20/09
     */
    public function getWhereClause()
    {
        $sets = [];
        foreach ($this->clauseSets as $set) {
            $sets[] = '('.implode("\n\t\tOR ", $set).')';
        }

        return implode("\n\tAND ", $sets);
    }

    /**
     * Answer the array of parameters that matches our current state.
     *
     * @return array
     *
     * @since 5/20/09
     */
    public function getParameters()
    {
        $params = [];
        foreach ($this->parameters as $set) {
            foreach ($set as $clauseParams) {
                $params = array_merge($params, $clauseParams);
            }
        }

        return $params;
    }

    /**
     * Answer any additional table join clauses to use.
     *
     * @return array
     *
     * @since 4/29/09
     */
    public function getAdditionalTableJoins()
    {
        if ($this->order) {
            return $this->order->getAdditionalTableJoins();
        } else {
            return [];
        }
    }

    /*********************************************************
     * Methods from osid_OsidSearch
     *********************************************************/

    /**
     *  By default, searches return all matching results. This method
     *  restricts the number of results by setting the start and end of the
     *  result set, starting from 1. The starting and ending results can be
     *  used for paging results when a certain ordering is requested. The
     *  ending position must be greater than the starting position.
     *
     * @param int $start the start of the result set
     * @param int $end   the end of the result set
     *
     * @throws osid_InvalidArgumentException <code> end </code> is less than
     *                                              or equal to <code> start </code>
     * @throws osid_NullArgumentException           null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function limitResultSet($start, $end)
    {
        if (is_null($start) || is_null($end)) {
            throw new osid_NullArgumentException('$start and $end must be integers.');
        }
        if (!is_int($start) || !is_int($end)) {
            throw new osid_InvalidArgumentException('$start and $end must be integers.');
        }
        if ($start < 1) {
            throw new osid_InvalidArgumentException('$start must be greater than or equal to 1.');
        }
        if ($start >= $end) {
            throw new osid_InvalidArgumentException('$start must be less than $end.');
        }

        $this->limit = 'LIMIT '.($start - 1).', '.($end + 1 - $start);
    }

    /**
     *  Tests if this search supports the given record <code> Type. </code>
     *  The given record type may be supported by the object through
     *  interface/type inheritence. This method should be checked before
     *  retrieving the record interface.
     *
     *  @param object osid_type_Type $searchRecordType a type
     *
     * @return boolean <code> true </code> if a search record the given
     *                        record <code> Type </code> is available, <code> false </code>
     *                        otherwise
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
}
