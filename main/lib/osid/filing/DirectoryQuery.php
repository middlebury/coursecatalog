<?php

/**
 * osid_filing_DirectoryQuery
 * 
 *     Specifies the OSID definition for osid_filing_DirectoryQuery.
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

require_once(dirname(__FILE__)."/DirectoryEntryQuery.php");

/**
 *  <p>This is the query interface for searching directories. Each method 
 *  match request produces an <code> AND </code> term while multiple 
 *  invocations of a method produces a nested <code> OR, </code> except for 
 *  accessing the <code> DirectoryQuery </code> subinterface. An example to 
 *  find directories whose name is "Library". </p> 
 *  
 *  <p> 
 *  <pre>
 *       
 *       
 *       DirectoryQuery query = session.getDirectoryQuery();
 *       query.matchName("Library", wordStringMatchType, true);
 *       
 *       DirectoryList list = session.getDirectoriesByQuery(query);
 *       
 *                   
 *       
 *  </pre>
 *  </p>
 * 
 * @package org.osid.filing
 */
interface osid_filing_DirectoryQuery
    extends osid_filing_DirectoryEntryQuery
{


    /**
     *  Tests if a <code> FileQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a file query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsFileQuery();


    /**
     *  Gets the query interface for a file contained within the directory. 
     *
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @return object osid_filing_FileQuery the directory query 
     *  @throws osid_UnimplementedException <code> supportsFileQuery() </code> 
     *          is <code> false </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsFileQuery() </code> is <code> true. </code> 
     */
    public function getFileQuery($match);


    /**
     *  Gets the file query interface for the file type. Supported types are 
     *  defined in the <code> FilingManager. </code> 
     *
     *  @param object osid_type_Type $fileInterfaceType a file interface type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for negative match 
     *  @return object osid_filing_DirectoryQuery the file query 
     *  @throws osid_NullArgumentException <code> fileInterfaceType </code> is 
     *          <code> null </code> 
     *  @throws osid_UnimplementedException <code> supportFileQuery() </code> 
     *          is <code> false </code> 
     *  @throws osid_UnsupportedException <code> 
     *          FilingManager.supportsFileInterfaceType(fileInterfaceType) 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsFileQuery() </code> is <code> true. </code> 
     */
    public function getFileQueryForInterfaceType(osid_type_Type $fileInterfaceType, 
                                                 $match);


    /**
     *  Gets the record query interface corresponding to the given <code> 
     *  Directory </code> record <code> Type. </code> Multiple record 
     *  retrievals produce a nested boolean <code> OR </code> term. 
     *
     *  @param object osid_type_Type $directoryRecordType a directory record 
     *          type 
     *  @return object osid_filing_DirectoryQuery the directory query record 
     *  @throws osid_NullArgumentException <code> directoryRecordType </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(directoryRecordType) </code> is <code> false 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDirectoryQueryRecord(osid_type_Type $directoryRecordType);

}
