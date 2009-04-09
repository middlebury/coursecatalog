<?php

/**
 * osid_type_TypeLookupSession
 * 
 *     Specifies the OSID definition for osid_type_TypeLookupSession.
 * 
 * Copyright (C) 2002-2007 Massachusetts Institute of Technology. All Rights 
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
 * @package org.osid.type
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session retrieves Types. A single Type can be retrieved using 
 *  <code> getType() </code> and all types known to this service can be 
 *  accessed via <code> getTypes() </code> . </p>
 * 
 * @package org.osid.type
 */
interface osid_type_TypeLookupSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can perform <code> Type </code> lookups. A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known all methods in this session will result in 
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer lookup operations. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupTypes();


    /**
     *  Gets a <code> Type </code> by its string representation which is a 
     *  combination of the authority and identifier. This method only returns 
     *  the <code> Type </code> if it is known by the given identification 
     *  components. 
     *
     *  @param string $namespace the identifier namespace 
     *  @param string $identifier the identifier 
     *  @param string $authority the authority 
     *  @return object osid_type_Type the <code> Type </code> 
     *  @throws osid_NotFoundException the type is not found 
     *  @throws osid_NullArgumentException <code> null </code> argument 
     *          provided 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getType($namespace, $identifier, $authority);


    /**
     *  Tests if the given <code> Type </code> is known. 
     *
     *  @param object osid_type_Type $type the <code> Type </code> to look for 
     *  @return boolean <code> true </code> if the given <code> Type </code> 
     *          is known, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> type </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasType(osid_type_Type $type);


    /**
     *  Gets all the known Types by domain. 
     *
     *  @param string $domain the domain 
     *  @return object osid_type_TypeList the list of <code> Types </code> 
     *          with the given domain 
     *  @throws osid_NullArgumentException <code> domain </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTypesByDomain($domain);


    /**
     *  Gets all the known Types by authority. 
     *
     *  @param string $authority the authority 
     *  @return object osid_type_TypeList the list of <code> Types </code> 
     *          with the given authority 
     *  @throws osid_NullArgumentException <code> authority </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException respect my authoritay 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTypesByAuthority($authority);


    /**
     *  Gets all the known Types by domain and authority 
     *
     *  @param string $domain the domain 
     *  @param string $authority the authority 
     *  @return object osid_type_TypeList the list of <code> Types </code> 
     *          with the given domain and authority 
     *  @throws osid_NullArgumentException <code> domain </code> or <code> 
     *          authority </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTypesByDomainAndAuthority($domain, $authority);


    /**
     *  Gets all the known Types. 
     *
     *  @return object osid_type_TypeList the list of all known <code> Types 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTypes();

}
