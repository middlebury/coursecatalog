<?php

/**
 * osid_authentication_KeyForm
 * 
 *     Specifies the OSID definition for osid_authentication_KeyForm.
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
 *  <p>This is the form for creating and updating <code> Keys. </code> Various 
 *  data elements may be set here for use in the create and update methods in 
 *  the <code> KeyAdminSession. </code> For each data element that may be set, 
 *  metadata may be examined to provide display hints or data constraints. 
 *  </p>
 * 
 * @package org.osid.authentication
 */
interface osid_authentication_KeyForm
{


    /**
     *  Gets the metadata for the comment corresponding to this form 
     *  submission. The comment is used for describing the nature of the 
     *  change to the corresponding object for the purposes of logging and 
     *  auditing. 
     *
     *  @return object osid_Metadata metadata for the comment 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCommentMetadata();


    /**
     *  Sets a comment. 
     *
     *  @param string $comment the new comment 
     *  @throws osid_InvalidArgumentException <code> comment </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> comment </code> cannot be 
     *          modified 
     *  @throws osid_NullArgumentException <code> comment </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setComment($comment);


    /**
     *  Tests if ths form is in a valid state for submission. A form is valid 
     *  if all required data has been supplied compliant with any constraints. 
     *
     *  @return boolean <code> false </code> if there is a known error in this 
     *          form, <code> true </code> otherwise 
     *  @throws osid_OperationFailedException attempt to perform validation 
     *          failed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isValid();


    /**
     *  Tests if this form supports the given record <code> Type. </code> The 
     *  given record type may be supported by the object through 
     *  interface/type inheritence. This method should be checked before 
     *  retrieving the record interface. 
     *
     *  @param object osid_type_Type $keyRecordType a record type 
     *  @return boolean <code> true </code> if a record form of the given 
     *          record <code> Type </code> is available, <code> false </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> keyRecordType </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasRecordType(osid_type_Type $keyRecordType);


    /**
     *  Gets the <code> KeyFormRecord </code> interface corresponding to the 
     *  given key record interface <code> Type. </code> 
     *
     *  @param object osid_type_Type $keyRecordType a key record type 
     *  @return object osid_authentication_KeyFormRecord the key form record 
     *  @throws osid_NullArgumentException <code> keyRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> hasRecordType(keyRecordType) 
     *          </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getKeyFormRecord(osid_type_Type $keyRecordType);

}
