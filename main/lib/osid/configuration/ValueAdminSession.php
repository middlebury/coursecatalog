<?php

/**
 * osid_configuration_ValueAdminSession
 * 
 *     Specifies the OSID definition for osid_configuration_ValueAdminSession.
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

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session is used to add, update and remove configuration parameter 
 *  values. <code> Parameters </code> are contained within <code> 
 *  Configurations </code> and <code> Values </code> contained within <code> 
 *  Parameters. </code> Each <code> Parameter </code> is identified with an 
 *  <code> Id </code> and each <code> Value </code> is identified with an 
 *  <code> index </code> number. The <code> index </code> is a number starting 
 *  at 1 but values are not required to have sequential numbers. The <code> 
 *  index </code> simply serves the purpose of identifying a particular <code> 
 *  Value </code> where multiple values exist for a parameter and to indicate 
 *  a preferential ordering of the values. </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_ValueAdminSession
    extends osid_OsidSession
{


    /**
     *  Gets the <code> Configuration </code> <code> Id </code> associated 
     *  with this session. 
     *
     *  @return object osid_id_Id the <code> Configuration </code> <code> Id 
     *          </code> associated with this session 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getConfigurationId();


    /**
     *  Gets the <code> Configuration </code> associated with this session. 
     *
     *  @return object osid_configuration_Configuration the <code> 
     *          Configuration </code> associated with this session 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getConfiguration();


    /**
     *  Tests if this user can create <code> Values. </code> A return of true 
     *  does not guarantee successful authorization. A return of false 
     *  indicates that it is known creating a <code> Value </code> will result 
     *  in a <code> PERMISSION_DENIED. </code> This is intended as a hint to 
     *  an application that may opt not to offer create operations to an 
     *  unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Value </code> ceration 
     *          is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateValues();


    /**
     *  Appends a value to the given parameter. The index assigned will be a 
     *  number 1 higher than the last value. 
     *
     *  @param object osid_id_Id $parameterId the parameter <code> Id </code> 
     *  @param object $value the value 
     *  @throws osid_InvalidArgumentException <code> value </code> is not of 
     *          parameter type 
     *  @throws osid_NullArgumentException <code> parameterId </code> or 
     *          <code> value </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addValue(osid_id_Id $parameterId, $value);


    /**
     *  Tests if this user can update <code> Values. </code> A return of true 
     *  does not guarantee successful authorization. A return of false 
     *  indicates that it is known updating a <code> Value </code> will result 
     *  in a <code> PERMISSION_DENIED. </code> This is intended as a hint to 
     *  an application that may opt not to offer update operations to an 
     *  unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Value </code> 
     *          modification is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canUpdateValues();


    /**
     *  Tests if this user can update a specified <code> Value. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known updating the <code> Value </code> 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may opt not to offer update operations 
     *  to an unauthoirzed user. 
     *
     *  @param object osid_id_Id $parameterId the <code> Id </code> of the 
     *          <code> Parameter </code> 
     *  @param integer $index the index of the value to test 
     *  @return boolean <code> false </code> if value modification is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> valueId </code> is <code> 
     *          null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> parameterId </code> and <code> index </code> is 
     *          not found, then it is acceptable to return false to indicate 
     *          the lack of an update available. 
     */
    public function canUpdateValue(osid_id_Id $parameterId, $index);


    /**
     *  Updates the given parameter value in this configuration. 
     *
     *  @param object osid_id_Id $parameterId the parameter <code> Id </code> 
     *  @param integer $index the index of the value to remove 
     *  @param object $value the value 
     *  @throws osid_InvalidArgumentException <code> value </code> is not of 
     *          parameter type 
     *  @throws osid_NotFoundException the parameter value is not found in 
     *          this configuration 
     *  @throws osid_NullArgumentException <code> parameterId </code> or 
     *          <code> value </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateValue(osid_id_Id $parameterId, $index, $value);


    /**
     *  Changes the value position. A new position of the same index as an 
     *  existing value results in an insert operation changing the indices os 
     *  subsequent values. 
     *
     *  @param object osid_id_Id $parameterId the <code> Id </code> of the 
     *          <code> Parameter </code> 
     *  @param integer $oldIndex the index of the value to test 
     *  @param integer $newIndex the index of the value to test 
     *  @throws osid_NotFoundException the parameter value is not found in 
     *          this configuration 
     *  @throws osid_NullArgumentException <code> parameterId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateValuePosition(osid_id_Id $parameterId, $oldIndex, 
                                        $newIndex);


    /**
     *  Tests if this user can delete <code> Values. </code> A return of true 
     *  does not guarantee successful authorization. A return of false 
     *  indicates that it is known deleting a <code> Value </code> will result 
     *  in a <code> PERMISSION_DENIED. </code> This is intended as a hint to 
     *  an application that may opt not to offer delete operations to an 
     *  unauthorized user. 
     *
     *  @return boolean <code> false </code> if <code> Value </code> deletion 
     *          is not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canDeleteValues();


    /**
     *  Tests if this user can delete a specified <code> Value. </code> A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known deleting the <code> Value </code> 
     *  will result in a <code> PERMISSION_DENIED. </code> This is intended as 
     *  a hint to an application that may opt not to offer delete operations 
     *  to an unauthorized user. 
     *
     *  @param object osid_id_Id $parameterId the <code> Id </code> of the 
     *          <code> Parameter </code> 
     *  @param integer $index the index of the value to test 
     *  @return boolean <code> false </code> if <code> Value </code> deletion 
     *          is not authorized, <code> true </code> otherwise 
     *  @throws osid_NullArgumentException <code> parameterId </code> is 
     *          <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  If the <code> parameterId </code> and index is not found, then 
     *          it is acceptable to return false to indicate the lack of an 
     *          delete available. 
     */
    public function canDeleteValue(osid_id_Id $parameterId, $index);


    /**
     *  Deletes the specified parameter value. This operation does not change 
     *  the index numbers of the surrounding values. 
     *
     *  @param object osid_id_Id $parameterId the <code> Id </code> of the 
     *          <code> Property </code> containing the value 
     *  @param integer $index the index of the value to remove 
     *  @throws osid_NotFoundException the parameter value is not found in 
     *          this configuration 
     *  @throws osid_NullArgumentException <code> parameterId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deleteValue(osid_id_Id $parameterId, $index);

}
