<?php

/**
 * osid_configuration_RegistryLookupSession
 * 
 *     Specifies the OSID definition for osid_configuration_RegistryLookupSession.
 * 
 * Copyright (C) 2008 Massachusetts Institute of Technology. All Rights 
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
 * @package org.osid.configuration
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session provides methods for retrieving <code> Registry </code> 
 *  objects. The <code> Registry </code> represents a collection of parameter 
 *  definitions. </p> 
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
 *  example, a browsing application may only need to examine the <code> 
 *  Registry </code> elements it can access, without breaking execution. 
 *  However, an assessment may only be useful if all <code> Registry </code> 
 *  objects referenced by it are available, and a test-taking applicationmay 
 *  sacrifice some interoperability for the sake of precision. Registries may 
 *  have an additional interface indicated by their respective types. The 
 *  interface extension is accessed via the <code> Registry. </code> The 
 *  returns may not be cast directly from the returns in the lookup methods. 
 *  </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_RegistryLookupSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can perform <code> Registry </code> lookups. A 
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
    public function canLookupRegistries();


    /**
     *  The returns from the lookup methods may omit or translate elements 
     *  based on this session, such as authorization, and not result in an 
     *  error. This view is used when greater interoperability is desired at 
     *  the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeRegistryView();


    /**
     *  A complete view of the <code> Registry </code> returns is desired. 
     *  Methods will return what is requested or result in an error. This view 
     *  is used when greater precision is desired at the expense of 
     *  interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryRegistryView();


    /**
     *  Gets the <code> Registry </code> specified by its <code> Id. </code> 
     *  In plenary mode, the exact <code> Id </code> is found or a <code> 
     *  NOT_FOUND </code> results. Otherwise, the returned <code> Registry 
     *  </code> may have a different <code> Id </code> than requested, such as 
     *  the case where a duplicate <code> Id </code> was assigned to a <code> 
     *  Registry </code> and retained for compatibility. 
     *
     *  @param object osid_id_Id $registryId the <code> Id </code> of the 
     *          <code> Registry </code> to rerieve 
     *  @return object osid_configuration_Registry the <code> Registry </code> 
     *  @throws osid_NotFoundException no <code> Registry </code> found with 
     *          the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> registryId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRegistry(osid_id_Id $registryId);


    /**
     *  Gets a <code> RegistryList </code> corresponding to the given <code> 
     *  IdList. </code> In plenary mode, the returned list contains all of the 
     *  registries specified in the <code> Id </code> list, in the order of 
     *  the list, including duplicates, or an error results if an <code> Id 
     *  </code> in the supplied list is not found or inaccessible. Otherwise, 
     *  inaccessible <code> Registry </code> objects may be omitted from the 
     *  list and may present the elements in any order including returning a 
     *  unique set. 
     *
     *  @param object osid_id_IdList $registryIdList the list of <code> Ids 
     *          </code> to rerieve 
     *  @return object osid_configuration_RegistryList the returned <code> 
     *          Registry list </code> 
     *  @throws osid_NotFoundException an <code> Id was </code> not found 
     *  @throws osid_NullArgumentException <code> registryIdList </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRegistriesByIds(osid_id_IdList $registryIdList);


    /**
     *  Gets a <code> RegistryList </code> corresponding to the given registry 
     *  genus <code> Type </code> which does not include registries of types 
     *  derived from the specified <code> Type. </code> In plenary mode, the 
     *  returned list contains all known registries or an error results. 
     *  Otherwise, the returned list may contain only those registries that 
     *  are accessible through this session. In both cases, the order of the 
     *  set is not specified. 
     *
     *  @param object osid_type_Type $registryGenusType a registry genus type 
     *  @return object osid_configuration_RegistryList the returned <code> 
     *          Registry list </code> 
     *  @throws osid_NullArgumentException <code> registryGenusType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRegistriesByGenusType(osid_type_Type $registryGenusType);


    /**
     *  Gets a <code> RegistryList </code> corresponding to the given registry 
     *  genus <code> Type </code> and include any additional registries with 
     *  genus types derived from the specified <code> Type. </code> In plenary 
     *  mode, the returned list contains all known registries or an error 
     *  results. Otherwise, the returned list may contain only those 
     *  registries that are accessible through this session. In both cases, 
     *  the order of the set is not specified. 
     *
     *  @param object osid_type_Type $registryGenusType a registry genus type 
     *  @return object osid_configuration_RegistryList the returned <code> 
     *          Registry list </code> 
     *  @throws osid_NullArgumentException <code> registryGenusType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRegistriesByParentGenusType(osid_type_Type $registryGenusType);


    /**
     *  Gets a <code> RegistryList </code> corresponding to the given registry 
     *  interface <code> Type. </code> The set of registries implementing the 
     *  given interface type is returned. In plenary mode, the returned list 
     *  contains all known registries or an error results. Otherwise, the 
     *  returned list may contain only those registries that are accessible 
     *  through this session. In both cases, the order of the set is not 
     *  specified. 
     *
     *  @param object osid_type_Type $registryInterfaceType a registry 
     *          interface type 
     *  @return object osid_configuration_RegistryList the returned <code> 
     *          Registry list </code> 
     *  @throws osid_NullArgumentException <code> registryInterfaceType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRegistriesByInterfaceType(osid_type_Type $registryInterfaceType);


    /**
     *  Gets all <code> Registry </code> objects. In plenary mode, the 
     *  returned list contains all known registries or an error results. 
     *  Otherwise, the returned list may contain only those registries that 
     *  are accessible through this session. In both cases, the order of the 
     *  set is not specified. 
     *
     *  @return object osid_configuration_RegistryList a list of registries 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRegistries();

}
