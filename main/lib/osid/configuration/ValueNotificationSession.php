<?php

/**
 * osid_configuration_ValueNotificationSession
 * 
 *     Specifies the OSID definition for osid_configuration_ValueNotificationSession.
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
 *  <p>This session defines methods to receive notifications on changes to 
 *  <code> Values. </code> A particular value in a set may have been added or 
 *  deleted, but all changes appear as a change to a parameter. Once a change 
 *  notification is received, the new value list can be obtained through the 
 *  <code> ValueLookupSession. </code> This session is intended for adapters 
 *  and providers needing to synchronize their state with this service without 
 *  the use of polling. Notifications are cancelled when this session is 
 *  closed. </p> 
 *  
 *  <p> Two views are defined; </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> federated: parameters defined in configurations that are a parent 
 *      of this configuration in the configuration hierarchy are included for 
 *      notifications </li> 
 *      <li> isolated: notifications are restricted to parameters are defined 
 *      to within this configuration </li> 
 *  </ul>
 *  The methods <code> federateValuerView() </code> and <code> 
 *  isolateValueView() </code> behave as a radio group and one should be 
 *  selected before invoking any lookup methods. </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_ValueNotificationSession
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
     *  Tests if this user can register for <code> Value </code> 
     *  notifications. A return of true does not guarantee successful 
     *  authorization. A return of false indicates that it is known all 
     *  methods in this session will result in a <code> PERMISSION_DENIED. 
     *  </code> This is intended as a hint to an application that may opt not 
     *  to offer notification operations. 
     *
     *  @return boolean <code> false </code> if notification methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canRegisterForValueNotifications();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include parameters in configurations of which this configuration is a 
     *  child in the configuration hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedConfigurationView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts notifications for parameter values to this configuration 
     *  only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedConfigurationView();


    /**
     *  Assigns a callback for notification of updated parameter values in 
     *  this configuration. <code> ValueReceiver.changedValue() </code> is 
     *  invoked when a <code> Parameter </code> is changed by adding, removing 
     *  or updating a <code> Value. </code> 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedValues();


    /**
     *  Assigns a callback for notifications of an update to a parameter in 
     *  this configuration. <code> ValueReceiver.changedValue() </code> is 
     *  invoked when the specified <code> Parameter </code> is changed by 
     *  adding, removijng or updating a <code> Value. </code> 
     *
     *  @param object osid_id_Id $parameterId the <code> Id </code> of the 
     *          <code> Parameter </code> to monitor 
     *  @throws osid_NotFoundException a <code> Parameter </code> was not 
     *          found identified by the given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> parameterId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForChangedValue(osid_id_Id $parameterId);

}
