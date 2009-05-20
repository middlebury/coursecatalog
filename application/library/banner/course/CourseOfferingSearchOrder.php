<?php
/**
 * @since 5/20/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 


/**
 *  <p>An interface for specifying the ordering of search results. </p>
 * 
 * @package org.osid.course
 */
class banner_course_CourseOfferingSearchOrder
    implements osid_course_CourseOfferingSearchOrder
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
    public function ascend() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Specifies a preference for ordering the result set in a descending 
     *  manner. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function descend() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Specifies a preference for ordering the result set by the display 
     *  name. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByDisplayName() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Specifies a preference for ordering the result set by the genus type. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByGenusType() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Tests if this search order supports the given record <code> Type. 
     *  </code> The given record type may be supported by the object through 
     *  interface/type inheritence. This method should be checked before 
     *  retrieving the record interface. 
     *
     *  @param object osid_type_Type $recordType a type 
     *  @return boolean <code> true </code> if an order record of the given 
     *          record <code> Type </code> is available, <code> false </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> recordType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasRecordType(osid_type_Type $recordType) {
    	throw new osid_UnimplementedException();
    }

/*********************************************************
 * Methods from osid_course_CourseOfferingSearchOrder
 *********************************************************/


    /**
     *  Specifies a preference for ordering the result set by course title. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByTitle() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Specifies a preference for ordering the result set by course number. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByNumber() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Specifies a preference for ordering the result set by course credits. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByCredits() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Specifies a preference for ordering the result set by course 
     *  prerequisite information. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByPrereqInfo() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Specifies a preference for ordering the result set by course. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByCourse() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Tests if a course order interface is available. 
     *
     *  @return boolean <code> true </code> if a course order interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseSearchOrder() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the course order interface. 
     *
     *  @return object osid_course_CourseSearchOrder the course search order 
     *          interface 
     *  @throws osid_UnimplementedException <code> supportsCourseSearchOrder() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseSearchOrder() </code> is <code> true. 
     *              </code> 
     */
    public function getCourseSearchOrder() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Specifies a preference for ordering the result set by course. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByTerm() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Tests if a term order interface is available. 
     *
     *  @return boolean <code> true </code> if a term order interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTermSearchOrder() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the term order interface. 
     *
     *  @return object osid_course_TermSearchOrder the term search order 
     *          interface 
     *  @throws osid_UnimplementedException <code> supportsTermSearchOrder() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTermSearchOrder() </code> is <code> true. </code> 
     */
    public function getTermSearchOrder() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Specifies a preference for ordering the result set by location info. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByLocationInfo() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Specifies a preference for ordering the result set by location. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByLocation() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Tests if a resource order interface is available for the location. 
     *
     *  @return boolean <code> true </code> if a location order interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLocationSearchOrder() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the resource order interface for the location. 
     *
     *  @return object osid_resource_ResourceSearchOrder the location search 
     *          order interface 
     *  @throws osid_UnimplementedException <code> 
     *          supportsLocationSearchOrder() </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLocationSearchOrder() </code> is <code> true. 
     *              </code> 
     */
    public function getLocationSearchOrder() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Specifies a preference for ordering the result set by schedule info. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByScheduleInfo() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Specifies a preference for ordering the result set by calendar. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByCalendar() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Tests if a resource order interface is available for the calendar. 
     *
     *  @return boolean <code> true </code> if a calendar order interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCalendarSearchOrder() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the calendar order interface. 
     *
     *  @return object osid_calendaring_CalendarSearchOrder the calendar 
     *          search order interface 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCalendarSearchOrder() </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCalendarSearchOrder() </code> is <code> true. 
     *              </code> 
     */
    public function getCalendarSearchOrder() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Specifies a preference for ordering the result set by learning 
     *  objective. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByLearningObjective() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Tests if a resource order interface is available for the learning 
     *  objective. 
     *
     *  @return boolean <code> true </code> if a learning objective order 
     *          interface is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLearningObjectiveSearchOrder() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the learning objective order interface. 
     *
     *  @return object osid_learning_ObjectiveSearchOrder the learning 
     *          objective search order interface 
     *  @throws osid_UnimplementedException <code> 
     *          supportsLearningObjectiveSearchOrder() </code> is <code> false 
     *          </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLearningObjectiveSearchOrder() </code> is <code> 
     *              true. </code> 
     */
    public function getLearningObjectiveSearchOrder() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Specifies a preference for ordering the result set by url. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByURL() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the course search order record corresponding to the given course 
     *  record <code> Type. </code> Multiple retrievals return the same 
     *  underlying object. 
     *
     *  @param object osid_type_Type $courseRecordType a course record type 
     *  @return object osid_course_CourseSearchOrderRecord the course search 
     *          order record interface 
     *  @throws osid_NullArgumentException <code> courseRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(courseRecordType) </code> is <code> false 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseSearchOrderRecord(osid_type_Type $courseRecordType) {
    	throw new osid_UnimplementedException();
    }

}
