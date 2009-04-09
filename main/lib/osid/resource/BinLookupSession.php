<?php

/**
 * osid_resource_BinLookupSession
 * 
 *     Specifies the OSID definition for osid_resource_BinLookupSession.
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
 * @package org.osid.resource
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session provides methods for retrieving <code> Bin </code> 
 *  objects. The <code> Bin </code> represents a collection resources. </p> 
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
 *  </ul>
 *  Generally, the comparative view should be used for most applications as it 
 *  permits operation even if there is data that cannot be accessed. For 
 *  example, a browsing application may only need to examine the <code> Bins 
 *  </code> it can access, without breaking execution. However, an 
 *  administrative application may require all <code> Bin </code> elements to 
 *  be available. Bins may have an additional records indicated by their 
 *  respective record types. The record may not be accessed through a cast of 
 *  the <code> Bin. </code> </p>
 * 
 * @package org.osid.resource
 */
interface osid_resource_BinLookupSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can perform <code> Bin </code> lookups. A return of 
     *  true does not guarantee successful authorization. A return of false 
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
    public function canLookupBins();


    /**
     *  The returns from the lookup methods may omit or translate elements 
     *  based on this session, such as authorization, and not result in an 
     *  error. This view is used when greater interoperability is desired at 
     *  the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeBinView();


    /**
     *  A complete view of the <code> Bin </code> returns is desired. Methods 
     *  will return what is requested or result in an error. This view is used 
     *  when greater precision is desired at the expense of interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryBinView();


    /**
     *  Gets the <code> Bin </code> specified by its <code> Id. </code> In 
     *  plenary mode, the exact <code> Id </code> is found or a <code> 
     *  NOT_FOUND </code> results. Otherwise, the returned <code> Bin </code> 
     *  may have a different <code> Id </code> than requested, such as the 
     *  case where a duplicate <code> Id </code> was assigned to a <code> Bin 
     *  </code> and retained for compatibility. 
     *
     *  @param object osid_id_Id $binId <code> Id </code> of the <code> Bin 
     *          </code> 
     *  @return object osid_resource_Bin the bin 
     *  @throws osid_NotFoundException <code> binId </code> not found 
     *  @throws osid_NullArgumentException <code> binId </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function getRepository(osid_id_Id $binId);


    /**
     *  Gets a <code> RepositoryList </code> corresponding to the given <code> 
     *  IdList. </code> In plenary mode, the returned list contains all of the 
     *  repositories specified in the <code> Id </code> list, in the order of 
     *  the list, including duplicates, or an error results if an <code> Id 
     *  </code> in the supplied list is not found or inaccessible. Otherwise, 
     *  inaccessible <code> Repositories </code> may be omitted from the list 
     *  and may present the elements in any order including returning a unique 
     *  set. 
     *
     *  @param object osid_id_IdList $repositoryIdList the list of <code> Ids 
     *          </code> to rerieve 
     *  @return object osid_repository_RepositoryList the returned <code> 
     *          Repository list </code> 
     *  @throws osid_NotFoundException an <code> Id was </code> not found 
     *  @throws osid_NullArgumentException <code> repositoryIdList </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRepositoriesByIds(osid_id_IdList $repositoryIdList);


    /**
     *  Gets a <code> RepositoryList </code> corresponding to the given 
     *  repository genus <code> Type </code> which does not include 
     *  repositories of types derived from the specified <code> Type. </code> 
     *  In plenary mode, the returned list contains all known repositories or 
     *  an error results. Otherwise, the returned list may contain only those 
     *  repositories that are accessible through this session. In both cases, 
     *  the order of the set is not specified. 
     *
     *  @param object osid_type_Type $repositoryGenusType a repository genus 
     *          type 
     *  @return object osid_repository_RepositoryList the returned <code> 
     *          Repository list </code> 
     *  @throws osid_NullArgumentException <code> repositoryGenusType </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRepositoriesByGenusType(osid_type_Type $repositoryGenusType);


    /**
     *  Gets a <code> RepositoryList </code> corresponding to the given 
     *  repository genus <code> Type </code> and include any additional 
     *  repositories with genus types derived from the specified <code> Type. 
     *  </code> In plenary mode, the returned list contains all known 
     *  repositories or an error results. Otherwise, the returned list may 
     *  contain only those repositories that are accessible through this 
     *  session. In both cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $repositoryGenusType a repository genus 
     *          type 
     *  @return object osid_repository_RepositoryList the returned <code> 
     *          Repository list </code> 
     *  @throws osid_NullArgumentException <code> repositoryGenusType </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRepositoriesByParentGenusType(osid_type_Type $repositoryGenusType);


    /**
     *  Gets a <code> BinList </code> containing the given bin record <code> 
     *  Type. </code> In plenary mode, the returned list contains all known 
     *  bins or an error results. Otherwise, the returned list may contain 
     *  only those bins that are accessible through this session. In both 
     *  cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $repositoryInterfaceType a repository 
     *          interface type 
     *  @return object osid_repository_RepositoryList the returned <code> 
     *          Repository list </code> 
     *  @throws osid_NullArgumentException <code> repositoryInterfaceType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRepositoriesByRecordType(osid_type_Type $repositoryInterfaceType);


    /**
     *  Gets all <code> Repositories. </code> In plenary mode, the returned 
     *  list contains all known repositories or an error results. Otherwise, 
     *  the returned list may contain only those repositories that are 
     *  accessible through this session. In both cases, the order of the set 
     *  is not specified. 
     *
     *  @return object osid_repository_RepositoryList a list of <code> 
     *          Repositories </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRepositories();

}
