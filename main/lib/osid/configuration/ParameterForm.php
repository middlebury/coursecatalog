<?php

/**
 * osid_configuration_ParameterForm
 * 
 *     Specifies the OSID definition for osid_configuration_ParameterForm.
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


/**
 *  <p>This is the form for creating and updating Parameters. Various data 
 *  elements may be set here for use in the create and update methods in the 
 *  ParameterAdminSession. For each data element that may be set, metadata may 
 *  be examined to provide display hints or data constraints. </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_ParameterForm
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
     *  Gets the metadtaa for the display name. 
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
     *  Gets the metadata for the description. 
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
     *  Gets the metadata for the genus type. 
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
    public function setGenus(osid_type_Type $genusType);


    /**
     *  Gets the metadata for the value type. 
     *
     *  @return object osid_Metadata metadata for the value type 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getValueTypeMetadata();


    /**
     *  Sets a value type. 
     *
     *  @param object osid_type_Type $valueType the new value type 
     *  @throws osid_InvalidArgumentException <code> valueType </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> valueType </code> cannot be 
     *          modified 
     *  @throws osid_NullArgumentException <code> valueType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setValueType(osid_type_Type $valueType);

}
