<?php

/**
 * osid_filing_FilingProfile
 * 
 *     Specifies the OSID definition for osid_filing_FilingProfile.
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

require_once(dirname(__FILE__)."/../OsidProfile.php");

/**
 *  <p>The filing profile describes the interoperability among filing 
 *  services. </p>
 * 
 * @package org.osid.filing
 */
interface osid_filing_FilingProfile
    extends osid_OsidProfile
{


    /**
     *  Tests if any dictionary federation is exposed. Federation is exposed 
     *  when a specific dictionary may be identified, selected and used to 
     *  create a lookup or admin session. Federation is not exposed when a set 
     *  of dictionaries appears as a single dictionary. 
     *
     *  @return boolean <code> true </code> if federation is visible <code> 
     *          false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsVisibleFederation();


    /**
     *  Tests if a <code> FileSession </code> is supported. 
     *
     *  @return boolean <code> true </code> if a <code> FilingSession </code> 
     *          is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsFiles();


    /**
     *  Tests if file searching is supported. 
     *
     *  @return boolean <code> true </code> if a <code> FilingSearchSession 
     *          </code> is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsFileSearch();


    /**
     *  Tests if file notification is supported. 
     *
     *  @return boolean <code> true </code> if a <code> 
     *          FilingNotificationSession </code> is available, <code> false 
     *          </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsFileNotification();


    /**
     *  Tests if a <code> DirectoryEntryLookupSession </code> is supported. 
     *
     *  @return boolean <code> true </code> if a <code> 
     *          DirectoryEntryLookupSession </code> is available, <code> false 
     *          </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsDirectoryEntryLookup();


    /**
     *  Tests if directory searching is supported. 
     *
     *  @return boolean <code> true </code> if a <code> DirectorySearchSession 
     *          </code> is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsDirectorySearch();


    /**
     *  Tests if directory administration is supported. 
     *
     *  @return boolean <code> true </code> if a <code> DirectoryAdminSession 
     *          </code> is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsDirectoryAdmin();


    /**
     *  Tests if a directory <code> </code> notification service is supported. 
     *
     *  @return boolean <code> true </code> if a <code> 
     *          DirectoryNotificationSession </code> is available, <code> 
     *          false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsDirectoryNotification();


    /**
     *  Tests if a file management service is supported. 
     *
     *  @return boolean <code> true </code> if a <code> FileManagementSession 
     *          </code> is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsFilingManagement();


    /**
     *  Tests if filing allocation is supported. 
     *
     *  @return boolean <code> true </code> if a <code> AllocationSession 
     *          </code> is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAllocation();


    /**
     *  Tests if quota administration is supported. 
     *
     *  @return boolean <code> true </code> if a <code> AllocationAdminSession 
     *          </code> is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAllocationAdmin();


    /**
     *  Tests if an allocation <code> </code> notification service is 
     *  supported. 
     *
     *  @return boolean <code> true </code> if a <code> 
     *          AllocationNotificationSession </code> is available, <code> 
     *          false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsAllocationNotification();


    /**
     *  Gets the supported file record types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> File </code> record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getFileRecordTypes();


    /**
     *  Tests if the given file record type is supported. 
     *
     *  @param object osid_type_Type $fileRecordType a <code> Type </code> 
     *          indicating a file record type 
     *  @return boolean <code> true </code> if the given record <code> Type 
     *          </code> is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsFileRecordType(osid_type_Type $fileRecordType);


    /**
     *  Gets the supported file search record types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> File </code> search record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getFileSearchRecordTypes();


    /**
     *  Tests if the given file search record type is supported. 
     *
     *  @param object osid_type_Type $fileSearchRecordType a <code> Type 
     *          </code> indicating a file search record type 
     *  @return boolean <code> true </code> if the given search record <code> 
     *          Type </code> is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsFileSearchRecordType(osid_type_Type $fileSearchRecordType);


    /**
     *  Gets the supported directory record types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> Directory </code> record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDirectoryRecordTypes();


    /**
     *  Tests if the given directory record type is supported. 
     *
     *  @param object osid_type_Type $directoryRecordType a <code> Type 
     *          </code> indicating a directory record type 
     *  @return boolean <code> true </code> if the given record <code> Type 
     *          </code> is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsDirectoryRecordType(osid_type_Type $directoryRecordType);


    /**
     *  Gets the supported directory search record types. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          <code> Directory </code> search record types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDirectorySearchRecordTypes();


    /**
     *  Tests if the given directory search record type is supported. 
     *
     *  @param object osid_type_Type $directorySearchRecordType a <code> Type 
     *          </code> indicating a directory search record type 
     *  @return boolean <code> true </code> if the given search record <code> 
     *          Type </code> is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsDirectorySearchRecordType(osid_type_Type $directorySearchRecordType);

}
