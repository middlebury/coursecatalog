<?php
/**
 * @since 10/14/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 *  <p>An interface for specifying the ordering of search results. </p>.
 */
class banner_course_Course_Search_Order extends banner_course_AbstractSearchOrder implements osid_course_CourseSearchOrder
{
    /*********************************************************
     * Methods from osid_OsidSearchOrder
     *********************************************************/

    /**
     *  Specifies a preference for ordering the result set in an ascending
     *  manner.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function ascend()
    {
        if (count($this->terms)) {
            $last = count($this->terms) - 1;
            $this->terms[$last]['direction'] = 'ASC';
        }
    }

    /**
     *  Specifies a preference for ordering the result set in a descending
     *  manner.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function descend()
    {
        if (count($this->terms)) {
            $last = count($this->terms) - 1;
            $this->terms[$last]['direction'] = 'DESC';
        }
    }

    /**
     *  Specifies a preference for ordering the result set by the display
     *  name.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByDisplayName()
    {
        $this->orderByNumber();
    }

    /**
     *  Specifies a preference for ordering the result set by the genus type.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByGenusType()
    {
        $this->addOrderColumns(['SSBSECT_SCHD_CODE']);
    }

    /*********************************************************
     * Methods from osid_course_CourseSearchOrder
     *********************************************************/

    /**
     *  Specifies a preference for ordering the result set by course title.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByTitle()
    {
        $this->addOrderColumns(['SCBCRSE_TITLE']);
    }

    /**
     *  Specifies a preference for ordering the result set by course number.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByNumber()
    {
        $this->addOrderColumns(['SCBCRSE_SUBJ_CODE', 'SCBCRSE_CRSE_NUMB']);
    }

    /**
     *  Specifies a preference for ordering the result set by course credits.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByCredits()
    {
        $this->addOrderColumns(['SCBCRSE_CREDIT_HR_HIGH']);
    }

    /**
     *  Specifies a preference for ordering the result set by course
     *  prerequisite information.
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function orderByPrereqInfo()
    {
        throw new osid_UnimplementedException();
    }

    /**
     *  Gets the course search order record corresponding to the given course
     *  record <code> Type. </code> Multiple retrievals return the same
     *  underlying object.
     *
     *  @param object osid_type_Type $courseRecordType a course record type
     *
     * @return object osid_course_CourseSearchOrderRecord the course search
     *                order record interface
     *
     * @throws osid_NullArgumentException <code> courseRecordType </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure occurred
     * @throws osid_UnsupportedException <code>
     *                                           hasRecordType(courseRecordType) </code> is <code> false
     *                                           </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseSearchOrderRecord(osid_type_Type $courseRecordType)
    {
        throw new osid_UnsupportedException('The CourseRecordType passed is not supported.');
    }
}
