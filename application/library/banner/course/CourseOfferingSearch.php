<?php
/**
 * @since 5/04/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 *  <p>The search interface for governing course offering searches. </p>
 * 
 * @package banner.course
 */
class banner_course_CourseOfferingSearch
    implements osid_course_CourseOfferingSearch
{

	/**
	 * Constructor
	 * 
	 * @param banner_course_AbstractCourseOfferingSession $session
	 * @return void
	 * @access public
	 * @since 5/28/09
	 */
	public function __construct (banner_course_AbstractCourseOfferingSession $session) {
		$this->session = $session;
		
		$this->limit = '';
		$this->order = null;
		
		$this->clauseSets = array();
		$this->parameters = array();
	}
	
	/**
	 * Add a clause. All clauses in the same set will be OR'ed, sets will be AND'ed.
	 * 
	 * @param string $set 
	 * @param string $where A where clause with parameters in '?' form.
	 * @param array $parameters An indexed array of parameters
     * @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
	 * @return void
	 * @access protected
	 * @since 5/20/09
	 */
	protected function addWhereClause ($set, $where, array $parameters) {
		$numParams = preg_match_all('/\?/', $where, $matches);
		if ($numParams === false)
			throw new osid_OperationFailedException('An error occured in matching.');
		if ($numParams != count($parameters))
			throw new osid_InvalidArgumentException('The number of \'?\'s must match the number of parameters.');
		
		if (!isset($this->clauseSets[$set]))
			$this->clauseSets[$set] = array();
		if (!isset($this->parameters[$set]))
			$this->parameters[$set] = array();
		
		$this->clauseSets[$set][] = $where;
		$this->parameters[$set][] = $parameters;
	}
	
	/**
	 * Answer the LIMIT clause
	 * 
	 * @return string
	 * @access public
	 * @since 5/28/09
	 */
	public function getLimitClause () {
		return $this->limit;
	}
	
	/**
	 * Answer the ORDER BY clause
	 * 
	 * @return string
	 * @access public
	 * @since 5/28/09
	 */
	public function getOrderByClause () {
		if (is_null($this->order))
			return '';
		else
			return $this->order->getOrderByClause();
	}
	
	/**
	 * Answer the SQL WHERE clause that reflects our current state
	 * 
	 * @return string
	 * @access public
	 * @since 5/20/09
	 */
	public function getWhereClause () {
		$sets = array();
		foreach ($this->clauseSets as $set) {
			$sets[] = '('.implode("\n\t\tOR ", $set).')';
		}
		
		return implode("\n\tAND ", $sets);
	}
	
	/**
	 * Answer the array of parameters that matches our current state
	 * 
	 * @return array
	 * @access public
	 * @since 5/20/09
	 */
	public function getParameters () {
		$params = array();
		foreach ($this->parameters as $set) {
			foreach ($set as $clauseParams) {
				$params = array_merge($params, $clauseParams);
			}
		}
		return $params;
	}
	
	/**
	 * Answer any additional table join clauses to use
	 * 
	 * @return array
	 * @access public
	 * @since 4/29/09
	 */
	public function getAdditionalTableJoins () {
		if ($this->order)
			return $this->order->getAdditionalTableJoins();
		else
			return array();
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
     *  @param integer $start the start of the result set 
     *  @param integer $end the end of the result set 
     *  @throws osid_InvalidArgumentException <code> end </code> is less than 
     *          or equal to <code> start </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function limitResultSet($start, $end) {
    	if (is_null($start) || is_null($end))
    		throw new osid_NullArgumentException('$start and $end must be integers.');
    	if (!is_int($start) || !is_int($end))
    		throw new osid_InvalidArgumentException('$start and $end must be integers.');
    	if ($start < 1)
    		throw new osid_InvalidArgumentException('$start must be greater than or equal to 1.');
		if ($start >= $end)
    		throw new osid_InvalidArgumentException('$start must be less than $end.');
    	
    	
    	$this->limit = 'LIMIT '.($start - 1).', '.($end + 1 - $start);
    }


    /**
     *  Tests if this search supports the given record <code> Type. </code> 
     *  The given record type may be supported by the object through 
     *  interface/type inheritence. This method should be checked before 
     *  retrieving the record interface. 
     *
     *  @param object osid_type_Type $searchRecordType a type 
     *  @return boolean <code> true </code> if a search record the given 
     *          record <code> Type </code> is available, <code> false </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> searchRecordType </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasSearchRecordType(osid_type_Type $searchRecordType) {
    	return false;
    }


/*********************************************************
 * Methods from osid_course_CourseOfferingSearch
 *********************************************************/

    /**
     *  Execute this search using a previous search result. 
     *
     *  @param object osid_course_CourseOfferingSearchResults $results results 
     *          from a query 
     *  @throws osid_InvalidArgumentException <code> results </code> is not 
     *          valid 
     *  @throws osid_NullArgumentException <code> results </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function searchWithinCourseOfferingResults(osid_course_CourseOfferingSearchResults $results) {
    	$ids = array();
    	while ($results->hasNext()) {
    		$id = $results->getNextCourseOffering()->getId();
    		$this->addWhereClause('course_offering_id', '(SSBSECT_TERM_CODE = ? AND SSBSECT_CRN = ?)', 
    			array(	$this->session->getTermCodeFromOfferingId($id),
    					$this->session->getCrnFromOfferingId($id)));
    	}
    }

	
    /**
     *  Execute this search among the given list of course offerings. 
     *
     *  @param object osid_id_IdList $courseOfferingIds list of courses 
     *  @throws osid_NullArgumentException <code> courseOfferingIds </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function searchAmongCourseOfferings(osid_id_IdList $courseOfferingIds) {
    	while ($courseOfferingIds->hasNext()) {
    		$id = $courseOfferingIds->getNextId();
    		$this->addWhereClause('course_offering_id', '(SSBSECT_TERM_CODE = ? AND SSBSECT_CRN = ?)', 
    			array(	$this->session->getTermCodeFromOfferingId($id),
    					$this->session->getCrnFromOfferingId($id)));
    	}
    }


    /**
     *  Specify an ordering to the search results. 
     *
     *  @param object osid_course_CourseOfferingSearchOrder $courseOfferingSearchOrder 
     *          course search order 
     *  @throws osid_NullArgumentException <code> courseOfferingSearchOrder 
     *          </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> courseOfferingSearchOrder 
     *          </code> is not of this service 
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderCourseOfferingResults(osid_course_CourseOfferingSearchOrder $courseOfferingSearchOrder) {
    	$this->order = $courseOfferingSearchOrder;
    }


    /**
     *  Gets the record corresponding to the given course offering search 
     *  record <code> Type. </code> This method must be used to retrieve an 
     *  object implementing the requested record interface along with all of 
     *  its ancestor interfaces. 
     *
     *  @param object osid_type_Type $courseOfferingSearchRecordType a course 
     *          search record type 
     *  @return object osid_course_CourseOfferingSearchRecord the course 
     *          offering search interface 
     *  @throws osid_NullArgumentException <code> 
     *          courseOfferingSearchRecordType </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasSearchRecordType(courseOfferingSearchRecordType) </code> is 
     *          <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingSearchRecord(osid_type_Type $courseOfferingSearchRecordType) {
    	throw new osid_UnsupportedException();
    }

}
