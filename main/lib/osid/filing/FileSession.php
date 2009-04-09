<?php

/**
 * osid_filing_FileSession
 * 
 *     Specifies the OSID definition for osid_filing_FileSession.
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
 * @package org.osid.filing
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session is for reading and writing the file associated with this 
 *  session. </p>
 * 
 * @package org.osid.filing
 */
interface osid_filing_FileSession
    extends osid_OsidSession
{


    /**
     *  Gets the absolute path of this file. 
     *
     *  @return string the absolute path of this file 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getFilePath();


    /**
     *  Gets the file associated with this session. 
     *
     *  @return object osid_filing_File the file associated with this session 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getFile();


    /**
     *  Tests if this user can access this file. A return of true does not 
     *  guarantee successful authorization. A return of false indicates that 
     *  it is known accessing this file will result in a <code> 
     *  PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may not wish to offer read operations to unauthorized 
     *  users. 
     *
     *  @return boolean <code> false </code> if file access is not authorized, 
     *          <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canReadFile();


    /**
     *  Gets the input stream for reading this file. The input stream reads 
     *  until the end of the file. 
     *
     *  @return object osid_transport_DataInputStream the input stream for 
     *          reading this file 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getInputStream();


    /**
     *  Gets the input stream for reading this file. The returned input 
     *  stream, once it reaches the end of the file, blocks for new content 
     *  that may be later appended to the file. 
     *
     *  @return object osid_transport_DataInputStream the input stream for 
     *          reading this file 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getBlockingInputStream();


    /**
     *  Tests if this user can update this file. A return of true does not 
     *  guarantee successful authorization. A return of false indicates that 
     *  it is known writing to this this file will result in a <code> 
     *  PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may not wish to offer write operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if file write is not authorized, 
     *          <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canWriteFile();


    /**
     *  Gets an output stream for writing to this file, replacing any existing 
     *  contents. 
     *
     *  @return object osid_transport_DataOutputStream the output stream for 
     *          writing to this file 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getOutputStream();


    /**
     *  Gets an output stream for appending to this file. 
     *
     *  @return object osid_transport_DataOutputStream the output stream for 
     *          appending to this file 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getOutputStreamForAppend();


    /**
     *  Updates the modified time of a file to be the current time. 
     *
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function touch();

}
