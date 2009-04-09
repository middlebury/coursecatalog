<?php

/**
 * osid_filing_DirectoryAdminSession
 * 
 *     Specifies the OSID definition for osid_filing_DirectoryAdminSession.
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
 *  <p>This session creates and removes files and directories under the 
 *  directory associated with this session. </p> 
 *  
 *  <p> The view of the administrative methods defined in this session is 
 *  determined by the provider. For an instance of this session where no 
 *  repository has been specified, it may not be parallel to the <code> 
 *  DirectoryLookupSession. </code> For example, a default <code> 
 *  DirectoryLookupSession </code> may view the entire directory hierarchy 
 *  while the default <code> DirectoryAdminSession </code> uses an isolated 
 *  <code> Directory </code> to create new entries. Another scenario is a 
 *  federated provider who does not wish to permit administrative operations 
 *  for the federation unaware. </p>
 * 
 * @package org.osid.filing
 */
interface osid_filing_DirectoryAdminSession
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
     *  Tests if this user can create or remove entries in this directory. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known modifying this directory will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer create operations to 
     *  an unauthorized user. 
     *
     *  @return boolean <code> false </code> if modifying this directory is 
     *          not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canModifyDirectory();


    /**
     *  Overwrite files if a destination pathname exists and is a file. 
     *
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function overwrite();


    /**
     *  Tests if this user can create a single <code> File </code> using the 
     *  desired record types. While <code> FilingManager.getFileRecordTypes() 
     *  </code> can be used to examine which records are supported, this 
     *  method tests which record(s) are required for creating a specific 
     *  <code> File. </code> Providing an empty array tests if a <code> File 
     *  </code> can be created with no records. 
     *
     *  @param array $fileRecordTypes array of types 
     *  @return boolean <code> true </code> if <code> File </code> creation 
     *          using the specified interface <code> Types </code> is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> fileRecordTypes </code> is 
     *          <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateFileWithRecordTypes(array $fileRecordTypes);


    /**
     *  Gets the file form for creating new files. A new form should be 
     *  requested for each create transaction. 
     *
     *  @param object osid_type_Type $fileInterfaceType the interface <code> 
     *          Type </code> of the <code> Directory </code> to be created 
     *  @return object osid_filing_FileForm the file form 
     *  @throws osid_NullArgumentException <code> fileInterfaceType </code> is 
     *          <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          canCreateFileWithInterfaceTypes(fileInterfaceType) </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getFileFormForCreate(osid_type_Type $fileInterfaceType);


    /**
     *  Creates a new file in this directory. 
     *
     *  @param object osid_filing_FileForm $fileForm the file form 
     *  @return object osid_filing_File the new file 
     *  @throws osid_AlreadyExistsException <code> name </code> already exists 
     *          as a file in this direrctory and <code> overwrite() </code> is 
     *          <code> false </code> 
     *  @throws osid_InvalidArgumentException one or more of the form elements 
     *          is invalid 
     *  @throws osid_NullArgumentException <code> fileForm </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> fileForm </code> is not 
     *          supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createFile(osid_filing_FileForm $fileForm);


    /**
     *  Gets the file form for updating an existing files. A new file form 
     *  should be requested for each update transaction. 
     *
     *  @param string $name name of the file to update 
     *  @return object osid_filing_FileForm the file form 
     *  @throws osid_NotFoundException <code> name </code> is not found 
     *  @throws osid_NullArgumentException <code> name </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getFileFormForUpdate($name);


    /**
     *  Updates an existing file. 
     *
     *  @param string $name name of the file 
     *  @param object osid_filing_FileForm $fileForm the form containing the 
     *          elements to be updated 
     *  @throws osid_InvalidArgumentException the form contains an invalid 
     *          value 
     *  @throws osid_NotFoundException <code> name </code> is not found 
     *  @throws osid_NullArgumentException <code> name </code> or <code> 
     *          fileForm </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> fileForm </code> is not 
     *          supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateFile($name, osid_filing_FileForm $fileForm);


    /**
     *  Deletes a file. 
     *
     *  @param string $name the name of the file to delete 
     *  @throws osid_NotFoundException <code> name </code> is not found 
     *  @throws osid_NullArgumentException <code> name </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function removeFile($name);


    /**
     *  Tests if this user can create a single <code> Directory </code> using 
     *  the desired record types. While <code> 
     *  FilingManager.getDiectoryRecordTypes() </code> can be used to examine 
     *  which records are supported, this method tests which record(s) are 
     *  required for creating a specific <code> Directory. </code> Providing 
     *  an empty array tests if a <code> Directory </code> can be created with 
     *  no records. 
     *
     *  @param array $directoryRecordTypes array of types 
     *  @return boolean <code> true </code> if <code> Directory </code> 
     *          creation using the specified record <code> Types </code> is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> directoryRecordTypes </code> 
     *          is <code> null </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canCreateDirectoryWithRecordTypes(array $directoryRecordTypes);


    /**
     *  Gets the file form for creating new directories. A new form should be 
     *  requested for each create transaction. This method is used for 
     *  creating new <code> Directories </code> where only the <code> 
     *  Directory </code> <code> Type </code> is known. 
     *
     *  @param object osid_type_Type $directoryInterfaceType the interface 
     *          <code> Type </code> of the <code> Directory </code> to be 
     *          created 
     *  @return object osid_filing_DirectoryForm the directory form 
     *  @throws osid_NullArgumentException <code> directoryInterfaceType 
     *          </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          canCreateDirectoryWithInterfaceTypes(directoryInterfaceType) 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDirectoryFormForCreate(osid_type_Type $directoryInterfaceType);


    /**
     *  Creates a new directory. 
     *
     *  @param object osid_filing_DirectoryForm $directoryForm the directory 
     *          form 
     *  @return object osid_filing_Directory the new directory 
     *  @throws osid_AlreadyExistsException <code> name </code> already exists 
     *          in this direrctory 
     *  @throws osid_InvalidArgumentException one or more of the form elements 
     *          is invalid 
     *  @throws osid_NullArgumentException <code> directoryForm </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function createDirectory(osid_filing_DirectoryForm $directoryForm);


    /**
     *  Gets the directory form for updating an existing files. A new 
     *  directory form should be requested for each update transaction. This 
     *  method is used when the <code> Directory </code> to be updated is 
     *  known and is desired to access any metadata specific to the <code> 
     *  Directory </code> being updated. 
     *
     *  @param string $name name of the directory to update 
     *  @return object osid_filing_DirectoryForm the directory form 
     *  @throws osid_NotFoundException <code> name </code> is not found 
     *  @throws osid_NullArgumentException <code> name </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDirectoryFormForUpdate($name);


    /**
     *  Updates an existing directory. 
     *
     *  @param string $name name of the file 
     *  @param object osid_filing_DirectoryForm $directory the form containing 
     *          the elements to be updated 
     *  @throws osid_InvalidArgumentException the form contains an invalid 
     *          value 
     *  @throws osid_NotFoundException <code> name </code> is not found 
     *  @throws osid_NullArgumentException <code> name </code> or <code> 
     *          directoryForm </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> directoryForm </code> is not 
     *          supported 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function updateDirectory($name, 
                                    osid_filing_DirectoryForm $directory);


    /**
     *  Deletes a directory in this directory. The directory to remove must be 
     *  empty. 
     *
     *  @param string $name the name of the directory to delete 
     *  @throws osid_NotFoundException <code> name </code> is not found 
     *  @throws osid_NullArgumentException <code> name </code> is <code> null 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function removeDirectory($name);

}
