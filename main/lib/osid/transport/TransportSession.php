<?php

/**
 * osid_transport_TransportSession
 * 
 *     Specifies the OSID definition for osid_transport_TransportSession.
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

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>The transport session is used to send and receive arbitrary data to and 
 *  from a remote end point. The methods accept for return a data stream. Some 
 *  protocols may send or receive all data within a single stream while others 
 *  may use the streams as channels or frames of data. </p> 
 *  
 *  <p> A stream may be available for reading before all the data as arrived 
 *  and as such multiple streams may be processed simultaneously. </p>
 * 
 * @package org.osid.transport
 */
interface osid_transport_TransportSession
    extends osid_OsidSession
{


    /**
     *  Sends data to the remote transport endpoint. 
     *
     *  @return object osid_transport_DataOutputStream the output stream in 
     *          which to send data 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function sendData();


    /**
     *  Tests to see if another input stream is available for retrieval. 
     *
     *  @return boolean <code> true </code> if a stream is available for 
     *          reading, <code> false </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasDataAvailable();


    /**
     *  Receives data from the remote transport endpoint. 
     *
     *  @return object osid_transport_DataInputStream the input stream 
     *          containing the received data 
     *  @throws osid_IllegalStateException <code> hasDataAvailable() </code> 
     *          is <code> false </code> or this session has been closed 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function receiveData();

}
