<?php

/**
 * osid_transport_DataInputStream
 * 
 *     Specifies the OSID definition for osid_transport_DataInputStream.
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
 *  <p>The data input stream provides a means for reading data from a stream. 
 *  </p>
 * 
 * @package org.osid.transport
 */
interface osid_transport_DataInputStream
{


    /**
     *  Tests if the end of this stream has been reached. This may not be a 
     *  permanent condition as more data may be available at a later time as 
     *  in the case of tailing a file. 
     *
     *  @return boolean <code> true </code> if the end of this stream has been 
     *          reached, <code> false </code> otherwise 
     *  @throws osid_IllegalStateException this stream has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function atEndOfStream();


    /**
     *  Gets the number of <code> bytes </code> available for retrieval. The 
     *  number returned by this method may be less than or equal to the total 
     *  number of <code> bytes </code> in this stream. 
     *
     *  @return integer the number of <code> bytes </code> available for 
     *          retrieval 
     *  @throws osid_IllegalStateException this stream has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function available();


    /**
     *  Skips a specified number of <code> bytes </code> in the stream. 
     *
     *  @param integer $n the number of <code> bytes </code> to skip 
     *  @return integer the actual number of <code> bytes </code> skipped 
     *  @throws osid_IllegalStateException this stream has been closed or 
     *          <code> atEndOfStream() </code> is <code> true </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function skip($n);


    /**
     *  Reads a specified number of <code> bytes </code> from this stream. 
     *
     *  @param array $buf the buffer in which the data is read 
     *  @param integer $n the number of <code> bytes </code> to read 
     *  @return integer the actual number of <code> bytes </code> read 
     *  @throws osid_IllegalStateException this stream has been closed or 
     *          <code> atEndOfStream() </code> is <code> true </code> 
     *  @throws osid_InvalidArgumentException the size of <code> buf </code> 
     *          is less than <code> n </code> 
     *  @throws osid_NullArgumentException <code> buf </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function read(array $buf, $n);


    /**
     *  Closes this stream and frees up any allocated resources. Methods in 
     *  this object may not be invoked after this method is called. 
     *
     *  @throws osid_IllegalStateException this stream has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function close();

}
