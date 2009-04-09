<?php

/**
 * osid_repository_CompositionLookupSession
 * 
 *     Specifies the OSID definition for osid_repository_CompositionLookupSession.
 * 
 * Copyright (C) 2008 Massachusetts Institute of Technology. All Rights 
 * Reserved. 
 * 
 *     This Work is being provided by the copyright holder(s) subject to the 
 *     following license. By obtaining, using and/or copying this Work, you 
 *     agree that you have read, understand, and will comply with the 
 *     following terms and conditions. 
 *     
 *     This Work and the information contained herein is provided on an "AS 
 *     IS" basis. The Massachusetts Institute of Technology, the Open 
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
 * @package org.osid.repository
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session provides methods for retrieving <code> Composition </code> 
 *  objects. The <code> Composition </code> represents a collection of <code> 
 *  Assets. </code> </p> 
 *  
 *  <p> This session defines views that offer differing behaviors when 
 *  retrieving multiple objects. </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered 
 *      </li> 
 *      <li> plenary view: provides a complete and ordered result set or is an 
 *      error condition </li> 
 *      <li> isolated repository view: All lookup methods in this session 
 *      operate, retrieve and pertain to compositions defined explicitly in 
 *      the current repository. Using an isolated view is useful for managing 
 *      compositions with the <code> CompositionAdminSession. </code> </li> 
 *      <li> federated repository view: All composition methods in this 
 *      session operate, retrieve and pertain to all compositions defined in 
 *      this repository and any other compositions implicitly available in 
 *      this repository through repository inheritence. </li> 
 *  </ul>
 *  Generally, the comparative view should be used for most applications as it 
 *  permits operation even if there is data that cannot be accessed.. For 
 *  example, a browsing application may only need to examine the <code> 
 *  Composition </code> it can access, without breaking execution. However, an 
 *  administrative application may require a complete set of <code> 
 *  Composition </code> objects to be returned. Compositions may have an 
 *  additional records indicated by their respective record types. The record 
 *  may not be accessed through a cast of the <code> Composition. </code> </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_CompositionLookupSession
    extends osid_OsidSession
{


    /**
     *  Gets the <code> Repository </code> <code> Id </code> associated with 
     *  this session. 
     *
     *  @return object osid_id_Id the <code> Repository Id </code> associated 
     *          with this session 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRepositoryId();


    /**
     *  Gets the <code> Repository </code> associated with this session. 
     *
     *  @return object osid_repository_Repository the <code> Repository 
     *          </code> associated with this session 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRepository();


    /**
     *  Tests if this user can perform <code> Composition </code> lookups. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupCompositions();


    /**
     *  The returns from the lookup methods may omit or translate elements 
     *  based on this session, such as authorization, and not result in an 
     *  error. This view is used when greater interoperability is desired at 
     *  the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeCompositionView();


    /**
     *  A complete view of the <code> Composition </code> returns is desired. 
     *  Methods will return what is requested or result in an error. This view 
     *  is used when greater precision is desired at the expense of 
     *  interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryCompositionView();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include compositions in repositories which are children of this 
     *  repository in the repository hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedRepositoryView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts lookups to this repository only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedRepositoryView();


    /**
     *  Gets the <code> Composition </code> specified by its <code> Id. 
     *  </code> In plenary mode, the exact <code> Id </code> is found or a 
     *  <code> NOT_FOUND </code> results. Otherwise, the returned <code> 
     *  Composition </code> may have a different <code> Id </code> than 
     *  requested, such as the case where a duplicate <code> Id </code> was 
     *  assigned to a <code> Composition </code> and retained for compatility. 
     *
     *  @param object osid_id_Id $compositionId <code> Id </code> of the 
     *          <code> Composiiton </code> 
     *  @return object osid_repository_Composition the composition 
     *  @throws osid_NotFoundException <code> compositionId </code> not found 
     *  @throws osid_NullArgumentException <code> compositionId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function getComposition(osid_id_Id $compositionId);


    /**
     *  Gets a <code> CompositionList </code> corresponding to the given 
     *  <code> IdList. </code> In plenary mode, the returned list contains all 
     *  of the compositions specified in the <code> Id </code> list, in the 
     *  order of the list, including duplicates, or an error results if an 
     *  <code> Id </code> in the supplied list is not found or inaccessible. 
     *  Otherwise, inaccessible <code> Compositions </code> may be omitted 
     *  from the list and may present the elements in any order including 
     *  returning a unique set. 
     *
     *  @param object osid_id_IdList $compositionIdList the list of <code> Ids 
     *          </code> to rerieve 
     *  @return object osid_repository_CompositionList the returned <code> 
     *          Composition list </code> 
     *  @throws osid_NotFoundException an <code> Id was </code> not found 
     *  @throws osid_NullArgumentException <code> compositionIdList </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCompositionsByIds(osid_id_IdList $compositionIdList);


    /**
     *  Gets a <code> CompositionList </code> corresponding to the given 
     *  composition genus <code> Type </code> which does not include 
     *  compositions of types derived from the specified <code> Type. </code> 
     *  In plenary mode, the returned list contains all known compositions or 
     *  an error results. Otherwise, the returned list may contain only those 
     *  compositions that are accessible through this session. In both cases, 
     *  the order of the set is not specified. 
     *
     *  @param object osid_type_Type $compositionGenusType a composition genus 
     *          type 
     *  @return object osid_repository_CompositionList the returned <code> 
     *          Composition list </code> 
     *  @throws osid_NullArgumentException <code> compositionGenusType </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCompositionsByGenusType(osid_type_Type $compositionGenusType);


    /**
     *  Gets a <code> CompositionList </code> corresponding to the given 
     *  composition genus <code> Type </code> and include any additional 
     *  compositions with genus types derived from the specified <code> Type. 
     *  </code> In plenary mode, the returned list contains all known 
     *  compositions or an error results. Otherwise, the returned list may 
     *  contain only those compositions that are accessible through this 
     *  session. In both cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $compositionGenusType a composition genus 
     *          type 
     *  @return object osid_repository_CompositionList the returned <code> 
     *          Composition list </code> 
     *  @throws osid_NullArgumentException <code> compositionGenusType </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCompositionsByParentGenusType(osid_type_Type $compositionGenusType);


    /**
     *  Gets a <code> CompositionList </code> containing the given composition 
     *  record <code> Type. </code> In plenary mode, the returned list 
     *  contains all known compositions or an error results. Otherwise, the 
     *  returned list may contain only those compositions that are accessible 
     *  through this session. In both cases, the order of the set is not 
     *  specified. 
     *
     *  @param object osid_type_Type $compositionRecordType a composition 
     *          record type 
     *  @return object osid_repository_CompositionList the returned <code> 
     *          Composition list </code> 
     *  @throws osid_NullArgumentException <code> compositionRecordType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCompositionsByRecordType(osid_type_Type $compositionRecordType);


    /**
     *  Gets all <code> Compositions. </code> In plenary mode, the returned 
     *  list contains all known compositions or an error results. Otherwise, 
     *  the returned list may contain only those compositions that are 
     *  accessible through this session. In both cases, the order of the set 
     *  is not specified. 
     *
     *  @return object osid_repository_CompositionList a list of <code> 
     *          Compositions </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCompositions();

}
