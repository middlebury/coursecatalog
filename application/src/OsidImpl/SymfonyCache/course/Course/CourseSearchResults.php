<?php

namespace Catalog\OsidImpl\SymfonyCache\course\Course;

/**
 * A List for retrieving sections based on search results.
 *
 * @copyright Copyright &copy; 2025, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class CourseSearchResults implements \osid_course_CourseSearchResults
{
    public function __construct(
        protected CourseLookupSession $cacheSession,
        protected \osid_course_CourseSearchResults $results,
    ) {
    }

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
        return $this->results->getResultSize();
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
     * @return object \osid_type_TypeList the search record types available
     *                through this object
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getSearchRecordTypes()
    {
        return $this->results->getSearchRecordTypes();
    }

    /**
     *  Tests if this search results supports the given record <code> Type.
     *  </code> The given record type may be supported by the object through
     *  interface/type inheritence. This method should be checked before
     *  retrieving the record interface.
     *
     *  @param object \osid_type_Type $searchRecordType a type
     *
     * @return bool <code> true </code> if a search record the given
     *                     record <code> Type </code> is available, <code> false </code>
     *                     otherwise
     *
     * @throws \osid_NullArgumentException <code> searchRecordType </code> is
     *                                            <code> null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function hasSearchRecordType(\osid_type_Type $searchRecordType)
    {
        return $this->results->hasSearchRecordType($searchRecordType);
    }

    /**
     *  Gets a list of properties. Properties provide a means for applications
     *  to display a representation of the contents of a search record without
     *  understanding its <code> Type </code> specification. Applications
     *  needing to examine a specific property should use the extension
     *  interface defined by its <code> Type. </code>.
     *
     * @return object \osid_PropertyList a list of properties
     *
     * @throws \osid_OperationFailedException  unable to complete request
     * @throws \osid_PermissionDeniedException an authorization failure
     *                                         occurred
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getProperties()
    {
        return $this->results->getProperties();
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
     *  @param object \osid_type_Type $searchRecordType the search record type
     *          corresponding to the properties set to retrieve
     *
     * @return object \osid_PropertyList a list of properties
     *
     * @throws \osid_NullArgumentException <code> searchRecordType </code> is
     *                                            <code> null </code>
     * @throws \osid_OperationFailedException     unable to complete request
     * @throws \osid_PermissionDeniedException    an authorization failure
     *                                            occurred
     * @throws \osid_UnsupportedException <code>
     *                                            hasSearchRecordType(searchRecordType) </code> is <code> false
     *                                            </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getPropertiesBySearchRecordType(\osid_type_Type $searchRecordType)
    {
        return $this->results->getPropertiesBySearchRecordType($searchRecordType);
    }

    /**
     *  Gets the course list resulting from a search.
     *
     * @return object \osid_course_CourseList the course list
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourses()
    {
        return new CourseList($this->cacheSession, $this->results->getCourses());
    }

    /**
     *  Gets the record corresponding to the given course search record <code>
     *  Type. </code> This method must be used to retrieve an object
     *  implementing the requested record interface along with all of its
     *  ancestor interfaces.
     *
     *  @param object \osid_type_Type $courseSearchRecordType a course search
     *          record type
     *
     * @return object \osid_course_CourseSearchResultsRecord the course search
     *                interface
     *
     * @throws \osid_NullArgumentException <code> courseSearchRecordType
     *                                            </code> is <code> null </code>
     * @throws \osid_OperationFailedException     unable to complete request
     * @throws \osid_PermissionDeniedException    authorization failure occurred
     * @throws \osid_UnsupportedException <code>
     *                                            hasSearchRecordType(courseSearchRecordType) </code> is <code>
     *                                            false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseSearchResultsRecord(\osid_type_Type $courseSearchRecordType)
    {
        return $this->results->getCourseSearchResultsRecord($courseSearchRecordType);
    }
}
