<?php

/**
 * osid_ServiceReceiver
 * 
 *     Specifies the OSID definition for osid_ServiceReceiver.
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
 *  <p>A <code> ServiceReceiver </code> is used to receive asynchronous 
 *  notifications from a service. The receiver defines the interface to be 
 *  implemented by the consumer. Simple example: </p> 
 *  
 *  <p> 
 *  <pre>
 *       
 *       
 *       MyCallback {
 *           void up() {
 *               print "notification service is up";
 *           }
 *       
 *           void down() {
 *               print "notification service is down";
 *           }
 *       
 *           void newMessage(msg) {
 *               print ("new message received " + msg);
 *           }
 *       }
 *       
 *                   
 *       
 *  </pre>
 *  </p> 
 *  
 *  <p> 
 *  <pre>
 *       
 *       
 *       notificationSession = manager.registerForServiceMessages(myCallback);
 *       
 *                   
 *       
 *  </pre>
 *  </p>
 * 
 * @package org.osid
 */
interface osid_ServiceReceiver
{


    /**
     *  The callback for notifications of new messages. The message <code> Id 
     *  </code> is to eliminate duplicate messages that may originate from 
     *  shared providers. 
     *
     *  @param object osid_id_Id $messageId unique identifier for the message 
     *  @param string $message a service message 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function newMessage(osid_id_Id $messageId, $message);

}
