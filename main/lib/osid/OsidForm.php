<?php

/**
 * osid_OsidForm
 * 
 *     Specifies the OSID definition for osid_OsidForm.
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
 * @package org.osid
 */


/**
 *  <p>The <code> OsidForm </code> is used to create and update <code> 
 *  OsidObjects. </code> The form is not an <code> OsidObject </code> but 
 *  merely a container for data to be sent to an update or create method of a 
 *  session. A provider may or may not combine the <code> OsidObject </code> 
 *  and <code> OsidForm </code> interfaces into a single object. </p> 
 *  
 *  <p> Generally, a set method parallels each get method of an <code> 
 *  OsidObject. </code> Additionally, <code> Metadata </code> may be examined 
 *  for each data element to assist in understanding particular rules 
 *  concerning acceptable data. </p> 
 *  
 *  <p> The form may provide some feedback as to the validity of certain data 
 *  updates before the update transaction is issued to the correspodning 
 *  session but a successful modification of the form is not a guarantee of 
 *  success for the update transaction. A consumer may elect to perform all 
 *  updates within a single update transaction or break up a large update 
 *  intio smaller units. The tradeoff is the granularity of error feedback vs. 
 *  the performance gain of a single transaction. </p> 
 *  
 *  <p> As with all aspects of the OSIDs, nulls cannot be used. Methods to 
 *  clear values are also defined in the form. A new <code> OsidForm </code> 
 *  should be acquired for each transaction upon an <code> OsidObject. </code> 
 *  Forms should not be reused from one object to another even if the supplied 
 *  data is the same as the forms may encapsulate data specific to the object 
 *  requested. Example of changing a display name and a color defined in a 
 *  color interface extension: </p> 
 *  
 *  <p> 
 *  <pre>
 *       
 *       
 *       ObjectForm form = session.getObjectFormForUpdate(objectId);
 *       form.setDisplayName("new name");
 *       ColorForm recordForm = form.getFormRecord(colorRecordType);
 *       recordForm.setColor("green");
 *       session.updateObject(objectId, form);
 *       
 *                   
 *       
 *  </pre>
 *  </p>
 * 
 * @package org.osid
 */
interface osid_OsidForm
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
     *  Gets a text message corresponding to additional instructions to pass 
     *  form validation. 
     *
     *  @return string message 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getValidationMessage();


    /**
     *  Gets the metadata for a display name. 
     *
     *  @return object osid_Metadata metadata for the display name 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDisplayNameMetadata();


    /**
     *  Sets a display name. A display name is required and if not set, will 
     *  be set by the provider. 
     *
     *  @param string $displayName the new display name 
     *  @throws osid_InvalidArgumentException <code> displayName </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> displayName </code> cannot be 
     *          modified 
     *  @throws osid_NullArgumentException <code> displayName </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setDisplayName($displayName);


    /**
     *  Gets the metadata for a description. 
     *
     *  @return object osid_Metadata metadata for the description 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDescriptionMetadata();


    /**
     *  Sets a description. 
     *
     *  @param string $description the new description 
     *  @throws osid_InvalidArgumentException <code> description </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> description </code> cannot be 
     *          modified 
     *  @throws osid_NullArgumentException <code> description </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setDescription($description);


    /**
     *  Clears the description. 
     *
     *  @throws osid_NoAccessException <code> description </code> cannot be 
     *          modified 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearDescription();


    /**
     *  Gets the metadata forr a genus type. 
     *
     *  @return object osid_Metadata metadata for the genus 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getGenusMetadata();


    /**
     *  Sets a genus. A genus cannot be cleared because all objects have at 
     *  minimum a root genus. 
     *
     *  @param object osid_type_Type $genusType the new genus 
     *  @throws osid_InvalidArgumentException <code> genusType </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> genusType </code> cannot be 
     *          modified 
     *  @throws osid_NullArgumentException <code> genusType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setGenusType(osid_type_Type $genusType);


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
