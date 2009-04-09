<?php

/**
 * osid_authentication_Key
 * 
 *     Specifies the OSID definition for osid_authentication_Key.
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
 *  <p>The key represents cryptographic data managed by the authentication 
 *  service. An <code> Agent </code> maps to a <code> Key </code> and there is 
 *  only one <code> Key </code> per <code> Agent. </code> </p> 
 *  
 *  <p> <code> getKeyInterface() </code> should be used to retrieve the 
 *  interface corresponding to this Type. The existence of the interface must 
 *  not be assumed until requested at which point it is safe to cast into the 
 *  interface indicated by the type. </p>
 * 
 * @package org.osid.authentication
 */
interface osid_authentication_Key
{


    /**
     *  Gets the <code> Agent </code> corresponding to this key. 
     *
     *  @return object osid_authentication_Agent <code> the agent </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgent();


    /**
     *  Gets the type of this key. The <code> Type </code> explicitly 
     *  indicates the specification of the extension interface and implicitly 
     *  may define an object family. 
     *
     *  @return object osid_type_Type the type 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRecordTypeType();


    /**
     *  Tests if this key supports the given record <code> Type. </code> The 
     *  given type may be supported by the key type through inheritence. 
     *
     *  @param object osid_type_Type $keyRecordType a key record type 
     *  @return boolean <code> true </code> if this key supports the given 
     *          record <code> Type, </code> <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> keyRecordType </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasRecordType(osid_type_Type $keyRecordType);


    /**
     *  Gets the record corresponding to the given <code> Key </code> record 
     *  <code> Type. </code> This method must be used to retrieve an object 
     *  implementing the requested record interface along with all of its 
     *  ancestor interfaces. The <code> keyRecordType </code> may be the 
     *  <code> Type </code> returned in <code> getRecordTypes() </code> or any 
     *  of its parents in a <code> Type </code> hierarchy where <code> 
     *  hasRecordType(keyRecordType) </code> is <code> true </code> . 
     *
     *  @param object osid_type_Type $keyRecordType a key record type 
     *  @return object osid_authentication_KeyRecord the key record 
     *  @throws osid_NullArgumentException <code> repositoryRecordType </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> hasRecordType(keyRecordType) 
     *          </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getKeyReceord(osid_type_Type $keyRecordType);

}
