<?php

/**
 * osid_OsidManager
 * 
 *     Specifies the OSID definition for osid_OsidManager.
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

require_once(dirname(__FILE__)."/OsidProfile.php");

/**
 *  <p>The <code> OsidManager </code> is the top level interface for all OSID 
 *  managers. An OSID manager is instantiated through the <code> 
 *  OsidRuntimeManager </code> and represents an instance of a service. An 
 *  OSID manager is responsible for implementing a profile for a service and 
 *  creating sessions that, in general, correspond to the profile. An 
 *  application need only create a single <code> OsidManager </code> per 
 *  service and implementors must ensure the <code> OsidManager </code> is 
 *  thread-safe <code> . </code> The <code> OsidSessions </code> spawned from 
 *  an OSID manager are dedicated to single processing threads. The <code> 
 *  OsidManager </code> defines methods in common throughout all OSID managers 
 *  which implement this interface. </p>
 * 
 * @package org.osid
 */
interface osid_OsidManager
    extends osid_OsidProfile
{


    /**
     *  Initializes this manager. A manager is initialized once at the time of 
     *  creation. 
     *
     *  @param object osid_OsidRuntimeManager $runtime the runtime environment 
     *  @throws osid_ConfigurationErrorException an error with implementation 
     *          configuration 
     *  @throws osid_IllegalStateException this manager has already been 
     *          initialized by the <code> OsidLoader </code> or this manager 
     *          has been shut down 
     *  @throws osid_NullArgumentException <code> runtime </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  In addition to loading its runtime configuration an 
     *          implementation may create shared resources such as connection 
     *          pools to be shared among all sessions of this service and 
     *          released when this manager is closed. Providers must 
     *          thread-protect any data stored in the manager. 
     *          <br/><br/>
     *          To maximize interoperability, providers should not honor a 
     *          second call to <code> initialize() </code> and must set an 
     *          <code> ILLEGAL_STATE </code> error. 
     */
    public function initialize(osid_OsidRuntimeManager $runtime);


    /**
     *  Gets the Journal session for this service. 
     *
     *  @return object osid_journaling_JournalSession a journal session 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnimplementedException <code> supportsJournaling() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getJournalSession();


    /**
     *  Rolls back this service to a point in time. 
     *
     *  @param DateTime $rollbackTime the requested time 
     *  @return object osid_journaling_JournalEntry the journal entry 
     *          corresponding to the actual state of this service 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnimplementedException <code> supportsJournaling() 
     *          </code> is <code> false </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function rollbackService($rollbackTime);


    /**
     *  Gets a service message which can be used for service announcements. 
     *
     *  @return string service message 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getServiceMessage();


    /**
     *  Register for service messages. <code> ServiceMessage.newMessage() 
     *  </code> is invoked for each new message. There is a single service 
     *  message receiver per manager. 
     *
     *  @param object osid_ServiceReceiver $receiver supplied interface for 
     *          service messages 
     *  @throws osid_NullArgumentException <code> receiver </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function registerForServiceMessages(osid_ServiceReceiver $receiver);


    /**
     *  Unregister for service messages. 
     *
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function unregisterForServiceMessages();


    /**
     *  Shuts down this <code>osid.OsidManager</code>
     */

    public function shutdown();

}
