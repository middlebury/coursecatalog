<?php

/**
 * osid_authentication_Authentication
 * 
 *     Specifies the OSID definition for osid_authentication_Authentication.
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


/**
 *  <p><code> Authentication </code> represents an authentication credential 
 *  which contains set of <code> bytes </code> and a format Type. Once an 
 *  <code> Authentication </code> object is created from the <code> 
 *  AuthenticationValidationSession, </code> the credential data can be 
 *  extracted and sent to the remote peer for validation. The remote peer gets 
 *  another <code> Authentication </code> object as a result of validating the 
 *  serialized credential data. </p> 
 *  
 *  <p> An <code> Authentication </code> may or may not be valid. <code> 
 *  isValid() </code> should be checked before acting upon the <code> Agent 
 *  </code> identity to which the credential is mapped. </p>
 * 
 * @package org.osid.authentication
 */
interface osid_authentication_Authentication
{


    /**
     *  Gets the <code> Id </code> of the <code> Agent </code> identified in 
     *  this authentication credential. 
     *
     *  @return object osid_id_Id the <code> Agent Id </code> 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  The Agent should be determined at the time this credential is 
     *          created. 
     */
    public function getAgentId();


    /**
     *  Gets the <code> Agent </code> identified in this authentication 
     *  credential. 
     *
     *  @return object osid_authentication_Agent the <code> Agent </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgent();


    /**
     *  Tests whether or not the credential represented by this <code> 
     *  Authentication </code> is currently valid. A credential may be invalid 
     *  because it has been destroyed, expired, or is somehow no longer able 
     *  to be used. 
     *
     *  @return boolean <code> true </code> if this authentication credential 
     *          is valid, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  Any problem in determining the validity of this credential 
     *          should result in <code> false. </code> 
     */
    public function isValid();


    /**
     *  Gets the expiration date associated with this authentication 
     *  credential. Consumers should check for the existence of a an 
     *  expiration mechanism via <code> hasExpiration(). </code> 
     *
     *  @return DateTime the expiration date of this authentication credential 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance optional This method must be implemented if <code> 
     *              AuthenticationManager.supportsExpiration() </code> is 
     *              <code> true. </code> 
     */
    public function getExpiration();


    /**
     *  Gets the credential represented by the given <code> Type </code> for 
     *  transport to a remote service. 
     *
     *  @param object osid_type_Type $credentialType the credential format 
     *          <code> Type </code> 
     *  @return object the credential 
     *  @throws osid_NullArgumentException <code> credentialType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException this provider does not support 
     *          exporting credentials 
     *  @throws osid_UnsupportedException the given <code> credentialType 
     *          </code> is not supported 
     *  @compliance optional This method must be implemented if <code> 
     *              AuthenticationManager.supportsCredentialExport() </code> 
     *              is <code> true. </code> 
     *  @notes  A provider may support multiple credential formats for a 
     *          variety of applications. 
     */
    public function getCredential(osid_type_Type $credentialType);

}
