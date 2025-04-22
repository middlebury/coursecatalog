<?php

namespace Catalog\OsidImpl\SymfonyCache\course\CourseOffering;

/**
 * A List for retrieving APC-wrapped course offerings from an underlying implementation.
 *
 * @copyright Copyright &copy; 2025, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class CourseOfferingList implements \osid_course_CourseOfferingList
{
    public function __construct(
        protected CourseOfferingLookupSession $cacheSession,
        protected \osid_course_CourseOfferingList $offerings,
    ) {
    }

    /**
     *  Tests if there are more elements in this list.
     *
     * @return bool <code> true </code> if more elements are available in
     *                     this list, <code> false </code> if the end of the list has
     *                     been reached
     *
     * @throws \osid_IllegalStateException this list has been closed
     *
     *  @compliance mandatory This method must be implemented.
     *
     *  @notes  Any errors that may result from accesing the underlying set of
     *          elements are to be deferred until the consumer attempts
     *          retrieval in which case the provider must return <code> true
     *          </code> for this method.
     */
    public function hasNext()
    {
        return $this->offerings->hasNext();
    }

    /**
     *  Gets the number of elements available for retrieval. The number
     *  returned by this method may be less than or equal to the total number
     *  of elements in this list. To determine if the end of the list has been
     *  reached, the method <code> hasNext() </code> should be used. This
     *  method conveys what is known about the number of remaining elements at
     *  a point in time and can be used to determine a minimum size of the
     *  remaining elements, if known. A valid return is zero even if <code>
     *  hasNext() </code> is true.
     *  <br/><br/>
     *  This method does not imply asynchronous usage. All OSID methods may
     *  block.
     *
     * @return int the number of elements available for retrieval
     *
     * @throws \osid_IllegalStateException this list has been closed
     *
     *  @compliance mandatory This method must be implemented.
     *
     *  @notes  Any errors that may result from accesing the underlying set of
     *          elements are to be deferred until the consumer attempts
     *          retrieval in which case the provider must return a positive
     *          integer for this method so the consumer can continue execution
     *          to receive the error. In all other circumstances, the provider
     *          must not return a number greater than the number of elements
     *          known since this number will be fed as a parameter to the bulk
     *          retrieval method.
     */
    public function available()
    {
        return $this->offerings->available();
    }

    /**
     *  Skip the specified number of elements in the list. If the number
     *  skipped is greater than the number of elements in the list, hasNext()
     *  becomes false and available() returns zero as there are no more
     *  elements to retrieve.
     *
     * @param int $n the number of elements to skip
     *
     * @throws \osid_NullArgumentException null argument provided
     * @throws \osid_IllegalStateException this list has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function skip($n)
    {
        $this->offerings->skip($n);
    }

    /**
     *  Closes down this <code>osid.OsidList</code>.
     */
    public function done()
    {
        $this->offerings->done();
    }

    /**
     *  Gets the next <code> CourseOffering </code> in this list.
     *
     * @return object \osid_course_CourseOffering the next <code>
     *                CourseOffering </code> in this list. The <code> hasNext()
     *                </code> method should be used to test that a next <code>
     *                CourseOffering </code> is available before calling this
     *                method.
     *
     * @throws \osid_IllegalStateException    no more elements available in this
     *                                        list or this list has been closed
     * @throws \osid_OperationFailedException unable to complete request
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getNextCourseOffering()
    {
        return new CourseOffering($this->cacheSession, $this->offerings->getNextCourseOffering());
    }

    /**
     *  Gets the next set of <code> CourseOffering </code> elements in this
     *  list. The specified amount must be less than or equal to the return
     *  from <code> available(). </code>.
     *
     * @param int $n the number of <code> CourseOffering </code> elements
     *               requested which must be less than or equal to <code>
     *               available() </code>
     *
     * @return array of \osid_course_CourseOffering objects  an array of
     *               <code> CourseOffering </code> elements. <code> </code> The
     *               length of the array is less than or equal to the number
     *               specified.
     *
     * @throws \osid_IllegalStateException    no more elements available in this
     *                                        list or this list has been closed
     * @throws \osid_OperationFailedException unable to complete request
     * @throws \osid_NullArgumentException    null argument provided
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getNextCourseOfferings($n)
    {
        $results = [];
        foreach ($this->offerings->getNextCourseOfferings($n) as $offering) {
            $results[] = new CourseOffering($this->cacheSession, $offering);
        }

        return $results;
    }
}
