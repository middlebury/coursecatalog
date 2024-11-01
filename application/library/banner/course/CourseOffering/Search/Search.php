<?php
/**
 * @since 5/04/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 *  <p>The search interface for governing course offering searches. </p>.
 */
class banner_course_CourseOffering_Search_Search extends banner_course_AbstractSearch implements osid_course_CourseOfferingSearch
{
    /*********************************************************
     * Methods from osid_course_CourseOfferingSearch
     *********************************************************/

    /**
     *  Execute this search using a previous search result.
     *
     *  @param object osid_course_CourseOfferingSearchResults $results results
     *          from a query
     *
     * @throws osid_InvalidArgumentException <code> results </code> is not
     *                                              valid
     * @throws osid_NullArgumentException <code>    results </code> is <code>
     *                                              null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function searchWithinCourseOfferingResults(osid_course_CourseOfferingSearchResults $results)
    {
        $ids = [];
        while ($results->hasNext()) {
            $id = $results->getNextCourseOffering()->getId();
            $this->addWhereClause('course_offering_id', '(SSBSECT_TERM_CODE = ? AND SSBSECT_CRN = ?)',
                [$this->session->getTermCodeFromOfferingId($id),
                    $this->session->getCrnFromOfferingId($id)]);
        }
    }

    /**
     *  Execute this search among the given list of course offerings.
     *
     *  @param object osid_id_IdList $courseOfferingIds list of courses
     *
     * @throws osid_NullArgumentException <code> courseOfferingIds </code> is
     *                                           <code> null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function searchAmongCourseOfferings(osid_id_IdList $courseOfferingIds)
    {
        while ($courseOfferingIds->hasNext()) {
            $id = $courseOfferingIds->getNextId();
            $this->addWhereClause('course_offering_id', '(SSBSECT_TERM_CODE = ? AND SSBSECT_CRN = ?)',
                [$this->session->getTermCodeFromOfferingId($id),
                    $this->session->getCrnFromOfferingId($id)]);
        }
    }

    /**
     *  Specify an ordering to the search results.
     *
     *  @param object osid_course_CourseOfferingSearchOrder $courseOfferingSearchOrder
     *          course search order
     *
     * @throws osid_NullArgumentException <code> courseOfferingSearchOrder
     *                                           </code> is <code> null </code>
     * @throws osid_UnsupportedException <code>  courseOfferingSearchOrder
     *                                           </code> is not of this service
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderCourseOfferingResults(osid_course_CourseOfferingSearchOrder $courseOfferingSearchOrder)
    {
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
     *
     * @return object osid_course_CourseOfferingSearchRecord the course
     *                offering search interface
     *
     * @throws osid_NullArgumentException <code>
     *                                           courseOfferingSearchRecordType </code> is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure occurred
     * @throws osid_UnsupportedException <code>
     *                                           hasSearchRecordType(courseOfferingSearchRecordType) </code> is
     *                                           <code> false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseOfferingSearchRecord(osid_type_Type $courseOfferingSearchRecordType)
    {
        throw new osid_UnsupportedException('The CourseOfferingSearchRecordType passed is not supported.');
    }
}
