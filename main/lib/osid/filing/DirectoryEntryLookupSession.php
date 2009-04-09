<?php

/**
 * osid_filing_DirectoryEntryLookupSession
 * 
 *     Specifies the OSID definition for osid_filing_DirectoryEntryLookupSession.
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
 *  <p>This session defines methods for operating on files and directories. A 
 *  <code> DirectoryENtrySession </code> is associated with a directory that 
 *  behaves as the current directory path for supplying relative path names. 
 *  Absolute path names can be supplied to access any file or directory in the 
 *  file system. </p> 
 *  
 *  <p> This session defines the following views: </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered 
 *      </li> 
 *      <li> plenary view: provides a complete result set or is an error 
 *      condition </li> 
 *      <li> federated directory view: searches include entries in directories 
 *      of which this directory is an ancestor </li> 
 *      <li> isolated directory view: lookups are restricted to entries in 
 *      this directory only </li> 
 *  </ul>
 *  Generally, the comparative view should be used for most applications as it 
 *  permits operation even if there is data out of sync. Some administrative 
 *  applications may need to know whether it had retrieved an entire set of 
 *  objects and may sacrifice some interoperability for the sake of precision 
 *  using the plenary view. </p>
 * 
 * @package org.osid.filing
 */
interface osid_filing_DirectoryEntryLookupSession
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
     *  Tests if this user can perform entry lookups. A return of true does 
     *  not guarantee successful authorization. A return of false indicates 
     *  that it is known all methods in this session will result in a <code> 
     *  PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer lookup operations. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupDirectoryEntries();


    /**
     *  Tests if the directory associated with this session has a parent 
     *  directory. 
     *
     *  @return boolean <code> true </code> if a parent exists, <code> false 
     *          </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function hasParentDirectory();


    /**
     *  Gets the parent of the directory associated with this session. 
     *
     *  @return object osid_filing_Directory the parent of the directory 
     *          associated with this session 
     *  @throws osid_IllegalStateException <code> hasParentDirectory() </code> 
     *          is <code> false </code> or this session has been closed 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getParentDirectory();


    /**
     *  The returns from the lookup methods may omit or translate elements 
     *  based on this session, such as authorization, and not result in an 
     *  error. This view is used when greater interoperability is desired at 
     *  the expense of precision. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useComparativeDirectoryView();


    /**
     *  A complete view of the file or directory returns is desired. Methods 
     *  will return what is requested or result in an error. This view is used 
     *  when greater precision is desired at the expense of interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryDirectoryView();


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
     *  Tests if a file, directory or alias name exists. In a federated view, 
     *  the existence test is performed on this directory and any children of 
     *  this directory. In an isolated view, the existence test is restrcited 
     *  to this directory only. 
     *
     *  @param string $name a file or directory name 
     *  @return boolean <code> true </code> if the name exists, <code> false 
     *          </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function exists($name);


    /**
     *  Tests if a name exists and is a file or an alias to a file. In a 
     *  federated view, the exietence test is performed on this directory and 
     *  any children of this directory. In an isolated view, the existence 
     *  test is restrcited to this directory only. 
     *
     *  @param string $name a file name 
     *  @return boolean <code> true </code> if the name is a file, <code> 
     *          false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function isFile($name);


    /**
     *  Tests if a name exists and is a directory or an alias to a directory. 
     *  In a federated view, the exietence test is performed on this directory 
     *  and any children of this directory. In an isolated view, the existence 
     *  test is restrcited to this directory only. 
     *
     *  @param string $name a file or directory name 
     *  @return boolean <code> true </code> if the path is a directory, <code> 
     *          false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function isDirectory($name);


    /**
     *  Tests if a name exists and is an alias. In a federated view, the 
     *  exietence test is performed on this directory and any children of this 
     *  directory. In an isolated view, the existence test is restrcited to 
     *  this directory only. 
     *
     *  @param string $name a file or directory name 
     *  @return boolean <code> true </code> if the path is an alias, <code> 
     *          false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function isAlias($name);


    /**
     *  Gets a specified file or alias to the file by its name in the current 
     *  directory only. For federated views, use <code> getFilesByName(). 
     *  </code> 
     *
     *  @param string $name the name to the file 
     *  @return object osid_filing_File the file 
     *  @throws osid_NotFoundException <code> name </code> is not found or is 
     *          a directory 
     *  @throws osid_NullArgumentException <code> name </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getFile($name);


    /**
     *  Gets the list of files and aliases to files in this directory. In a 
     *  federated view, this method returns all files in descendant 
     *  directories. 
     *
     *  @return object osid_filing_FileList the list of files in this 
     *          directory 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getFiles();


    /**
     *  Gets a specified files and aliases to files for the given file name. 
     *  In an isolated view, this method behaves like <code> getFile(). 
     *  </code> Is a federated view, this method returns a list of files by 
     *  the same name in descendant directories. 
     *
     *  @param string $name the name of the file 
     *  @return object osid_filing_FileList the list of files of the given 
     *          name 
     *  @throws osid_NotFoundException <code> name </code> is not found or is 
     *          a directory 
     *  @throws osid_NullArgumentException <code> name </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getFilesByName($name);


    /**
     *  Gets a <code> FileList </code> corresponding to the given file genus 
     *  <code> Type </code> which does not include files of genus types 
     *  derived from the specified <code> Type. </code> In plenary mode, the 
     *  returned list contains all known files or an error results. Otherwise, 
     *  the returned list may contain only those files that are accessible 
     *  through this session. In both cases, the order of the set is not 
     *  specified. 
     *
     *  @param object osid_type_Type $fileGenusType a file genus type 
     *  @return object osid_filing_FileList the returned <code> File list 
     *          </code> 
     *  @throws osid_NullArgumentException <code> fileGenusType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getFilesByGenusType(osid_type_Type $fileGenusType);


    /**
     *  Gets a <code> FileList </code> corresponding to the given file genus 
     *  <code> Type </code> and include any additional files with genus types 
     *  derived from the specified <code> Type. </code> In plenary mode, the 
     *  returned list contains all known files or an error results. Otherwise, 
     *  the returned list may contain only those files that are accessible 
     *  through this session. In both cases, the order of the set is not 
     *  specified. 
     *
     *  @param object osid_type_Type $fileGenusType a file genus type 
     *  @return object osid_filing_FileList the returned <code> File list 
     *          </code> 
     *  @throws osid_NullArgumentException <code> fileGenusType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getFilesByParentGenusType(osid_type_Type $fileGenusType);


    /**
     *  Gets a <code> FileList </code> corresponding to the given file record 
     *  <code> Type. </code> The set of files implementing the given interface 
     *  type is returned. <code> </code> In plenary mode, the returned list 
     *  contains all known files or an error results. Otherwise, the returned 
     *  list may contain only those files that are accessible through this 
     *  session. In both cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $fileInterfaceType a file interface type 
     *  @return object osid_filing_FileList the returned <code> File list 
     *          </code> 
     *  @throws osid_NullArgumentException <code> fileInterfaceType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getFilesByRecordType(osid_type_Type $fileInterfaceType);


    /**
     *  Gets a specified directory or alias to the directory by its name in 
     *  the current directory only. For federated views, use <code> 
     *  getDirectoriesByName(). </code> 
     *
     *  @param string $name the name of the directory 
     *  @return object osid_filing_Directory the directory 
     *  @throws osid_NotFoundException <code> name </code> is not found or is 
     *          a file 
     *  @throws osid_NullArgumentException <code> name </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSubdirectory($name);


    /**
     *  Gets the list of directories and aliases to directories in this 
     *  directory. In a federated view, this method returns all directories in 
     *  descedent directories. 
     *
     *  @return object osid_filing_DirectoryList the list of directories in 
     *          this directory 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSubdirectories();


    /**
     *  Gets a specified directories and aliases to directories for the given 
     *  directory name. In an isolated view, this method behaves like <code> 
     *  getDirectory(). </code> Is a federated view, this method returns a 
     *  list of directories by the same name in descedent directories. 
     *
     *  @param string $name the name of the file 
     *  @return object osid_filing_FileList the list of files of the given 
     *          name 
     *  @throws osid_NotFoundException <code> name </code> is not found or is 
     *          a directory 
     *  @throws osid_NullArgumentException <code> name </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSubdirectoriesByName($name);


    /**
     *  Gets a <code> DirectoryList </code> corresponding to the given 
     *  directory genus <code> Type </code> which does not include directories 
     *  of genus types derived from the specified <code> Type. </code> In 
     *  plenary mode, the returned list contains all known directories or an 
     *  error results. Otherwise, the returned list may contain only those 
     *  directories that are accessible through this session. In both cases, 
     *  the order of the set is not specified. 
     *
     *  @param object osid_type_Type $directoryGenusType a directory genus 
     *          type 
     *  @return object osid_filing_DirectoryList the returned <code> Directory 
     *          list </code> 
     *  @throws osid_NullArgumentException <code> directoryGenusType </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSubdirectoriesByGenusType(osid_type_Type $directoryGenusType);


    /**
     *  Gets a <code> DirectoryList </code> corresponding to the given 
     *  directory genus <code> Type </code> and include any additional 
     *  directories with genus types derived from the specified <code> Type. 
     *  </code> In plenary mode, the returned list contains all known 
     *  directories or an error results. Otherwise, the returned list may 
     *  contain only those directories that are accessible through this 
     *  session. In both cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $directoryGenusType a directory genus 
     *          type 
     *  @return object osid_filing_DirectoryList the returned <code> Directory 
     *          list </code> 
     *  @throws osid_NullArgumentException <code> directoryGenusType </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSubdrectoriesByParentGenusType(osid_type_Type $directoryGenusType);


    /**
     *  Gets a <code> DirectoryList </code> corresponding to the given 
     *  directory record <code> Type. </code> The set of directories 
     *  implementing the given directory record type is returned. <code> 
     *  </code> In plenary mode, the returned list contains all known 
     *  directories or an error results. Otherwise, the returned list may 
     *  contain only those directories that are accessible through this 
     *  session. In both cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $directoryRecordType a directory record 
     *          type 
     *  @return object osid_filing_DirectoryList the returned <code> Directory 
     *          list </code> 
     *  @throws osid_NullArgumentException <code> directoryRecordType </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSuibdirectoriesByRecordType(osid_type_Type $directoryRecordType);

}
