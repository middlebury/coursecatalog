<?php

/**
 * osid_filing_FilingManagementSession
 * 
 *     Specifies the OSID definition for osid_filing_FilingManagementSession.
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
 *  <p>This session defines methods for operating on files and directories. 
 *  This session is an expanded version of the <code> DirectoryAdminSession 
 *  </code> that defines methods requiring path names for navigating a 
 *  federation of directories as opposed to working within a single directory 
 *  node. The directory associated with this session is the current working 
 *  directory and any relative path names provided are with respect to this 
 *  directory. </p>
 * 
 * @package org.osid.filing
 */
interface osid_filing_FilingManagementSession
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
     *  Tests if this user can perform functions in this session. A return of 
     *  true does not guarantee successful authorization. A return of false 
     *  indicates that it is known all methods in this session will result in 
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if filing management methods are 
     *          not authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canManageFiling();


    /**
     *  Overwrite files if a destination pathname exists. 
     *
     *  @param boolean $overwite <code> true </code> if files can be 
     *          overwritten, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function overwrite($overwite);


    /**
     *  Create any missing directories for a destination path that does not 
     *  exist. 
     *
     *  @param boolean $create <code> true </code> if intermediate directories 
     *          should be created, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function createMissingPaths($create);


    /**
     *  Moves a file to another path. The detination path may be a file or 
     *  directory. If the destination is a file and exists, the destination is 
     *  only replaced if <code> overwrite() </code> is <code> true. </code> If 
     *  the destination is a directory and does not exist, the missing 
     *  directories are only created if <code> createMissingPaths() </code> is 
     *  <code> true. </code> 
     *
     *  @param string $src the source name or path of the file 
     *  @param string $dst the destination name or path of the directory or 
     *          file 
     *  @throws osid_AlreadyExistsException <code> dst </code> exists and 
     *          <code> overwrite() </code> is <code> false </code> 
     *  @throws osid_InvalidArgumentException <code> src </code> is not a file 
     *  @throws osid_NotFoundException <code> src </code> is not found, or the 
     *          path to <code> dst </code> is not found and <code> 
     *          createMissingPaths() </code> is <code> false </code> 
     *  @throws osid_NullArgumentException <code> src </code> or <code> dst 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function moveFile($src, $dst);


    /**
     *  Moves a directory to another path. The destination must be a directory 
     *  and if exists, the source directory is placed as a child to the given 
     *  directory. If a path component in the destination does not exist, the 
     *  path is created is <code> createMissingPaths() </code> is <code> true. 
     *  </code> 
     *
     *  @param string $src the source name or path of the directory 
     *  @param string $dst the destination name or path of the directory 
     *  @throws osid_InvalidArgumentException <code> src </code> is not a 
     *          directory 
     *  @throws osid_NotFoundException <code> src </code> is not found, or the 
     *          path to <code> dst </code> is not found and <code> 
     *          createMissingPaths() </code> is <code> false </code> 
     *  @throws osid_NullArgumentException <code> src </code> or <code> dst 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function moveDirectory($src, $dst);


    /**
     *  Copies a file to another path. The detination path may be a file or 
     *  directory. If the destination is a file and exists, the destination is 
     *  only replaced if <code> overwrite() </code> is <code> true. </code> If 
     *  the destination is a directory and does not exist, the missing 
     *  directories are only created if <code> createMissingPaths() </code> is 
     *  <code> true. </code> 
     *
     *  @param string $src the source name or path of the file 
     *  @param string $dst the destination name or path of the directory or 
     *          file 
     *  @throws osid_AlreadyExistsException <code> dst </code> exists and 
     *          <code> overwrite() </code> is <code> false </code> 
     *  @throws osid_InvalidArgumentException <code> src </code> is not a file 
     *  @throws osid_NotFoundException <code> src </code> is not found, or the 
     *          path to <code> dst </code> is not found and <code> 
     *          createMissingPaths() </code> is <code> false </code> 
     *  @throws osid_NullArgumentException <code> src </code> or <code> dst 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function copyFile($src, $dst);


    /**
     *  Copies a directory and all of its contents to another path. The 
     *  destination must be a directory and if exists, the source directory is 
     *  placed as a child to the given directory. If a path component in the 
     *  destination does not exist, the path is created is <code> 
     *  createMissingPaths() </code> is <code> true. </code> 
     *
     *  @param string $src the source name or path of the directory 
     *  @param string $dst the destination name or path of the directory 
     *  @throws osid_InvalidArgumentException <code> src </code> is not a 
     *          directory 
     *  @throws osid_NotFoundException <code> src </code> is not found, or the 
     *          path to <code> dst </code> is not found and <code> 
     *          createMissingPaths() </code> is <code> false </code> 
     *  @throws osid_NullArgumentException <code> src </code> or <code> dst 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function copyDirectory($src, $dst);


    /**
     *  Creates an alias from one path to another. 
     *
     *  @param string $src the source path 
     *  @param string $dst the destination path 
     *  @throws osid_AlreadyExistsException <code> src </code> exists 
     *  @throws osid_NotFoundException <code> src </code> is not found, or the 
     *          path to <code> dst </code> is not found and <code> 
     *          createMissingPaths() </code> is <code> false </code> 
     *  @throws osid_NullArgumentException <code> src </code> or <code> dst 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function alias($src, $dst);


    /**
     *  Removes an alias. 
     *
     *  @param string $aliasPath the relative or absolute path to the alias. 
     *  @throws osid_NotFoundException <code> aliasPath </code> not found 
     *  @throws osid_NullArgumentException <code> aliasPath </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function unalias($aliasPath);

}
