<?php

/**
 * osid_authentication_AuthenticationManager
 * 
 *     Specifies the OSID definition for osid_authentication_AuthenticationManager.
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

require_once(dirname(__FILE__)."/../OsidManager.php");
require_once(dirname(__FILE__)."/AuthenticationProfile.php");

/**
 *  <p>The authentication manager provides access to authentication sessions 
 *  and provides interoperability tests for various aspects of this service. 
 *  The sessions included in this manager are: </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> <code> AuthenticationAcquisitionSession: </code> a session to 
 *      acquire credentials from a user and serialize them for transport to a 
 *      remote peer for authentication </li> 
 *      <li> <code> AuthenticationValidationSession: a </code> session to 
 *      receive and validate authentication credentials from a remote peer 
 *      wishing to authenticate </li> 
 *      <li> <code> AgentLookupSession: </code> a session to look up <code> 
 *      Agents </code> </li> 
 *      <li> <code> AgentSearchSession: </code> a session to search <code> 
 *      Agents </code> </li> 
 *      <li> <code> AgentAdminSession: </code> a session to create, modify and 
 *      delete <code> Agents </code> </li> 
 *      <li> <code> AgentNotificationSession: a </code> session to receive 
 *      messages pertaining to <code> Agent </code> changes </li> 
 *      <li> <code> KeyLookupSession: </code> a session to access the keys of 
 *      <code> Agents </code> </li> 
 *      <li> <code> KeyAdminSession: </code> a session to update the keys of 
 *      <code> Agents </code> </li> 
 *  </ul>
 *  The authentication manager provides a profile for determining 
 *  authentication process compatibility with regard to requiring data from a 
 *  challenge response mechanism to generate the credential with <code> 
 *  supportsChallenge(). </code> The authentication profile also tests for 
 *  supported Types. <code> supportsChallengeType() </code> and <code> 
 *  supportsAgentType(), </code> <code> supportsKeyType() </code> are methods 
 *  that can be used to determine is the desired <code> Types </code> are 
 *  supported. </p> 
 *  
 *  <p> Notifications for adds and changes to <code> Agents </code> is 
 *  available via the <code> getAgentNotificationSession() </code> method. 
 *  </p>
 * 
 * @package org.osid.authentication
 */
interface osid_authentication_AuthenticationManager
    extends osid_OsidManager,
            osid_authentication_AuthenticationProfile
{


    /**
     *  Gets an <code> AuthenticationAcquisitionSession </code> which is 
     *  responsible for acquiring authentication credentials on behalf of a 
     *  service client. 
     *
     *  @return object osid_authentication_AuthenticationAcquisitionSession an 
     *          acquisition session for this service 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsAuthenticationAcquisition() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAcquisition() </code> is <code> true. </code> 
     */
    public function getAuthenticationAcquisitionSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the <code> 
     *  AuthenticationValidation </code> service. 
     *
     *  @return object osid_authentication_AuthenticationValidationSession an 
     *          <code> AuthenticationValidationSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsAuthenticationValidation() </code> is <code> false 
     *          </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsValidation() </code> is <code> true. </code> 
     */
    public function getAuthenticationValidationSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the agent lookup 
     *  service. 
     *
     *  @return object osid_authentication_AgentLookupSession an <code> 
     *          AgentLookupSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsAgentLookup() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAgentLookup() </code> is <code> true. </code> 
     */
    public function getAgentLookupSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the agent search 
     *  service. 
     *
     *  @return object osid_authentication_AgentSearchSession an <code> 
     *          AgentSearchSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsAgentSearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAgentSearch() </code> is <code> true. </code> 
     */
    public function getAgentSearchSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the agent 
     *  administration service. 
     *
     *  @return object osid_authentication_AgentAdminSession an <code> 
     *          AgentAdminSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsAgentAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAgentAdmin() </code> is <code> true. </code> 
     */
    public function getAgentAdminSession();


    /**
     *  Gets the notification session for notifications pertaining to service 
     *  changes. 
     *
     *  @param object osid_authentication_AgentReceiver $receiver the agent 
     *          receiver interface 
     *  @return object osid_authentication_AgentNotificationSession an <code> 
     *          AgentNotificationSession </code> 
     *  @throws osid_NullArgumentException <code> receiver </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsAgentNotification() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAgentNotification() </code> is <code> true. 
     *              </code> 
     */
    public function getAgentNotificationSession(osid_authentication_AgentReceiver $receiver);


    /**
     *  Gets the <code> OsidSession </code> associated with the key lookup 
     *  service. 
     *
     *  @return object osid_authentication_KeyLookupSession the new <code> 
     *          KeyLookupSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsKeyLookup() </code> 
     *          is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsKeyLookup() </code> is <code> true. </code> 
     */
    public function getKeyLookupSession();


    /**
     *  Gets the <code> OsidSession </code> associated with the key 
     *  administration service. 
     *
     *  @return object osid_authentication_AgentAdminSession an <code> 
     *          KeyAdminSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsKeyAdmin() </code> 
     *          is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsKeyAdmin() </code> is <code> true. </code> 
     */
    public function getKeyAdminSession();

}
