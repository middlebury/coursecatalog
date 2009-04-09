<?php

/**
 * osid_authentication_AuthenticationValidationSession
 * 
 *     Specifies the OSID definition for osid_authentication_AuthenticationValidationSession.
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

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session is the remote end of a transport link from the acquisition 
 *  session and validates authentication credentials sent to it. The basic 
 *  method, <code> authenticate() </code> accepts a credential, validates it 
 *  and returns an Authentication object containing the identity of the 
 *  authenticated user. The credential is indicated by a <code> Type. </code> 
 *  <code> AuthenticationManager.getCredentialTypes() </code> lists all the 
 *  credential types supported. </p> 
 *  
 *  <p> This OSID does not define any root interface for credentials and 
 *  challenge data. The object representing these are completely defined 
 *  within their <code> Type, </code> providing flexibility in adapting to a 
 *  variety of application environments. </p>
 * 
 * @package org.osid.authentication
 */
interface osid_authentication_AuthenticationValidationSession
    extends osid_OsidSession
{


    /**
     *  Validates and returns the authentication credential from the given 
     *  data. 
     *
     *  @param object $credential contains an authentication credential to be 
     *          validated 
     *  @param object osid_type_Type $credentialType specifies the credential 
     *          interface 
     *  @return object osid_authentication_Authentication the acquired 
     *          authentication credential 
     *  @throws osid_InvalidArgumentException <code> credential </code> is not 
     *          of <code> credentialType </code> 
     *  @throws osid_NullArgumentException <code> credentialType </code> or 
     *          <code> credential </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> credentialType </code> not 
     *          supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function authenticate($credential, osid_type_Type $credentialType);


    /**
     *  Gets data that can be used for a challenge to the peer attempting 
     *  authentication. 
     *
     *  @param object osid_type_Type $challengeType specifies the format of 
     *          the data challenge 
     *  @return object the acquired challenge data 
     *  @throws osid_NullArgumentException <code> challengeType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnimplementedException challenge response not available 
     *  @throws osid_UnsupportedException <code> challengeType </code> not 
     *          supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance optional This method must be implemented if <code> 
     *              AuthenticationManager.supportsChallenge() </code> is 
     *              <code> true. </code> 
     */
    public function getChallengeData(osid_type_Type $challengeType);

}
