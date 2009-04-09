<?php

/**
 * osid_transport_DataOutputStream
 * 
 *     Specifies the OSID definition for osid_transport_DataOutputStream.
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


/**
 *  <p>The data output stream provides a means in which data can be written to 
 *  a stream. </p>
 * 
 * @package org.osid.transport
 */
interface osid_transport_DataOutputStream
{


    /**
     *  Writes <code> n </code> bytes to this stream. 
     *
     *  @param array $buf the buffer containing the data to write 
     *  @param integer $n the number of <code> bytes </code> to write 
     *  @throws osid_IllegalStateException this stream has been closed 
     *  @throws osid_InvalidArgumentException <code> buf </code> does not 
     *          contain <code> n bytes </code> 
     *  @throws osid_NullArgumentException <code> buf </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function write(array $buf, $n);


    /**
     *  Flushes the output, closes this stream and frees up any allocated 
     *  resources. Methods in this object may not be invoked after this method 
     *  is called. 
     *
     *  @throws osid_IllegalStateException this stream has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function close();

}
