<?php

/**
 * osid_course_CourseManager
 * 
 *     Specifies the OSID definition for osid_course_CourseManager.
 * 
 * Copyright (C) 2009 Massachusetts Institute of Technology. All Rights 
 * Reserved. 
 * 
 *     This Work is being provided by the copyright holder(s) subject to the 
 *     following license. By obtaining, using and/or copying this Work, you 
 *     agree that you have read, understand, and will comply with the 
 *     following terms and conditions. 
 *     
 *     This Work and the information contained herein is provided on an ""AS 
 *     IS"" basis. The Massachusetts Institute of Technology, the Open 
 *     Knowledge Initiative, and THE AUTHORS DISCLAIM ALL WARRANTIES, EXPRESS 
 *     OR IMPLIED, INCLUDING BUT NOT LIMITED TO WARRANTIES OF MERCHANTABILITY, 
 *     FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL 
 *     THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR 
 *     OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, 
 *     ARISING FROM, OUT OF OR IN CONNECTION WITH THE WORK OR THE USE OR OTHER 
 *     DEALINGS IN THE WORK. 
 *     
 *     Permission to use, copy and distribute unmodified versions of this 
 *     Work, for any purpose, without fee or royalty is hereby granted, 
 *     provided that you include the above copyright notice and the terms of 
 *     this license on ALL copies of the Work or portions thereof. 
 *     
 *     You may nodify or create Derivatives of this Work only for your 
 *     internal purposes. You shall not distribute or transfer any such 
 *     Derivative of this Work to any location or to any third party. For the 
 *     purposes of this license, Derivative shall mean any derivative of the 
 *     Work as defined in the United States Copyright Act of 1976, such as a 
 *     translation or modification. 
 *     
 *     The export of software employing encryption technology may require a 
 *     specific license from the United States Government. It is the 
 *     responsibility of any person or organization comtemplating export to 
 *     obtain such a license before exporting this Work. 
 * 
 * @package org.osid.course
 */

require_once(dirname(__FILE__)."/../OsidManager.php");
require_once(dirname(__FILE__)."/CourseProfile.php");

/**
 *  <p>The course manager provides access to rules sessions and provides 
 *  interoperability tests for various aspects of this service. The sessions 
 *  included in this manager are: 
 *  <ul>
 *      <li> <code> CourseLookupSession: </code> a session to retrieve courses 
 *      </li> 
 *      <li> <code> CourseSearchSession: </code> a session to search for 
 *      courses </li> 
 *      <li> <code> CourseAdminSession: </code> a session to create and delete 
 *      courses </li> 
 *      <li> <code> CourseNotificationSession: </code> a session to receive 
 *      notifications pertaining to course changes </li> 
 *      <li> <code> CourseCatalogSession: </code> a session to look up course 
 *      to course catalog mappings </li> 
 *      <li> <code> CourseCatalogAssignmentSession: </code> a session to 
 *      manage course to course catalog mappings </li> 
 *  </ul>
 *  
 *  <ul>
 *      <li> <code> CourseOfferingLookupSession: </code> a session to retrieve 
 *      course offerings </li> 
 *      <li> <code> CourseOfferingSearchSession: </code> a session to search 
 *      for course offerings </li> 
 *      <li> <code> CourseOfferingAdminSession: </code> a session to create 
 *      and delete course offerings </li> 
 *      <li> <code> CourseOfferingNotificationSession: </code> a session to 
 *      receive notifications pertaining to course offering changes </li> 
 *      <li> <code> CourseOfferingHierarchySession: </code> a session to 
 *      traverse a hierarchy of course offerings </li> 
 *      <li> <code> CourseOfferingHierarchyDesignSession: </code> a session to 
 *      manage a course offering hierarchy </li> 
 *      <li> <code> CourseOfferingCatalogSession: </code> a session to look up 
 *      course offering to course catalog mappings </li> 
 *      <li> <code> CourseOfferingCatalogAssignmentSession: </code> a session 
 *      to manage course offering to course catalog mappings </li> 
 *  </ul>
 *  
 *  <ul>
 *      <li> <code> TermLookupSession: </code> a session to retrieve terms 
 *      </li> 
 *      <li> <code> TermOfferingSearchSession: </code> a session to search for 
 *      terms </li> 
 *      <li> <code> TermOfferingAdminSession: </code> a session to create and 
 *      delete terms </li> 
 *      <li> <code> TermOfferingNotificationSession: </code> a session to 
 *      receive notifications pertaining to term changes </li> 
 *      <li> <code> TermOfferingHierarchySession: </code> a session to 
 *      traverse a hierarchy of terms </li> 
 *      <li> <code> TermOfferingHierarchyDesignSession: </code> a session to 
 *      manage a term hierarchy </li> 
 *      <li> <code> TermOfferingCatalogSession: </code> a session to look up 
 *      term to course catalog mappings </li> 
 *      <li> <code> TermOfferingCatalogAssignmentSession: </code> a session to 
 *      manage term to course catalog mappings </li> 
 *  </ul>
 *  
 *  <ul>
 *      <li> <code> TopicLookupSession: </code> a session to retrieve topics 
 *      </li> 
 *      <li> <code> TopicOfferingSearchSession: </code> a session to search 
 *      for topics </li> 
 *      <li> <code> TopicOfferingAdminSession: </code> a session to create and 
 *      delete topics </li> 
 *      <li> <code> TopicOfferingNotificationSession: </code> a session to 
 *      receive notifications pertaining to topic changes </li> 
 *      <li> <code> TopicOfferingHierarchySession: </code> a session to 
 *      traverse a hierarchy of topics </li> 
 *      <li> <code> TopicOfferingHierarchyDesignSession: </code> a session to 
 *      manage a topic hierarchy </li> 
 *      <li> <code> TopicOfferingCatalogSession: </code> a session to look up 
 *      topic to course catalog mappings </li> 
 *      <li> <code> TopicOfferingCatalogAssignmentSession: </code> a session 
 *      to manage topic to course catalog mappings </li> 
 *  </ul>
 *  
 *  <ul>
 *      <li> <code> CourseCatalogLookupSession: </code> a session to retrieve 
 *      course catalogs </li> 
 *      <li> <code> CourseCatalogSearchSession: </code> a session to search 
 *      for course catalogs </li> 
 *      <li> <code> CourseCatalogAdminSession: </code> a session to create and 
 *      delete course catalogs </li> 
 *      <li> <code> CourseCatalogNotificationSession: </code> a session to 
 *      receive notifications pertaining to course catalog changes </li> 
 *      <li> <code> CourseCatalogHierarchySession: </code> a session to 
 *      traverse a hierarchy of course catalogs </li> 
 *      <li> <code> CourseCatalogHierarchyDesignSession: </code> a session to 
 *      manage a course catalog hierarchy </li> 
 *  </ul>
 *  The course manager also provides a profile for determing the supported 
 *  search types supported by this service. This OSID also leverages other 
 *  service definitions to fulfill it service. These include: </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> <code> ResourceManager: </code> provides interfaces to find, 
 *      create and modify resources used in course offerings and enrollment 
 *      </li> 
 *      <li> <code> ObjectiveManager: </code> provides interfaces to find, 
 *      create and modify learning objectives used in course offerings </li> 
 *      <li> <code> CalendarManager: </code> provides interfaces to find, 
 *      create and modify calendars used in course offerings </li> 
 *  </ul>
 *  </p>
 * 
 * @package org.osid.course
 */
interface osid_course_CourseManager
    extends osid_OsidManager,
            osid_course_CourseProfile
{


    /**
     *  Gets the <code> OsidSession </code> associated with the course lookup 
     *  service. 
     *
     *  @return object osid_course_CourseLookupSession a <code> 
     *          CourseLookupSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsCourseLookup() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseLookup() </code> is <code> true. </code> 
     */
    public function getCourseLookupSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the course lookup 
     *  service for the given course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          course catalog 
     *  @return object osid_course_CourseLookupSession a <code> 
     *          CourseLookupSession </code> 
     *  @throws osid_NotFoundException no <code> CousreCatalog </code> found 
     *          by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsCourseLookup() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseLookup() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getCourseLookupSessionForCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Gets the <code> OsidSession </code> associated with the course search 
     *  service. 
     *
     *  @return object osid_course_CourseSearchSession a <code> 
     *          CourseSearchSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsCourseSearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseSearch() </code> is <code> true. </code> 
     */
    public function getCourseSearchSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the course search 
     *  service for the given course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @return object osid_course_CourseSearchSession a <code> 
     *          CourseSearchSession </code> 
     *  @throws osid_NotFoundException no course catalog found by the given 
     *          <code> Id </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsCourseSearch() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseSearch() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getCourseSearchSessionForCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Gets the <code> OsidSession </code> associated with the course 
     *  administration service. 
     *
     *  @return object osid_course_CourseAdminSession a <code> 
     *          CourseAdminSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsCourseAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseAdmin() </code> is <code> true. </code> 
     */
    public function getCourseAdminSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the course 
     *  administration service for the given course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @return object osid_course_CourseAdminSession a <code> 
     *          CourseAdminSession </code> 
     *  @throws osid_NotFoundException no course catalog found by the given 
     *          <code> Id </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsCourseAdmin() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseAdmin() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getCourseAdminSessionForCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Gets the <code> OsidSession </code> associated with the course 
     *  notification service. 
     *
     *  @return object osid_course_CourseNotificationSession a <code> 
     *          CourseNotificationSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseNotification() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseNotification() </code> is <code> true. 
     *              </code> 
     */
    public function getCourseNotificationSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the course 
     *  notification service for the given course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @return object osid_course_CourseNotificationSession a <code> 
     *          CourseNotificationSession </code> 
     *  @throws osid_NotFoundException no course catalog found by the given 
     *          <code> Id </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseNotification() </code> or <code> 
     *          supportsVisibleFederation() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseNotification() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getCourseNotificationSessionForCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Gets the <code> OsidSession </code> to lookup course/catalog mappings. 
     *
     *  @return object osid_course_CourseCatalogSession a <code> 
     *          CourseCatalogSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsCourseCatalog() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseCatalog() </code> is <code> true. </code> 
     */
    public function getCourseCatalogSession();


    /**
     *  Gets the <code> OsidSession </code> associated with assigning courses 
     *  to course catalogs. 
     *
     *  @return object osid_course_CourseCatalogAssignmentSession a <code> 
     *          CourseCatalogAssignmentSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseCatalogAssignment() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseCatalogAssignment() </code> is <code> true. 
     *              </code> 
     */
    public function getCourseCatalogAssignmentSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the course 
     *  offering lookup service. 
     *
     *  @return object osid_course_CourseOfferingLookupSession a <code> 
     *          CourseOfferingSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseOfferingLookup() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseOfferingLookup() </code> is <code> true. 
     *              </code> 
     */
    public function getCourseOfferingLookupSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the course 
     *  offering lookup service for the given course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          course catalog 
     *  @return object osid_course_CourseOfferingLookupSession a <code> 
     *          CourseOfferingLookupSession </code> 
     *  @throws osid_NotFoundException no <code> CousreCatalog </code> found 
     *          by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseOfferingLookup() </code> or <code> 
     *          supportsVisibleFederation() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseOfferingLookup() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getCourseOfferingLookupSessionForCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Gets the <code> OsidSession </code> associated with the course 
     *  offering search service. 
     *
     *  @return object osid_course_CourseOfferingSearchSession a <code> 
     *          CourseOfferingSearchSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseOfferingSearch() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseOfferingSearch() </code> is <code> true. 
     *              </code> 
     */
    public function getCourseOfferingSearchSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the course 
     *  offering search service for the given course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @return object osid_course_CourseOfferingSearchSession a <code> 
     *          CourseOfferingSearchSession </code> 
     *  @throws osid_NotFoundException no course catalog found by the given 
     *          <code> Id </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseOfferingSearch() </code> or <code> 
     *          supportsVisibleFederation() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseOfferingSearch() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getCourseOfferingSearchSessionForCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Gets the <code> OsidSession </code> associated with the course 
     *  offering administration service. 
     *
     *  @return object osid_course_CourseOfferingAdminSession a <code> 
     *          CourseOfferingAdminSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseOfferingAdmin() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseOfferingAdmin() </code> is <code> true. 
     *              </code> 
     */
    public function getCourseOfferingAdminSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the course 
     *  offering administration service for the given course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @return object osid_course_CourseOfferingAdminSession a <code> 
     *          CourseOfferingAdminSession </code> 
     *  @throws osid_NotFoundException no course catalog found by the given 
     *          <code> Id </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseOfferingAdmin() </code> or <code> 
     *          supportsVisibleFederation() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseOfferingAdmin() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getCourseOfferingAdminSessionForCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Gets the <code> OsidSession </code> associated with the course 
     *  offering notification service. 
     *
     *  @return object osid_course_CourseOfferingNotificationSession a <code> 
     *          CourseOfferingNotificationSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseOfferingNotification() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseOfferingNotification() </code> is <code> 
     *              true. </code> 
     */
    public function getCourseOfferingNotificationSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the course 
     *  offering notification service for the given course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @return object osid_course_CourseOfferingNotificationSession a <code> 
     *          CourseOfferingNotificationSession </code> 
     *  @throws osid_NotFoundException no course catalog found by the given 
     *          <code> Id </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseOfferingNotification() </code> or <code> 
     *          supportsVisibleFederation() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseOfferingNotification() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getCourseOfferingNotificationSessionForCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Gets the course offering hierarchy traversal session. 
     *
     *  @return object osid_course_CourseOfferingHierarchySession <code> a 
     *          CourseOfferingHierarchySession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseOfferingHierarchy() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseOfferingHierarchy() </code> is <code> true. 
     *              </code> 
     */
    public function getCourseOfferingHierarchySession();


    /**
     *  Gets the course offering hierarchy design session. 
     *
     *  @return object osid_course_CourseOfferingHierarchyDesignSession a 
     *          <code> CourseOfferingHierarchyDesignSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseOfferingHierarchyDesign() </code> is <code> 
     *          false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseOfferingHierarchyDesign() </code> is <code> 
     *              true. </code> 
     */
    public function getCourseOfferingHierarchyDesignSession();


    /**
     *  Gets the <code> OsidSession </code> to lookup course offering/catalog 
     *  mappings. 
     *
     *  @return object osid_course_CourseOfferingCatalogSession a <code> 
     *          CourseOfferingCatalogSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseOfferingCatalog() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseOfferingCatalog() </code> is <code> true. 
     *              </code> 
     */
    public function getCourseOfferingCatalogSession();


    /**
     *  Gets the <code> OsidSession </code> associated with assigning course 
     *  offerings to course catalogs. 
     *
     *  @return object osid_course_CourseOfferingCatalogAssignmentSession a 
     *          <code> CourseOfferingCatalogAssignmentSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseOfferingCatalogAssignment() </code> is <code> 
     *          false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseOfferingCatalogAssignment() </code> is 
     *              <code> true. </code> 
     */
    public function getCourseOfferingCatalogAssignmentSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the term lookup 
     *  service. 
     *
     *  @return object osid_course_TermLookupSession a <code> 
     *          TermLookupSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsTermLookup() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTermLookup() </code> is <code> true. </code> 
     */
    public function getTermLookupSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the term lookup 
     *  service for the given course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @return object osid_course_TermLookupSession a <code> 
     *          TermLookupSession </code> 
     *  @throws osid_NotFoundException no <code> CourseCatalog </code> found 
     *          by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsTermLookup() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTermLookup() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getTermLookupSessionForCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Gets the <code> OsidSession </code> associated with the term search 
     *  service. 
     *
     *  @return object osid_course_TermSearchSession a <code> 
     *          TermSearchSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsTermSearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTermSearch() </code> is <code> true. </code> 
     */
    public function getTermSearchSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the term search 
     *  service for the given course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @return object osid_course_TermSearchSession a <code> 
     *          TermSearchSession </code> 
     *  @throws osid_NotFoundException no course catalog found by the given 
     *          <code> Id </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsTermSearch() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTermSearch() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getTermSearchSessionForCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Gets the <code> OsidSession </code> associated with the term 
     *  administration service. 
     *
     *  @return object osid_course_TermAdminSession a <code> TermAdminSession 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsTermAdmin() </code> 
     *          is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTermAdmin() </code> is <code> true. </code> 
     */
    public function getTermAdminSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the term 
     *  administration service for the given course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @return object osid_course_TermAdminSession a <code> TermAdminSession 
     *          </code> 
     *  @throws osid_NotFoundException no course catalog found by the given 
     *          <code> Id </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsTermAdmin() </code> 
     *          or <code> supportsVisibleFederation() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTermAdmin() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getTermAdminSessionForCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Gets the <code> OsidSession </code> associated with the term 
     *  notification service. 
     *
     *  @return object osid_course_TermAdminSession a <code> 
     *          TermNotificationSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsTermNotification() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTermNotification() </code> is <code> true. </code> 
     */
    public function getTermNotificationSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the term 
     *  notification service for the given course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @return object osid_course_TermNotificationSession a <code> 
     *          TermNotificationSession </code> 
     *  @throws osid_NotFoundException no course catalog found by the given 
     *          <code> Id </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsTermNotification() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTermNotification() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getTermNotificationSessionForCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Gets the term hierarchy traversal session. 
     *
     *  @return object osid_course_TermHierarchySession <code> a 
     *          TermHierarchySession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsTermHierarchy() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTermHierarchy() </code> is <code> true. </code> 
     */
    public function getTermHierarchySession();


    /**
     *  Gets the term hierarchy design session. 
     *
     *  @return object osid_course_TermHierarchyDesignSession a <code> 
     *          TermHierarchyDesignSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsTermHierarchyDesign() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTermHierarchyDesign() </code> is <code> true. 
     *              </code> 
     */
    public function getTermHierarchyDesignSession();


    /**
     *  Gets the <code> OsidSession </code> to lookup term/catalog mappings. 
     *
     *  @return object osid_course_TermCatalogSession a <code> 
     *          TermCatalogSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsTermCatalog() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTermCatalog() </code> is <code> true. </code> 
     */
    public function getTermCatalogSession();


    /**
     *  Gets the <code> OsidSession </code> associated with assigning terms to 
     *  course catalogs. 
     *
     *  @return object osid_course_TermCatalogAssignmentSession a <code> 
     *          TermCatalogAssignmentSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsTermCatalogAssignment() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTermCatalogAssignment() </code> is <code> true. 
     *              </code> 
     */
    public function getTermCatalogAssignmentSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the topic lookup 
     *  service. 
     *
     *  @return object osid_course_TopicLookupSession a <code> 
     *          TopicLookupSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsTopicLookup() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTopicLookup() </code> is <code> true. </code> 
     */
    public function getTopicLookupSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the topic lookup 
     *  service for the given course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> courseCatalog </code> 
     *  @return object osid_course_TopicLookupSession a <code> 
     *          TopicLookupSession </code> 
     *  @throws osid_NotFoundException no <code> CourseCatalog </code> found 
     *          by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsTopicLookup() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTopicLookup() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getTopicLookupSessionForCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Gets the <code> OsidSession </code> associated with the topic search 
     *  service. 
     *
     *  @return object osid_course_TopicSearchSession a <code> 
     *          TopicSearchSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsTopicSearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTopicSearch() </code> is <code> true. </code> 
     */
    public function getTopicSearchSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the topic search 
     *  service for the given course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @return object osid_course_TopicSearchSession a <code> 
     *          TopicSearchSession </code> 
     *  @throws osid_NotFoundException no course catalog found by the given 
     *          <code> Id </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsTopicSearch() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTopicSearch() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getTopicSearchSessionForCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Gets the <code> OsidSession </code> associated with the topic 
     *  administration service. 
     *
     *  @return object osid_course_TopicAdminSession a <code> 
     *          TopicAdminSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsTopicAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTopicAdmin() </code> is <code> true. </code> 
     */
    public function getTopicAdminSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the topic 
     *  administration service for the given course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @return object osid_course_TopicAdminSession a <code> 
     *          TopicAdminSession </code> 
     *  @throws osid_NotFoundException no course catalog found by the given 
     *          <code> Id </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsTopicAdmin() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTopicAdmin() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getTopicAdminSessionForCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Gets the <code> OsidSession </code> associated with the topic 
     *  notification service. 
     *
     *  @return object osid_course_TopicAdminSession a <code> 
     *          TopicNotificationSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsTopicNotification() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTopicNotification() </code> is <code> true. 
     *              </code> 
     */
    public function getTopicNotificationSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the topic 
     *  notification service for the given course catalog. 
     *
     *  @param object osid_id_Id $courseCatalogId the <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @return object osid_course_TopicNotificationSession a <code> 
     *          TopicNotificationSession </code> 
     *  @throws osid_NotFoundException no course catalog found by the given 
     *          <code> Id </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsTopicNotification() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTopicNotification() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true 
     *              </code> 
     */
    public function getTopicNotificationSessionForCatalog(osid_id_Id $courseCatalogId);


    /**
     *  Gets the topic hierarchy traversal session. 
     *
     *  @return object osid_course_TopicHierarchySession <code> a 
     *          TopicHierarchySession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsTopicHierarchy() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTopicHierarchy() </code> is <code> true. </code> 
     */
    public function getTopicHierarchySession();


    /**
     *  Gets the topic hierarchy design session. 
     *
     *  @return object osid_course_TopicHierarchyDesignSession a <code> 
     *          TopicHierarchyDesignSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsTopicHierarchyDesign() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTopicHierarchyDesign() </code> is <code> true. 
     *              </code> 
     */
    public function getTopicHierarchyDesignSession();


    /**
     *  Gets the <code> OsidSession </code> to lookup topic/catalog mappings. 
     *
     *  @return object osid_course_TopicCatalogSession a <code> 
     *          TopicCatalogSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsTopicCatalog() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTopicCatalog() </code> is <code> true. </code> 
     */
    public function getTopicCatalogSession();


    /**
     *  Gets the <code> OsidSession </code> associated with assigning topics 
     *  to course catalogs. 
     *
     *  @return object osid_course_TopicCatalogAssignmentSession a <code> 
     *          TopicCatalogAssignmentSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsTopicCatalogAssignment() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTopicCatalogAssignment() </code> is <code> true. 
     *              </code> 
     */
    public function getTopicCatalogAssignmentSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the course catalog 
     *  lookup service. 
     *
     *  @return object osid_course_CourseCatalogLookupSession a <code> 
     *          CourseCatalogLookupSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseCatalogLookup() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseCatalogLookup() </code> is <code> true. 
     *              </code> 
     */
    public function getCourseCatalogLookupSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the course catalog 
     *  search service. 
     *
     *  @return object osid_course_CourseCatalogSearchSession a <code> 
     *          CourseCatalogSearchSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseCatalogSearch() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseCatalogSearch() </code> is <code> true. 
     *              </code> 
     */
    public function getCourseCatalogSearchSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the course catalog 
     *  administrative service. 
     *
     *  @return object osid_course_CourseCatalogAdminSession a <code> 
     *          CourseCatalogAdminSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseCatalogAdmin() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseCatalogAdmin() </code> is <code> true. 
     *              </code> 
     */
    public function getCourseCatalogAdminSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the course catalog 
     *  notification service. 
     *
     *  @return object osid_course_CourseCatalogNotificationSession a <code> 
     *          CourseCatalogNotificationSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseCatalogNotification() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseCatalogNotification() </code> is <code> 
     *              true. </code> 
     */
    public function getCourseCatalogNotificationSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the course catalog 
     *  hierarchy service. 
     *
     *  @return object osid_course_CourseCatalogHierarchySession a <code> 
     *          CourseCatalogHierarchySession </code> for course catalogs 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseCatalogHierarchy() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseCatalogHierarchy() </code> is <code> true. 
     *              </code> 
     */
    public function getCourseCatalogHierarchySession();


    /**
     *  Gets the <code> OsidSession </code> associated with the course catalog 
     *  hierarchy design service. 
     *
     *  @return object osid_course_CourseCatalogHierarchyDesignSession a 
     *          <code> HierarchyDesignSession </code> for course catalogs 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCourseCatalogHierarchyDesign() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCourseCatalogHierarchyDesign() </code> is <code> 
     *              true. </code> 
     */
    public function getCourseCatalogHierarchyDesignSession();


    /**
     *  Gets the resource service for accessing resources used in course 
     *  offerings. 
     *
     *  @return object osid_resource_ResourceManager a <code> ResourceManager 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented <code> . </code> 
     */
    public function getResourceManager();


    /**
     *  Gets the calendar service for accessing calendars used in course 
     *  offerings. 
     *
     *  @return object osid_calendaring_CalendarManager a <code> 
     *          CalendarManager </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented <code> . </code> 
     */
    public function getCalendarManager();


    /**
     *  Gets the learning objective service for accessing learning objectives 
     *  used in course offerings. 
     *
     *  @return object osid_learning_ObjectiveManager an <code> 
     *          ObjectiveManager </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented <code> . </code> 
     */
    public function getLearningManager();

}
