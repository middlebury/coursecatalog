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
    		throw new osid_NullArgumentException('$start and $end must be integers.');
		if ($start >= $end)
    		throw new osid_NullArgumentException('$start must be less than $end.');
    	
    	
    	// @todo actually do something here.
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
    	$courseOfferings = $results->getCourseOfferings();
    	while ($courseOfferings->hasNext()) {
    		$ids[] = $courseOfferings->getNextCourseOffering()->getId();
    	}
    	
    	$this->searchAmongCourseOfferings(new phpkit_id_ArrayIdList($ids));
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
    	$this->ids = $courseOfferingIds;
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
