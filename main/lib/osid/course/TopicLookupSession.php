<?php

/**
 * osid_course_TopicLookupSession
 * 
 *     Specifies the OSID definition for osid_course_TopicLookupSession.
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

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session provides methods for retrieving <code> Topic </code> 
 *  objects. The <code> Topic </code> represents a subject category in which 
 *  courses can be tagged. </p> 
 *  
 *  <p> This session defines views that offer differing behaviors when 
 *  retrieving multiple objects. </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered 
 *      </li> 
 *      <li> plenary view: provides a complete set or is an error condition 
 *      </li> 
 *      <li> isolated course catalog view: All topic methods in this session 
 *      operate, retrieve and pertain to topics defined explicitly in the 
 *      current course catalog. Using an isolated view is useful for managing 
 *      <code> Topics </code> with the <code> TopicAdminSession. </code> </li> 
 *      <li> federated course catalog view: All topic methods in this session 
 *      operate, retrieve and pertain to all topics defined in this course 
 *      catalog and any other topics implicitly available in this course 
 *      catalog through course catalog inheritence. </li> 
 *  </ul>
 *  Generally, the comparative view should be used for most applications as it 
 *  permits operation even if there is data that cannot be accessed. The 
 *  methods <code> useFederatedCourseCatalogView() </code> and <code> 
 *  useIsolatedCourseCatalogView() </code> behave as a radio group and one 
 *  shou </p>
 * 
 * @package org.osid.course
 */
interface osid_course_TopicLookupSession
    extends osid_OsidSession
{


    /**
     *  Gets the <code> CourseCatalog </code> <code> Id </code> associated 
     *  with this session. 
     *
     *  @return object osid_id_Id the <code> CourseCatalog Id </code> 
     *          associated with this session 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogId();


    /**
     *  Gets the <code> CourseCatalog </code> associated with this session. 
     *
     *  @return object osid_course_CourseCatalog the course catalog 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalog();


    /**
     *  Tests if this user can perform <code> Topic </code> lookups. A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known all methods in this session will result in 
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupTopics();


    /**
     *  The returns from the lookup methods may omit or translate elements 
     *  based on this session, such as authorization, and not result in an 
     *  error. This view is used when greater interoperability is desired at 
     *  the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeTopicView();


    /**
     *  A complete view of the <code> Topic </code> returns is desired. 
     *  Methods will return what is requested or result in an error. This view 
     *  is used when greater precision is desired at the expense of 
     *  interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryTopicView();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include topics in course catalogs which are children of this course 
     *  catalog in the course catalog hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedCourseCatalogView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts lookups to this course catalog only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedCourseCatalogView();


    /**
     *  Gets the <code> Topic </code> specified by its <code> Id. </code> In 
     *  plenary mode, the exact <code> Id </code> is found or a <code> 
     *  NOT_FOUND </code> results. Otherwise, the returned <code> Topic 
     *  </code> may have a different <code> Id </code> than requested, such as 
     *  the case where a duplicate <code> Id </code> was assigned to a <code> 
     *  Topic </code> and retained for compatibility. 
     *
     *  @param object osid_id_Id $topicId <code> Id </code> of the <code> 
     *          Topic </code> 
     *  @return object osid_course_Topic the topic 
     *  @throws osid_NotFoundException <code> topicId </code> not found 
     *  @throws osid_NullArgumentException <code> topicId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function getTopic(osid_id_Id $topicId);


    /**
     *  Gets a <code> TopicList </code> corresponding to the given <code> 
     *  IdList. </code> In plenary mode, the returned list contains all of the 
     *  topics specified in the <code> Id </code> list, in the order of the 
     *  list, including duplicates, or an error results if an <code> Id 
     *  </code> in the supplied list is not found or inaccessible. Otherwise, 
     *  inaccessible <code> Topics </code> may be omitted from the list and 
     *  may present the elements in any order including returning a unique 
     *  set. 
     *
     *  @param object osid_id_IdList $topicIdList the list of <code> Ids 
     *          </code> to rerieve 
     *  @return object osid_course_TopicList the returned <code> Topic list 
     *          </code> 
     *  @throws osid_NotFoundException an <code> Id was </code> not found 
     *  @throws osid_NullArgumentException <code> topicIdList </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopicsByIds(osid_id_IdList $topicIdList);


    /**
     *  Gets a <code> TopicList </code> corresponding to the given subject 
     *  genus <code> Type </code> which does not include topics of types 
     *  derived from the specified <code> Type. </code> In plenary mode, the 
     *  returned list contains all known topics or an error results. 
     *  Otherwise, the returned list may contain only those topics that are 
     *  accessible through this session. In both cases, the order of the set 
     *  is not specified. 
     *
     *  @param object osid_type_Type $topicGenusType a topic genus type 
     *  @return object osid_course_TopicList the returned <code> Topic list 
     *          </code> 
     *  @throws osid_NullArgumentException <code> topicGenusType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopicsByGenusType(osid_type_Type $topicGenusType);


    /**
     *  Gets a <code> TopicList </code> corresponding to the given topic genus 
     *  <code> Type </code> and include any additional topics with genus types 
     *  derived from the specified <code> Type. </code> In plenary mode, the 
     *  returned list contains all known topics or an error results. 
     *  Otherwise, the returned list may contain only those topics that are 
     *  accessible through this session. In both cases, the order of the set 
     *  is not specified. 
     *
     *  @param object osid_type_Type $topicGenusType a topic genus type 
     *  @return object osid_course_TopicList the returned <code> Topic list 
     *          </code> 
     *  @throws osid_NullArgumentException <code> topicGenusType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopicsByParentGenusType(osid_type_Type $topicGenusType);


    /**
     *  Gets a <code> TopicList </code> containing the given topic record 
     *  <code> Type. </code> In plenary mode, the returned list contains all 
     *  known topics or an error results. Otherwise, the returned list may 
     *  contain only those topics that are accessible through this session. In 
     *  both cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $topicRecordType a topic record type 
     *  @return object osid_course_TopicList the returned <code> Topic list 
     *          </code> 
     *  @throws osid_NullArgumentException <code> topicRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopicsByRecordType(osid_type_Type $topicRecordType);


    /**
     *  Gets all <code> Topics. </code> In plenary mode, the returned list 
     *  contains all known topics or an error results. Otherwise, the returned 
     *  list may contain only those topics that are accessible through this 
     *  session. In both cases, the order of the set is not specified. 
     *
     *  @return object osid_course_TopicList a list of <code> Topics </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTopics();

}
