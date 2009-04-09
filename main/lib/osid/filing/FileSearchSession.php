<?php

/**
 * osid_filing_FileSearchSession
 * 
 *     Specifies the OSID definition for osid_filing_FileSearchSession.
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
 *  <p>This session provides methods for searching among files and directories 
 *  objects. The search query is constructed using the <code> FileQuery 
 *  </code> and <code> DirectoryQuery </code> interfaces. </p> 
 *  
 *  <p> <code> getFilesByQuery() </code> is the basic search method and 
 *  returns a list of <code> Files. </code> A more advanced search may be 
 *  performed with <code> getFilesBySearch(). </code> It accepts a <code> 
 *  FileSearch </code> interface in addition to the query interface for the 
 *  purpose of specifying additional options affecting the entire search, such 
 *  as ordering. <code> getFilesBySearch() </code> returns an <code> 
 *  FileSearchResults </code> interface that can be used to access the 
 *  resulting <code> FileList </code> or be used to perform a search within 
 *  the result set through <code> FileSearch. </code> </p> 
 *  
 *  <p> This session defines views that offer differing behaviors when 
 *  retrieving multiple objects. </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> federated directory view: searches include entries in directories 
 *      of which this directory is a ancestor </li> 
 *      <li> isolated directory view: searches are restricted this directory 
 *      only </li> 
 *  </ul>
 *  </p>
 * 
 * @package org.osid.filing
 */
interface osid_filing_FileSearchSession
    extends osid_OsidSession
{


    /**
     *  Gets the absolute path of this directory. 
     *
     *  @return string the absolute path of this directory 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDirectoryPath();


    /**
     *  Gets the directory associated with this session. 
     *
     *  @return object osid_filing_Directory the directory associated with 
     *          this session 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDirectory();


    /**
     *  Tests if this user can perform <code> File </code> searches. A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known all methods in this session will result in 
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer search operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canSearchFiles();


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include entries in directories which are children of this directory. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedDirectoryView();


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts lookups to this directory only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedDirectoryView();


    /**
     *  Gets a file query interface. 
     *
     *  @return object osid_filing_FileQuery the file query 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getFileQuery();


    /**
     *  Gets a list of <code> Files </code> matching the given query. 
     *
     *  @param object osid_filing_FileQuery $fileQuery the search query 
     *  @return object osid_filing_FileList the returned <code> FileList 
     *          </code> 
     *  @throws osid_NullArgumentException <code> fileQuery </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> fileQuery </code> is not of 
     *          this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getFilesByQuery(osid_filing_FileQuery $fileQuery);


    /**
     *  Gets a file search interface. The returned query only makes available 
     *  the core <code> FileSearch </code> interface and does not support 
     *  additional interface types. <code> getFileSearchForInterfaceType() 
     *  </code> should be used if additional interface types are required. 
     *
     *  @return object osid_filing_FileSearch the file search interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getFileSearch();


    /**
     *  Gets a file search order interface. The <code> FileSearchOrder </code> 
     *  is supplied to a <code> FileSearch </code> to specify the ordering of 
     *  results. 
     *
     *  @return object osid_filing_FileSearchOrder the file search order 
     *          interface 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getFileSearchOrder();


    /**
     *  Gets the search results matching the given search query using the 
     *  given search interface. 
     *
     *  @param object osid_filing_FileQuery $fileQuery the search query 
     *  @param object osid_filing_FileSearch $fileSearch the search interface 
     *  @return object osid_filing_FileSearchResults the search results 
     *  @throws osid_NullArgumentException <code> fileQuery </code> or <code> 
     *          fileSearch </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> fileQuery </code> or <code> 
     *          fileSearch </code> is not of this service 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getFilesBySearch(osid_filing_FileQuery $fileQuery, 
                                     osid_filing_FileSearch $fileSearch);

}
