<?php

/**
 * osid_filing_DirectoryEntryForm
 * 
 *     Specifies the OSID definition for osid_filing_DirectoryEntryForm.
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
 * @package org.osid.filing
 */


/**
 *  <p><code> DirectoryEntryForm </code> defines methods in common to both 
 *  <code> FileForm </code> and <code> DirectoryForm. </code> </p>
 * 
 * @package org.osid.filing
 */
interface osid_filing_DirectoryEntryForm
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
     *  Performs a validation of the data set in this form. This method 
     *  returns nothing if validation is not performed or validation is 
     *  successful. An transaction is returned if the form is invalid. 
     *
     *  @throws osid_OperationFailedException attempt to perform validation 
     *          failed 
     *  @throws osid_TransactionFailureException form is invalid 
     *  @compliance mandatory This method must be implemented. 
     */
    public function validate();


    /**
     *  Gets the metadata for the name. 
     *
     *  @return object osid_Metadata metadata for the name 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNameMetadata();


    /**
     *  Sets the name. 
     *
     *  @param string $name the new name 
     *  @throws osid_InvalidArgumentException <code> name </code> is invalid 
     *  @throws osid_NoAccessException <code> name </code> cannot be modified 
     *  @throws osid_NullArgumentException <code> displayName </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setName($name);


    /**
     *  Gets the metadata for the owner. 
     *
     *  @return object osid_Metadata metadata for the owner 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getOwnerMetadata();


    /**
     *  Sets the owner. 
     *
     *  @param object osid_id_Id $agentId the new owner 
     *  @throws osid_InvalidArgumentException <code> agentId </code> is 
     *          invalid 
     *  @throws osid_NoAccessException owner cannot be modified 
     *  @throws osid_NullArgumentException <code> agentId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setOwner(osid_id_Id $agentId);


    /**
     *  Gets the metadata for the genus type. 
     *
     *  @return object osid_Metadata metadata for the genus 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getGenusMetadata();


    /**
     *  Sets the genus type. 
     *
     *  @param object osid_type_Type $genusType the new genus type 
     *  @throws osid_InvalidArgumentException <code> genusType </code> is 
     *          invalid 
     *  @throws osid_NoAccessException genus cannot be modified 
     *  @throws osid_NullArgumentException <code> genusType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setGenus(osid_type_Type $genusType);


    /**
     *  Tests if this form supports the given record <code> Type. </code> The 
     *  given record type may be supported by the object through 
     *  interface/type inheritence. This method should be checked before 
     *  retrieving the record interface. 
     *
     *  @param object osid_type_Type $recordType a record type 
     *  @return boolean <code> true </code> if a record form of the given 
     *          record <code> Type </code> is available, <code> false </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> recordType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasRecordType(osid_type_Type $recordType);

}
