<?php
/**
 * @since 5/04/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 *  <p>This is the query interface for searching course offerings. Each method 
 *  match specifies an <code> AND </code> term while multiple invocations of 
 *  the same method produce a nested <code> OR. </code> </p>
 * 
 * @package org.osid.course
 */
class banner_course_CourseOfferingQuery
    implements osid_course_CourseOfferingQuery
{

/*********************************************************
 * Methods from osid_OsidQuery
 *********************************************************/

	/**
     *  Gets the string matching types supported. A string match type 
     *  specifies the syntax of the string query, such as matching a word or 
     *  including a wildcard or regular expression. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          string match types 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getStringMatchTypes() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Tests if the given string matching type is supported. 
     *
     *  @param object osid_type_Type $searchType a <code> Type </code> 
     *          indicating a string match type 
     *  @return boolean <code> true </code> if the given Type is supported, 
     *          <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsStringMatchType(osid_type_Type $searchType) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Adds a keyword to match. Multiple keywords can be added to perform a 
     *  boolean <code> OR </code> among them. A keyword may be applied to any 
     *  of the elements defined in this object such as the display name, 
     *  description or any method defined in an interface implemented by this 
     *  object. 
     *
     *  @param string $keyword keyword to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> keyword is </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> keyword </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchKeyword($keyword, osid_type_Type $stringMatchType, 
                                 $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Adds a display name to match. Multiple display name matches can be 
     *  added to perform a boolean <code> OR </code> among them. 
     *  <br/><br/>
     *  
     *
     *  @param string $displayName display name to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> keyword is </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> displayName </code> or 
     *          <code> stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchDisplayName($displayName, 
                                     osid_type_Type $stringMatchType, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Adds a description name to match. Multiple description matches can be 
     *  added to perform a boolean <code> OR </code> among them. 
     *
     *  @param string $description description to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> keyword is </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> description </code> or 
     *          <code> stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchDescription($description, 
                                     osid_type_Type $stringMatchType, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Matches a description that has any value. 
     *
     *  @param boolean $match <code> true </code> to match any description, 
     *          <code> false </code> to match descriptions with no values 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyDescription($match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Sets a <code> Type </code> for querying objects of a given genus. A 
     *  genus type matches if the specified type is the same genus as the 
     *  object genus type. 
     *
     *  @param object osid_type_Type $genusType the object genus type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> genusType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchGenusType(osid_type_Type $genusType, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Sets a <code> Type </code> for querying objects of a given genus. A 
     *  genus type matches if the specified type is the same genus as the 
     *  object or if the specified type is an ancestor of the object genus in 
     *  a type hierarchy. 
     *
     *  @param object osid_type_Type $genusType the object genus type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> genusType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchParentGenusType(osid_type_Type $genusType, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Sets a <code> Type </code> for querying objects having records 
     *  implementing a given record type. This includes records of the same 
     *  interface type as the one provided and records implementing an 
     *  ancestor interface type in an interface hierarchy. 
     *
     *  @param object osid_type_Type $recordType the record interface type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> recordType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchRecordType(osid_type_Type $recordType, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Tests if this query supports the given record <code> Type. </code> The 
     *  given record type may be supported by the object through 
     *  interface/type inheritence. This method should be checked before 
     *  retrieving the record interface. 
     *
     *  @param object osid_type_Type $recordType a type 
     *  @return boolean <code> true </code> if a record query of the given 
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
 * Methods from osid_course_CourseOfferingQuery
 *********************************************************/


    /**
     *  Adds a title for this query. 
     *
     *  @param string $title title string to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> title </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> title </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchTitle($title, osid_type_Type $stringMatchType, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Matches a title that has any value. 
     *
     *  @param boolean $match <code> true </code> to match course offerings 
     *          with any title, <code> false </code> to match course offerings 
     *          with no title 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyTitle($match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Adds a course number for this query. 
     *
     *  @param string $number course number string to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> number </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> number </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchNumber($number, osid_type_Type $stringMatchType, 
                                $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Matches a course number that has any value. 
     *
     *  @param boolean $match <code> true </code> to match course offerings 
     *          with any number, <code> false </code> to match course 
     *          offerings with no number 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyNumber($match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Matches courses with credits between the given numbers inclusive. 
     *
     *  @param float $min low number 
     *  @param float $max high number 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> max </code> is less than 
     *          <code> min </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchCredits($min, $max, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Matches a course that has any credits assigned. 
     *
     *  @param boolean $match <code> true </code> to match course offerings 
     *          with any credits, <code> false </code> to match course 
     *          offerings with no credits 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyCredits($match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Matches courses with the prerequisites informational string. 
     *
     *  @param string $prereqInfo prerequisite informational string to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> prereqInfo </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> prereqInfo </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchPrereqInfo($prereqInfo, 
                                    osid_type_Type $stringMatchType, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Matches a course that has any prerequisite information assigned. 
     *
     *  @param boolean $match <code> true </code> to match courses with any 
     *          prerequisite information, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyPrereqInfo($match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Sets the course <code> Id </code> for this query to match courses 
     *  offerings that have a related course. 
     *
     *  @param object osid_id_Id $courseId a course <code> Id </code> 
     *  @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     *  @throws osid_NullArgumentException <code> courseId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchCourseId(osid_id_Id $courseId, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Tests if a <code> CourseQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a course query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseQuery() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the query interface for a course. Multiple retrievals produce a 
     *  nested <code> OR </code> term. 
     *
     *  @return object osid_course_CourseQuery the course query 
     *  @throws osid_UnimplementedException <code> supportsCourseQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseQuery() </code> is <code> true. </code> 
     */
    public function getCourseQuery() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Sets the term <code> Id </code> for this query to match courses 
     *  offerings that have a related term. 
     *
     *  @param object osid_id_Id $termId a term <code> Id </code> 
     *  @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     *  @throws osid_NullArgumentException <code> termId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchTermId(osid_id_Id $termId, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Tests if a <code> TermQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a term query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTermQuery() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the query interface for a term. Multiple retrievals produce a 
     *  nested <code> OR </code> term. 
     *
     *  @return object osid_course_TermQuery the term query 
     *  @throws osid_UnimplementedException <code> supportsTermQuery() </code> 
     *          is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTermQuery() </code> is <code> true. </code> 
     */
    public function getTermQuery() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Adds a location informational string for this query. 
     *
     *  @param string $locationInfo location string string to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> locationInfo </code> not 
     *          of <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> locationInfo </code> or 
     *          <code> stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchLocationInfo($locationInfo, 
                                      osid_type_Type $stringMatchType, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Matches a location informational string that has any value. 
     *
     *  @param boolean $match <code> true </code> to match courses offerings 
     *          with any location string, <code> false </code> to match course 
     *          offerings with no location string 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyLocationInfo($match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Sets the location resource <code> Id </code> for this query to match 
     *  courses offerings that have a related location resource. 
     *
     *  @param object osid_id_Id $resourceId a location resource <code> Id 
     *          </code> 
     *  @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     *  @throws osid_NullArgumentException <code> locationId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchLocationId(osid_id_Id $resourceId, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Tests if a <code> ResourceQuery </code> is available for the location. 
     *
     *  @return boolean <code> true </code> if a resource query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLocationQuery() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the query interface for a location resource. Multiple retrievals 
     *  produce a nested <code> OR </code> term. 
     *
     *  @return object osid_resource_ResourceQuery the resource query 
     *  @throws osid_UnimplementedException <code> supportsLocationQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLocationQuery() </code> is <code> true. </code> 
     */
    public function getLocationQuery() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Matches any location resource. 
     *
     *  @param boolean $match <code> true </code> to match course offerings 
     *          with any location, <code> false </code> to match course 
     *          offerings with no location 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyLocation($match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Adds a schedule informational string for this query. 
     *
     *  @param string $scheduleInfo schedule string string to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> scheduleInfo </code> not 
     *          of <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> scheduleInfo </code> or 
     *          <code> stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchScheduleInfo($scheduleInfo, 
                                      osid_type_Type $stringMatchType, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Matches a schedule informational string that has any value. 
     *
     *  @param boolean $match <code> true </code> to match courses offerings 
     *          with any schedule string, <code> false </code> to match course 
     *          offerings with no schedule string 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyScheduleInfo($match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Sets the calendar <code> Id </code> for this query to match courses 
     *  offerings that have a related calendar. 
     *
     *  @param object osid_id_Id $calendarId a calendar <code> Id </code> 
     *  @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     *  @throws osid_NullArgumentException <code> calendarId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchCalendarId(osid_id_Id $calendarId, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Tests if a <code> CalendarQuery </code> is available for the location. 
     *
     *  @return boolean <code> true </code> if a calendar query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCalendarQuery() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the query interface for a calendar. Multiple retrievals produce a 
     *  nested <code> OR </code> term. 
     *
     *  @return object osid_calendaring_CalendarQuery the calendar query 
     *  @throws osid_UnimplementedException <code> supportsCalendarQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCalendarQuery() </code> is <code> true. </code> 
     */
    public function getCalendarQuery() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Matches any calendar resource. 
     *
     *  @param boolean $match <code> true </code> to match course offerings 
     *          with any calendar, <code> false </code> to match course 
     *          offerings with no calendar 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyCalendar($match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Sets the course catalog <code> Id </code> for this query to match 
     *  courses offerings assigned to a learning objecive. 
     *
     *  @param object osid_id_Id $learningObjectiveId a learning objective 
     *          <code> Id </code> 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> learningObjectiveId </code> 
     *          is <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchLearningObjectiveId(osid_id_Id $learningObjectiveId, 
                                             $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Tests if a <code> LearningObjective </code> is available for the 
     *  location. 
     *
     *  @return boolean <code> true </code> if a learning objective query 
     *          interface is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLearningObjectiveQuery() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the query interface for a learning objective. Multiple retrievals 
     *  produce a nested <code> OR </code> term. 
     *
     *  @return object osid_learning_ObjectiveQuery the learning objective 
     *          query 
     *  @throws osid_UnimplementedException <code> 
     *          supportsLearningObjectiveQuery() </code> is <code> false 
     *          </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLearningObjectiveQuery() </code> is <code> true. 
     *              </code> 
     */
    public function getLearningObjectiveQuery() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Matches any learning objective. 
     *
     *  @param boolean $match <code> true </code> to match course offerings 
     *          with any learning objective, <code> false </code> to match 
     *          course offerings with no learning objective 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyLearningObjective($match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Sets the course catalog <code> Id </code> for this query to match 
     *  course offerings assigned to course catalogs. 
     *
     *  @param object osid_id_Id $courseCatalogId the course catalog <code> Id 
     *          </code> 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchCourseCatalogId(osid_id_Id $courseCatalogId, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Tests if a <code> CourseCatalogQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a course catalog query 
     *          interface is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseCatalogQuery() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the query interface for a course catalog. Multiple retrievals 
     *  produce a nested <code> OR </code> term. 
     *
     *  @return object osid_course_CourseCatalogQuery the course catalog query 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseCatalogQuery() </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseCatalogQuery() </code> is <code> true. 
     *              </code> 
     */
    public function getCourseCatalogQuery() {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Adds a class url for this query. 
     *
     *  @param string $url url string to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> url </code> not of <code> 
     *          stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> url </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchURL($url, osid_type_Type $stringMatchType, $match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Matches a url that has any value. 
     *
     *  @param boolean $match <code> true </code> to match course offerings 
     *          with any url, <code> false </code> to match course offerings 
     *          with no url 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyURL($match) {
    	throw new osid_UnimplementedException();
    }


    /**
     *  Gets the record query interface corresponding to the given <code> 
     *  CourseOffering </code> record <code> Type. </code> Multiple record 
     *  retrievals produce a nested <code> OR </code> term. 
     *
     *  @param object osid_type_Type $courseOfferingRecordType a course 
     *          offering record type 
     *  @return object osid_course_CourseOfferingQueryRecord the course 
     *          offering query record 
     *  @throws osid_NullArgumentException <code> courseOfferingRecordType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(courseOfferingRecordType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingQueryRecord(osid_type_Type $courseOfferingRecordType) {
    	throw new osid_UnimplementedException();
    }

}
