<?php

/**
 * osid_transport_TransportReceiver
 * 
 *     Specifies the OSID definition for osid_transport_TransportReceiver.
 * 
 * Copyright (C) 2002-2007 Massachusetts Institute of Technology. All Rights 
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
 * @package org.osid.transport
 */

require_once(dirname(__FILE__)."/../OsidReceiver.php");

/**
 *  <p>The TransportReceive is used to receive incoming connections. The 
 *  receiver is provided to the service via the TransportManager and invoked 
 *  by the transport provider when a new association is created. The transport 
 *  session and authentication is porvided. The authentication object may 
 *  contain information pertaining to the connection. </p>
 * 
 * @package org.osid.transport
 */
interface osid_transport_TransportReceiver
    extends osid_OsidReceiver
{


    /**
     *  Invoked by the transport provider when a new connection request or 
     *  datagram is received. 
     *
     *  @param object osid_transport_TransportSession $session the new 
     *          transport session 
     *  @param object osid_authentication_Authentication $credential the 
     *          authentication credential retrieved from the transport or 
     *          <code> null </code> if <code> 
     *          TransportManager.supportsPAuthenticationForProxy() </code> is 
     *          <code> false </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function dispatch(osid_transport_TransportSession $session, 
                             osid_authentication_Authentication $credential);

}
