<?php

/**
 * osid_filing_File
 * 
 *     Specifies the OSID definition for osid_filing_File.
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

require_once(dirname(__FILE__)."/DirectoryEntry.php");

/**
 *  <p><code> File </code> represents a file in a file system. </p>
 * 
 * @package org.osid.filing
 */
interface osid_filing_File
    extends osid_filing_DirectoryEntry
{


    /**
     *  Tests if this file has a known size. 
     *
     *  @return boolean <code> true </code> if this file has a size, <code> 
     *          false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasSize();


    /**
     *  Gets the size of this file in bytes if <code> hasSize() </code> is 
     *  <code> true. </code> 
     *
     *  @return integer the size of this file 
     *  @throws osid_IllegalStateException <code> hasSize() </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSize();


    /**
     *  Gets the record corresponding to the given <code> File </code> record 
     *  <code> Type. </code> This method must be used to retrieve an object 
     *  implementing the requested record interface along with all of its 
     *  ancestor interfaces. The <code> fileRecordType </code> may be the 
     *  <code> Type </code> returned in <code> getRecordTypes() </code> or any 
     *  of its parents in a <code> Type </code> hierarchy where <code> 
     *  hasRecordType(fileRecordType) </code> is <code> true </code> . 
     *
     *  @param object osid_type_Type $fileInterfaceType the file record type 
     *  @return object osid_filing_File the file record 
     *  @throws osid_NullArgumentException <code> fileRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasInterfaceType(fileRecordType) </code> is <code> false 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getFileRecord(osid_type_Type $fileInterfaceType);

}
