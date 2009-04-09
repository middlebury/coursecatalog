<?php

/**
 * osid_filing_FilingProxyManager
 * 
 *     Specifies the OSID definition for osid_filing_FilingProxyManager.
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

require_once(dirname(__FILE__)."/../OsidProxyManager.php");
require_once(dirname(__FILE__)."/FilingProfile.php");

/**
 *  <p>The filing manager provides access sessions to retrieve and manage 
 *  files and directories. A manager may support federation in that files and 
 *  directories can be accessed by a specified path. Methods in this manager 
 *  support the passing of an Authentication object for the purposes of proxy 
 *  authentication. The sessions included in this manager are: 
 *  <ul>
 *      <li> <code> FileSession: </code> a basic session for reading and 
 *      writing a file </li> 
 *      <li> <code> FileSearchSession: </code> a session for searching for 
 *      files </li> 
 *      <li> <code> FileNotificationSession: </code> a session for subscribing 
 *      to changes in files </li> 
 *      <li> <code> DirectoryEntryLookupSession: </code> a session for 
 *      examining the contents of a directory </li> 
 *      <li> <code> DirectorySearchSession: </code> a session for searching 
 *      for directories </li> 
 *      <li> <code> DirectoryNotificationSession: </code> a session for 
 *      subscribing to changes in directories </li> 
 *      <li> <code> FilingManagementSession: </code> a session for performing 
 *      operations across directories </li> 
 *      <li> <code> AllocationSession: </code> a session for accessing usage 
 *      information and quotas </li> 
 *      <li> <code> AllocationAdminSession </code> a session for assigning 
 *      quotas </li> 
 *      <li> <code> AllocationNotificationSession: </code> a session for 
 *      subscribing to usage warnings and quota changes </li> 
 *  </ul>
 *  </p>
 * 
 * @package org.osid.filing
 */
interface osid_filing_FilingProxyManager
    extends osid_OsidProxyManager,
            osid_filing_FilingProfile
{


    /**
     *  Gets the session for manipulating files. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_FileSession the <code> FileSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsFiles() </code> is 
     *          <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsFiles() </code> is <code> true. </code> 
     */
    public function getFileSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for manipulating files for the given path. If a path 
     *  represents an alias, the target file is accessed. The path will 
     *  indicate the path to the alias and the real path will indicate the 
     *  path to the target file. 
     *
     *  @param string $filePath the pathname to the file 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_FileSession a <code> FileSession </code> 
     *  @throws osid_InvalidArgumentException <code> filePath </code> is not a 
     *          file 
     *  @throws osid_NotFoundException <code> filePath </code> is not found 
     *  @throws osid_NullArgumentException <code> filePath </code> or <code> 
     *          authentication </code> is null 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsFiles() </code> is 
     *          <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsFiles() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getFileSessionForPath($filePath, 
                                          osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for searching for files. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_FileSearchSession the <code> 
     *          FileSessionSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsFileSearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsFileSearch() </code> is <code> true. </code> 
     */
    public function getFileSearchSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets a file search session for the specified directory. 
     *
     *  @param string $directoryPath the pathname to the directory 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_FileSearchSession a <code> 
     *          FileSearchSession </code> 
     *  @throws osid_InvalidArgumentException <code> directoryPath </code> is 
     *          not a directory or an alias to a directory 
     *  @throws osid_NotFoundException <code> directoryPath </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> directoryPath </code> or 
     *          <code> authentication </code> is null 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsFileSearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsFileSearch() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getFileSearchSessionForDirectory($directoryPath, 
                                                     osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for receiving messages about changes to files. 
     *
     *  @param object osid_filing_FileReceiver $receiver the notification 
     *          callback 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_FileNotificationSession <code> a 
     *          FileNotificationSession </code> 
     *  @throws osid_NullArgumentException <code> receiver </code> or <code> 
     *          authentication </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsFileNotification() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsFileNotification() </code> is <code> true. </code> 
     */
    public function getFileNotificationSession(osid_filing_FileReceiver $receiver, 
                                               osid_authentication_Authentication $authentication);


    /**
     *  Gets a file notification session for the specified directory. 
     *
     *  @param object osid_filing_FileReceiver $receiver the notification 
     *          callback 
     *  @param string $directoryPath the pathname to the directory 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_FileNotificationSession a <code> 
     *          FileNotificationSession </code> 
     *  @throws osid_InvalidArgumentException <code> directoryPath </code> is 
     *          not a directory or an alias to a directory 
     *  @throws osid_NotFoundException <code> directoryPath </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> receiver, directoryPath 
     *          </code> or <code> authentication </code> is null 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsFileNotification() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsFileNotification() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getFileNotificationSessionForDirectory(osid_filing_FileReceiver $receiver, 
                                                           $directoryPath, 
                                                           osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for examining directories. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_DirectoryEntryLookupSession a <code> 
     *          DirectoryEntryLookupSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsDirectoryEntryLookup() </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsDirectoryEntryLookup() </code> is <code> true. 
     *              </code> 
     */
    public function getDirectoryEntryLookupSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for examining a given directory. If the path is an 
     *  alias, the target directory is used. The path indicates the file alias 
     *  and the real path indicates the target directory. 
     *
     *  @param string $directoryPath the pathname to the directory 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_DirectoryEntryLookupSession a <code> 
     *          DirectoryEntryLookupSession </code> 
     *  @throws osid_InvalidArgumentException <code> directoryPath </code> is 
     *          not a directory or an alias to a directory 
     *  @throws osid_NotFoundException <code> directoryPath </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> directoryPath </code> or 
     *          <code> authentication </code> is null 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsDirectoryEntryLookup() </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsDirectoryEntryLookup() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getDirectoryEntryLookupSessionForDirectory($directoryPath, 
                                                               osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for searching for directories. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_DirectorySearchSession a <code> 
     *          DirectorySearchSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsDirectorySearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsDirectorySearch() </code> is <code> true. </code> 
     */
    public function getDirectorySearchSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for searching for directories within a given 
     *  directory. If the path is an alias, the target directory is used. The 
     *  path indicates the file alias and the real path indicates the target 
     *  directory. 
     *
     *  @param string $directoryPath the pathname to the directory 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_DirectorySearchSession a <code> 
     *          DirectorySearchSession </code> 
     *  @throws osid_InvalidArgumentException <code> directoryPath </code> is 
     *          not a directory or an alias to a directory 
     *  @throws osid_NotFoundException <code> directoryPath </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> directoryPath </code> or 
     *          <code> authentication </code> is null 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsDirectorySearch() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsDirectorySearch() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getDirectorySearchSessionForDirectory($directoryPath, 
                                                          osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for creating and removing files. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_DirectoryAdminSession a <code> 
     *          DirectoryAdminSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsDirectoryAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsDirectoryAdmin() </code> is <code> true. </code> 
     */
    public function getDirectoryAdminSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for searching for creating and removing files in the 
     *  given directory. If the path is an alias, the target directory is 
     *  used. The path indicates the file alias and the real path indicates 
     *  the target directory. 
     *
     *  @param string $directoryPath the pathname to the directory 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_DirectoryAdminSession a <code> 
     *          DirectoryAdminSession </code> 
     *  @throws osid_InvalidArgumentException <code> directoryPath </code> is 
     *          not a directory or an alias to a directory 
     *  @throws osid_NotFoundException <code> directoryPath </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> directoryPath </code> or 
     *          <code> authentication </code> is null 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsDirectoryAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsDirectoryAdmin() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getDirectoryAdminSessionForDirectory($directoryPath, 
                                                         osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for receiving messages about changes to directories. 
     *
     *  @param object osid_filing_DirectoryReceiver $receiver the notification 
     *          callback 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_DirectoryNotificationSession a <code> 
     *          DirectoryNotificationSession </code> 
     *  @throws osid_NullArgumentException <code> receiver </code> or <code> 
     *          authentication </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsDirectoryNotification() </code> is <code> false 
     *          </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsDirectoryNotification() </code> is <code> true. 
     *              </code> 
     */
    public function getDirectoryNotificationSession(osid_filing_DirectoryReceiver $receiver, 
                                                    osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for receiving messages about changes to directories 
     *  in the given directory. If the path is an alias, the target directory 
     *  is used. The path indicates the file alias and the real path indicates 
     *  the target directory. 
     *
     *  @param object osid_filing_DirectoryReceiver $receiver the notification 
     *          callback 
     *  @param string $directoryPath the pathname to the directory 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_DirectoryNotificationSession a <code> 
     *          DirectoryNotificationSession </code> 
     *  @throws osid_InvalidArgumentException <code> directoryPath </code> is 
     *          not a directory or an alias to a directory 
     *  @throws osid_NotFoundException <code> directoryPath </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> receiver, directoryPath 
     *          </code> or <code> authentication </code> is null 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsDirectoryNotification() </code> is <code> false 
     *          </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsDirectoryNotification() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getDirectoryNotificationSessionForDirectory(osid_filing_DirectoryReceiver $receiver, 
                                                                $directoryPath, 
                                                                osid_authentication_Authentication $authentication);


    /**
     *  Gets a session for manipulating entries across directories. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_FilingManagementSession a <code> 
     *          FilingManagementSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsFilingManagement() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsFilingManagement() </code> is <code> true. </code> 
     */
    public function getFilingManagementSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for manipulating entries using the given director as 
     *  the current directory. If the path is an alias, the target directory 
     *  is used. The path indicates the file alias and the real path indicates 
     *  the target directory. 
     *
     *  @param string $directoryPath the pathname to the directory 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_FilingManagementSession a <code> 
     *          FilingManagementSession </code> 
     *  @throws osid_InvalidArgumentException <code> directoryPath </code> is 
     *          not a directory or an alias to a directory 
     *  @throws osid_NotFoundException <code> directoryPath </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> directoryPath </code> or 
     *          <code> authentication </code> is null 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsFilingManagement() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsFilingManagement() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getFileManagementSessionForDirectory($directoryPath, 
                                                         osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for accessing usage and quotas. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_AllocationSession an <code> 
     *          AllocationSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsAllocation() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAllocation() </code> is <code> true. </code> 
     */
    public function getAllocationSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for accessing usage and quotas for a given directory. 
     *  If the path is an alias, the target directory is used. The path 
     *  indicates the file alias and the real path indicates the target 
     *  directory. 
     *
     *  @param string $directoryPath the pathname to the directory 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_AllocationSession an <code> 
     *          AllocationSession </code> 
     *  @throws osid_InvalidArgumentException <code> directoryPath </code> is 
     *          not a directory or an alias to a directory 
     *  @throws osid_NotFoundException <code> directoryPath </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> directoryPath </code> or 
     *          <code> authentication </code> is null 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsAllocation() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAllocation() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getAllocationSessionForDirectory($directoryPath, 
                                                     osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for assigning quotas. 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_AllocationAdminSession an <code> 
     *          AllocationAdminSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsAllocationAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAllocationAdmin() </code> is <code> true. </code> 
     */
    public function getAllocationAdminSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for assigning quotas for the given directory. If the 
     *  path is an alias, the target directory is used. The path indicates the 
     *  file alias and the real path indicates the target directory. 
     *
     *  @param string $directoryPath the pathname to the directory 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_AllocationAdminSession an <code> 
     *          AllocationAdminSession </code> 
     *  @throws osid_InvalidArgumentException <code> directoryPath </code> is 
     *          not a directory or an alias to a directory 
     *  @throws osid_NotFoundException <code> directoryPath </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> directoryPath </code> or 
     *          <code> authentication </code> is null 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsAllocationAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAllocationAdmin() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getAllocationAdminSessionForDirectory($directoryPath, 
                                                          osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for receiving messages about changes to directories. 
     *
     *  @param object osid_filing_AllocationReceiver $receiver the 
     *          notification callback 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_AllocationNotificationSession an <code> 
     *          AllocationNotificationSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsAllocationNotification() </code> is <code> false 
     *          </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsDirectoryNotification() </code> is <code> true. 
     *              </code> 
     */
    public function getAllocationNotificationSession(osid_filing_AllocationReceiver $receiver, 
                                                     osid_authentication_Authentication $authentication);


    /**
     *  Gets the session for receiving messages about usage warnings and quota 
     *  changes for the given directory. If the path is an alias, the target 
     *  directory is used. The path indicates the file alias and the real path 
     *  indicates the target directory. 
     *
     *  @param object osid_filing_AllocationReceiver $receiver the 
     *          notification callback 
     *  @param string $directoryPath the pathname to the directory 
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_filing_AllocationNotificationSession an <code> 
     *          AllocationNotificationSession </code> 
     *  @throws osid_InvalidArgumentException <code> directoryPath </code> is 
     *          not a directory or an alias to a directory 
     *  @throws osid_NotFoundException <code> directoryPath </code> is not 
     *          found 
     *  @throws osid_NullArgumentException <code> receiver, directoryPath 
     *          </code> or <code> authentication </code> is null 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException a <code> uthentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> 
     *          supportsAllocationNotification() </code> is <code> false 
     *          </code> 
     *  @throws osid_UnsupportedException the <code> authentication </code> 
     *          service is not supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsAllocationNotification() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getAllocationNotificationSessionForDirectory(osid_filing_AllocationReceiver $receiver, 
                                                                 $directoryPath, 
                                                                 osid_authentication_Authentication $authentication);

}
