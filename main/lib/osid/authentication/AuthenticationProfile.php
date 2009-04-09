<?php

/**
 * osid_authentication_AuthenticationProfile
 * 
 *     Specifies the OSID definition for osid_authentication_AuthenticationProfile.
 * 
 * Copyright (C) 2002-2008 Massachusetts Institute of Technology. All Rights 
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
 * @package org.osid.authentication
 */

require_once(dirname(__FILE__)."/../OsidProfile.php");

/**
 *  <p>The <code> AuthenticationProfile </code> describes the interoperability 
 *  among authentication services. </p>
 * 
 * @package org.osid.authentication
 */
interface osid_authentication_AuthenticationProfile
    extends osid_OsidProfile
{


    /**
     *  Tests is authentication acquisition is supported. Authentication 
     *  acquisition is responsible for acquiring client side authentication 
     *  credentials. 
     *
     *  @return boolean <code> true </code> if authentication acquisiiton is 
     *          supported <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAuthenticationAcquisition();


    /**
     *  Tests if authentication validation is supported. Authentication 
     *  validation verifies given authentication credentials and maps to an 
     *  agent identity. 
     *
     *  @return boolean <code> true </code> if authentication validation is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAuthenticationValidation();


    /**
     *  Tests if an agent lookup service is supported. An agent lookup service 
     *  defines methods to access agents. 
     *
     *  @return boolean <code> true </code> if agent lookup is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAgentLookup();


    /**
     *  Tests if an agent search service is supported. 
     *
     *  @return boolean <code> true </code> if agent search is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAgentSearch();


    /**
     *  Tests if an agent administrative service is supported. 
     *
     *  @return boolean <code> true </code> if agent admin is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAgentAdmin();


    /**
     *  Tests if agent notification is supported. Messages may be sent when 
     *  agents are created, modified, or deleted. 
     *
     *  @return boolean <code> true </code> if agent notification is supported 
     *          <code> , </code> <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAgentNotification();


    /**
     *  Tests if a key lookup service is supported. A key lookup service 
     *  defines methods to access keys. 
     *
     *  @return boolean <code> true </code> if key lookup is supported, <code> 
     *          false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsKeyLookup();


    /**
     *  Tests if a key administrative service is supported. 
     *
     *  @return boolean <code> true </code> if key admin is supported, <code> 
     *          false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsKeyAdmin();


    /**
     *  Tests if this authentication service supports a challenge-response 
     *  mechanism where credential validation service must implement a means 
     *  to generate challenge data. 
     *
     *  @return boolean <code> true </code> if this is a challenge-response 
     *          system, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsChallenge();


    /**
     *  Gets the supported challenge types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          challenge types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getChallengeTypes();


    /**
     *  Tests if the given challenge data type is supported. 
     *
     *  @param object osid_type_Type $peerChallengeType a <code> Type </code> 
     *          indicating a challenge data format 
     *  @return boolean <code> true </code> if the given Type is supported, 
     *          <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsChallengeType(osid_type_Type $peerChallengeType);


    /**
     *  Tests if <code> Authentication </code> objects can export serialzied 
     *  credentials for transport. 
     *
     *  @return boolean <code> true </code> if the given credentials export is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCredentialExport();


    /**
     *  Gets the supported credential types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          credential types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCredentialTypes();


    /**
     *  Tests if the given credential type is supported. 
     *
     *  @param object osid_type_Type $credentialType a <code> Type </code> 
     *          indicating an credential type 
     *  @return boolean <code> true </code> if the given Type is supported, 
     *          <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCredentialType(osid_type_Type $credentialType);


    /**
     *  Gets the supported <code> Agent </code> record types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> Agent </code> record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgentRecordTypes();


    /**
     *  Tests if the given <code> Agent </code> record type is supported. 
     *
     *  @param object osid_type_Type $agentRecordType a <code> Type </code> 
     *          indicating an <code> Agent </code> record type 
     *  @return boolean <code> true </code> if the given record Type is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAgentRecordType(osid_type_Type $agentRecordType);


    /**
     *  Gets the supported <code> Agent </code> search record types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> Agent </code> search record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgentSearchRecordTypes();


    /**
     *  Tests if the given <code> Agent </code> search record type is 
     *  supported. 
     *
     *  @param object osid_type_Type $agentSearchRecordType a <code> Type 
     *          </code> indicating an <code> Agent </code> search record type 
     *  @return boolean <code> true </code> if the given Type is supported, 
     *          <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAgentSearchRecordType(osid_type_Type $agentSearchRecordType);


    /**
     *  Gets the supported <code> Key </code> record types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> Key </code> record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getKeyRecordTypes();


    /**
     *  Tests if the given <code> Key </code> record type is supported. 
     *
     *  @param object osid_type_Type $keyRecordType a <code> Type </code> 
     *          indicating a <code> Key </code> type 
     *  @return boolean <code> true </code> if the given key record <code> 
     *          Type </code> is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsKeyRecordType(osid_type_Type $keyRecordType);


    /**
     *  Gets the supported key search record types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> Key </code> search record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getKeySearchRecordTypes();


    /**
     *  Tests if the given key search record type is supported. 
     *
     *  @param object osid_type_Type $keySearchRecordType a <code> Type 
     *          </code> indicating a <code> Key </code> search record type 
     *  @return boolean <code> true </code> if the given search record <code> 
     *          Type </code> is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsKeySearchRecordType(osid_type_Type $keySearchRecordType);

}
