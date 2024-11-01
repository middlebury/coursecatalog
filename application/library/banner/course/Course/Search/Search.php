<?php
/**
 * @since 10/14/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 *  <p>The search interface for governing course searches. </p>.
 */
class banner_course_Course_Search_Search extends banner_course_AbstractSearch implements osid_course_CourseSearch
{
    /**
     *  Execute this search using a previous search result.
     *
     *  @param object osid_course_CourseSearchResults $results results from a
     *          query
     *
     * @throws osid_InvalidArgumentException <code> results </code> is not
     *                                              valid
     * @throws osid_NullArgumentException <code>    results </code> is <code>
     *                                              null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function searchWithinCourseResults(osid_course_CourseSearchResults $results)
    {
        while ($results->hasNext()) {
            $id = $results->getNextCourse()->getId();
            $this->addWhereClause('course_id', '(SCBCRSE_SUBJ_CODE = ? AND SCBCRSE_CRSE_NUMB = ?)',
                [$this->session->getSubjectFromCourseId($id),
                    $this->session->getNumberFromCourseId($id)]);
        }
    }

    /**
     *  Execute this search among the given list of courses.
     *
     *  @param object osid_id_IdList $courseIds list of courses
     *
     * @throws osid_NullArgumentException <code> courseIds </code> is <code>
     *                                           null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function searchAmongCourses(osid_id_IdList $courseIds)
    {
        while ($courseIds->hasNext()) {
            $id = $courseIds->getNextId();
            $this->addWhereClause('course_id', '(SCBCRSE_SUBJ_CODE = ? AND SCBCRSE_CRSE_NUMB = ?)',
                [$this->session->getSubjectFromCourseId($id),
                    $this->session->getNumberFromCourseId($id)]);
        }
    }

    /**
     *  Specify an ordering to the search results.
     *
     *  @param object osid_course_CourseSearchOrder $courseSearchOrder course
     *          search order
     *
     * @throws osid_NullArgumentException <code> courseSearchOrder </code> is
     *                                           <code> null </code>
     * @throws osid_UnsupportedException <code>  courseSearchOrder </code> is
     *                                           not of this service
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderCourseResults(osid_course_CourseSearchOrder $courseSearchOrder)
    {
        $this->order = $courseSearchOrder;
    }

    /**
     *  Gets the record corresponding to the given course search record <code>
     *  Type. </code> This method must be used to retrieve an object
     *  implementing the requested record interface along with all of its
     *  ancestor interfaces.
     *
     *  @param object osid_type_Type $courseSearchRecordType a course search
     *          record type
     *
     * @return object osid_course_CourseSearchRecord the course search
     *                interface
     *
     * @throws osid_NullArgumentException <code> courseSearchRecordType
     *                                           </code> is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure occurred
     * @throws osid_UnsupportedException <code>
     *                                           hasSearchRecordType(courseSearchRecordType) </code> is <code>
     *                                           false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseSearchRecord(osid_type_Type $courseSearchRecordType)
    {
        throw new osid_UnsupportedException('The CourseSearchRecordType passed is not supported.');
    }
}
