<?php

/**
 * osid_course_CourseProfile
 * 
 *     Specifies the OSID definition for osid_course_CourseProfile.
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

require_once(dirname(__FILE__)."/../OsidProfile.php");

/**
 *  <p>The course profile describes the interoperability among course 
 *  management services. </p>
 * 
 * @package org.osid.course
 */
interface osid_course_CourseProfile
    extends osid_OsidProfile
{


    /**
     *  Tests if any course catalog federation is exposed. Federation is 
     *  exposed when a specific course catalog may be identified, selected and 
     *  used to create a lookup or admin session. Federation is not exposed 
     *  when a set of catalogs appears as a single catalog. 
     *
     *  @return boolean <code> true </code> if visible federation is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsVisibleFederation();


    /**
     *  Tests if looking up courses is supported. 
     *
     *  @return boolean <code> true </code> if course lookup is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseLookup();


    /**
     *  Tests if searching courses is supported. 
     *
     *  @return boolean <code> true </code> if course search is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseSearch();


    /**
     *  Tests if course <code> </code> administrative service is supported. 
     *
     *  @return boolean <code> true </code> if course administration is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseAdmin();


    /**
     *  Tests if a course <code> </code> notification service is supported. 
     *
     *  @return boolean <code> true </code> if course notification is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseNotification();


    /**
     *  Tests if a course catalogging service is supported. 
     *
     *  @return boolean <code> true </code> if course catalogging is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseCatalog();


    /**
     *  Tests if a course catalogging service is supported. A course 
     *  catalogging service maps courses to catalogs. 
     *
     *  @return boolean <code> true </code> if course catalogging is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseCatalogAssignment();


    /**
     *  Tests if looking up course offerings is supported. 
     *
     *  @return boolean <code> true </code> if course offering lookup is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseOfferingLookup();


    /**
     *  Tests if searching course offerings is supported. 
     *
     *  @return boolean <code> true </code> if course offering search is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseOfferingSearch();


    /**
     *  Tests if course <code> </code> offering <code> </code> administrative 
     *  service is supported. 
     *
     *  @return boolean <code> true </code> if course offering administration 
     *          is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseOfferingAdmin();


    /**
     *  Tests if a course offering <code> </code> notification service is 
     *  supported. 
     *
     *  @return boolean <code> true </code> if course offering notification is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseOfferingNotification();


    /**
     *  Tests if course <code> </code> offering <code> </code> hierarchy 
     *  traversal service is supported. 
     *
     *  @return boolean <code> true </code> if course offering hierarchy is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseOfferingHierarchy();


    /**
     *  Tests if a course offering <code> </code> hierarchy design service is 
     *  supported. 
     *
     *  @return boolean <code> true </code> if course offering hierarchy 
     *          design is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseOfferingHierarchyDesign();


    /**
     *  Tests if the course offering hierarchy supports node sequencing. 
     *
     *  @return boolean <code> true </code> if course offering hierarchy node 
     *          sequencing is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseOfferingHierarchySequencing();


    /**
     *  Tests if a course offering catalogging service is supported. 
     *
     *  @return boolean <code> true </code> if course offering catalog is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseOfferingCatalog();


    /**
     *  Tests if a course offering catalogging service is supported. A 
     *  catalogging service maps course offerings to catalogs. 
     *
     *  @return boolean <code> true </code> if course offering catalogging is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseOfferingCatalogAssignment();


    /**
     *  Tests if looking up terms is supported. 
     *
     *  @return boolean <code> true </code> if term lookup is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTermLookup();


    /**
     *  Tests if searching terms is supported. 
     *
     *  @return boolean <code> true </code> if term search is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTermSearch();


    /**
     *  Tests if term <code> </code> administrative service is supported. 
     *
     *  @return boolean <code> true </code> if term administration is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTermAdmin();


    /**
     *  Tests if a term <code> </code> notification service is supported. 
     *
     *  @return boolean <code> true </code> if term notification is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTermNotification();


    /**
     *  Tests if term <code> </code> hierarchy traversal service is supported. 
     *
     *  @return boolean <code> true </code> if term hierarchy is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTermHierarchy();


    /**
     *  Tests if a term <code> </code> hierarchy design service is supported. 
     *
     *  @return boolean <code> true </code> if term hierarchy design is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTermHierarchyDesign();


    /**
     *  Tests if the term hierarchy supports node sequencing. 
     *
     *  @return boolean <code> true </code> if term hierarchy node sequencing 
     *          is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTermHierarchySequencing();


    /**
     *  Tests if a term catalogging service is supported. 
     *
     *  @return boolean <code> true </code> if term catalog is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTermCatalog();


    /**
     *  Tests if a term catalogging service is supported. A catalogging 
     *  service maps terms to catalogs. 
     *
     *  @return boolean <code> true </code> if term catalogging is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTermCatalogAssignment();


    /**
     *  Tests if looking up topics is supported. 
     *
     *  @return boolean <code> true </code> if topic lookup is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTopicLookup();


    /**
     *  Tests if searching topics is supported. 
     *
     *  @return boolean <code> true </code> if topic search is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTopicSearch();


    /**
     *  Tests if topic <code> </code> administrative service is supported. 
     *
     *  @return boolean <code> true </code> if topic administration is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTopicAdmin();


    /**
     *  Tests if a topic <code> </code> notification service is supported. 
     *
     *  @return boolean <code> true </code> if topic notification is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTopicNotification();


    /**
     *  Tests if topic <code> </code> hierarchy traversal service is 
     *  supported. 
     *
     *  @return boolean <code> true </code> if topic hierarchy is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTopicHierarchy();


    /**
     *  Tests if a topic <code> </code> hierarchy design service is supported. 
     *
     *  @return boolean <code> true </code> if topic hierarchy design is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTopicHierarchyDesign();


    /**
     *  Tests if the topic hierarchy supports node sequencing. 
     *
     *  @return boolean <code> true </code> if topic hierarchy node sequencing 
     *          is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTopicHierarchySequencing();


    /**
     *  Tests if a topic catalogging service is supported. 
     *
     *  @return boolean <code> true </code> if topic catalog is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTopicCatalog();


    /**
     *  Tests if a topic catalogging service is supported. A catalogging 
     *  service maps terms to catalogs. 
     *
     *  @return boolean <code> true </code> if topic catalogging is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTopicCatalogAssignment();


    /**
     *  Tests if looking up course catalogs is supported. 
     *
     *  @return boolean <code> true </code> if course catalog lookup is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseCatalogLookup();


    /**
     *  Tests if searching course catalogs is supported. 
     *
     *  @return boolean <code> true </code> if course catalog search is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseCatalogSearch();


    /**
     *  Tests if course <code> catalog </code> administrative service is 
     *  supported. 
     *
     *  @return boolean <code> true </code> if course catalog administration 
     *          is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseCatalogAdmin();


    /**
     *  Tests if a course catalog <code> </code> notification service is 
     *  supported. 
     *
     *  @return boolean <code> true </code> if course catalog notification is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseCatalogNotification();


    /**
     *  Tests for the availability of a course catalog hierarchy traversal 
     *  service. 
     *
     *  @return boolean <code> true </code> if course catalog hierarchy 
     *          traversal is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented in all 
     *              providers. 
     */
    public function supportsCourseCatalogHierarchy();


    /**
     *  Tests for the availability of a course catalog hierarchy design 
     *  service. 
     *
     *  @return boolean <code> true </code> if course catalog hierarchy design 
     *          is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseCatalogHierarchyDesign();


    /**
     *  Tests if the course catalog hierarchy supports node sequencing. 
     *
     *  @return boolean <code> true </code> if course catalog hierarchy node 
     *          sequencing is supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseCatalogSequencing();


    /**
     *  Gets the supported <code> Course </code> record interface types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> Course </code> record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseRecordTypes();


    /**
     *  Tests if the given <code> Course </code> record interface type is 
     *  supported. 
     *
     *  @param object osid_type_Type $courseRecordType a <code> Type </code> 
     *          indicating a <code> Course </code> record type 
     *  @return boolean <code> true </code> if the given <code> Type </code> 
     *          is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseRecordType(osid_type_Type $courseRecordType);


    /**
     *  Gets the supported <code> Course </code> search record interface 
     *  types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> Course </code> search record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseSearchRecordTypes();


    /**
     *  Tests if the given <code> Course </code> search record interface type 
     *  is supported. 
     *
     *  @param object osid_type_Type $courseSearchRecordType a <code> Type 
     *          </code> indicating a <code> Course </code> search record type 
     *  @return boolean <code> true </code> if the given search record type is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseSearchRecordType(osid_type_Type $courseSearchRecordType);


    /**
     *  Gets the supported <code> CourseOffering </code> record interface 
     *  types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> CourseOffering </code> record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingRecordTypes();


    /**
     *  Tests if the given <code> CourseOffering </code> record interface type 
     *  is supported. 
     *
     *  @param object osid_type_Type $courseOfferingRecordType a <code> Type 
     *          </code> indicating an <code> CourseOffering </code> record 
     *          type 
     *  @return boolean <code> true </code> if the given record type is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseOfferingRecordType(osid_type_Type $courseOfferingRecordType);


    /**
     *  Gets the supported <code> CourseOffering </code> search types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> CourseOffering </code> search types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseOfferingSearchTypes();


    /**
     *  Tests if the given <code> CourseOffering </code> search type is 
     *  supported. 
     *
     *  @param object osid_type_Type $courseOfferingSearchRecordType a <code> 
     *          Type </code> indicating an <code> CourseOffering </code> 
     *          search type 
     *  @return boolean <code> true </code> if the given <code> Type </code> 
     *          is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseOfferingSearchType(osid_type_Type $courseOfferingSearchRecordType);


    /**
     *  Gets the supported <code> Term </code> record interface types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> Term </code> record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermRecordTypes();


    /**
     *  Tests if the given <code> Term </code> record interface type is 
     *  supported. 
     *
     *  @param object osid_type_Type $termRecordType a <code> Type </code> 
     *          indicating a <code> Term </code> record type 
     *  @return boolean <code> true </code> if the given <code> Type </code> 
     *          is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTermRecordType(osid_type_Type $termRecordType);


    /**
     *  Gets the supported <code> Term </code> search record interface types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> Term </code> search record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermSearchRecordTypes();


    /**
     *  Tests if the given <code> Term </code> search record interface type is 
     *  supported. 
     *
     *  @param object osid_type_Type $termSearchRecordType a <code> Type 
     *          </code> indicating a <code> Term </code> search record type 
     *  @return boolean <code> true </code> if the given <code> Type </code> 
     *          is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTermSearchRecordType(osid_type_Type $termSearchRecordType);


    /**
     *  Gets the supported <code> Topic </code> record interface types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> Topic </code> record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopicRecordTypes();


    /**
     *  Tests if the given <code> Topic </code> record interface type is 
     *  supported. 
     *
     *  @param object osid_type_Type $topicRecordType a <code> Type </code> 
     *          indicating a <code> Topic </code> record type 
     *  @return boolean <code> true </code> if the given type is supported, 
     *          <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTopicRecordType(osid_type_Type $topicRecordType);


    /**
     *  Gets the supported <code> Topic </code> search record interface types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> Topic </code> search record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopicSearchRecordTypes();


    /**
     *  Tests if the given <code> Topic </code> search record interface type 
     *  is supported. 
     *
     *  @param object osid_type_Type $topicSearchRecordType a <code> Type 
     *          </code> indicating a <code> Topic </code> search record type 
     *  @return boolean <code> true </code> if the given Type is supported, 
     *          <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsTopicSearchRecordType(osid_type_Type $topicSearchRecordType);


    /**
     *  Gets the supported <code> CourseCatalog </code> record interface 
     *  types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> CourseCatalog </code> types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogRecordTypes();


    /**
     *  Tests if the given <code> CourseCatalog </code> record interface type 
     *  is supported. 
     *
     *  @param object osid_type_Type $courseCatalogrecordType a <code> Type 
     *          </code> indicating an <code> CourseCatalog </code> record type 
     *  @return boolean <code> true </code> if the given <code> Type </code> 
     *          is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseCatalogRecordType(osid_type_Type $courseCatalogrecordType);


    /**
     *  Gets the supported <code> CourseCatalog </code> search record 
     *  interface types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> CourseCatalog </code> search record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogSearchRecordTypes();


    /**
     *  Tests if the given <code> CourseCatalog </code> search record 
     *  interface type is supported. 
     *
     *  @param object osid_type_Type $courseCatalogrecordType a <code> Type 
     *          </code> indicating an <code> CourseCatalog </code> search 
     *          record type 
     *  @return boolean <code> true </code> if the given <code> Type </code> 
     *          is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCourseCatalogSearchRecordType(osid_type_Type $courseCatalogrecordType);

}
