<?php

/**
 * osid_filing_DirectoryEntry
 * 
 *     Specifies the OSID definition for osid_filing_DirectoryEntry.
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


/**
 *  <p><code> DirectoryEntry </code> defines methods in common to both <code> 
 *  File </code> and <code> Directory. </code> </p>
 * 
 * @package org.osid.filing
 */
interface osid_filing_DirectoryEntry
{


    /**
     *  Gets the name of this entry. The name does not include the path. If 
     *  this entry represents an alias, the name of the alias is returned. 
     *
     *  @return string the entry name 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getName();


    /**
     *  Tests if this entry is an alias. 
     *
     *  @return boolean <code> true </code> if this is an alias, <code> false 
     *          </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAlias();


    /**
     *  Gets the full path of this entry. The path includes the name. Path 
     *  components are separated by a /. If this entry represents an alias, 
     *  the path to the alias is returned. 
     *
     *  @return string the path 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPath();


    /**
     *  Gets the real path of this entry. The path includes the name. Path 
     *  components are separated by a /. If this entry represents an alias, 
     *  the full path to the target file or directory is returned. 
     *
     *  @return string the path 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRealPath();


    /**
     *  Gets the <code> Id </code> of the <code> Agent </code> that owns this 
     *  entry. 
     *
     *  @return object osid_id_Id the <code> Agent Id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getOwnerId();


    /**
     *  Gets the <code> Agent </code> that owns this entry. 
     *
     *  @return object osid_authentication_Agent the <code> Agent </code> 
     *  @throws osid_OperationFailedException authentication service not 
     *          available 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getOwner();


    /**
     *  Gets the created time of this entry. 
     *
     *  @return object osid_calendaring_DateTime the created time 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCreatedTime();


    /**
     *  Gets the last modified time of this entry. 
     *
     *  @return object osid_calendaring_DateTime the last modified time 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLastModifiedTime();


    /**
     *  Gets the last access time of this entry. 
     *
     *  @return object osid_calendaring_DateTime the last access time 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLastAccessTime();


    /**
     *  Gets the record types of this entry. The <code> Type </code> 
     *  explicitly indicates the specification of the record interface. 
     *
     *  @return object osid_type_TypeList the record types 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRecordTypes();


    /**
     *  Tests if this entry supports the given record <code> Type. </code> The 
     *  given type may be supported through inheritence. 
     *
     *  @param object osid_type_Type $recordType a record type 
     *  @return boolean <code> true </code> if this entry has the given record 
     *          <code> Type, </code> <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> recordType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasRecordType(osid_type_Type $recordType);


    /**
     *  Gets the genus type of this object. 
     *
     *  @return object osid_type_Type the genus type of this object 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getGenusType();


    /**
     *  Tests if this object is of the given genus <code> Type. </code> The 
     *  given genus type may be supported by the object through the type 
     *  hierarchy. 
     *
     *  @param object osid_type_Type $genusType a genus type 
     *  @return boolean <code> true </code> if this object is of the given 
     *          genus <code> Type, </code> <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> genusType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isOfGenusType(osid_type_Type $genusType);


    /**
     *  Tests to see if the last method invoked retrieved up-to-date data. 
     *  Simple retrieval methods do not specify errors as, generally, the data 
     *  is retrieved once at the time this object is instantiated. Some 
     *  implementations may provide real-time data though the application may 
     *  not always care. An implementation providing a real-time service may 
     *  fall back to a previous snapshot in case of error. This method returns 
     *  false if the data last retrieved was stale. 
     *
     *  @return boolean <code> true </code> if the last data retrieval was up 
     *          to date, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  Providers should return false unless all getters are 
     *          implemented using real-time queries, or some trigger process 
     *          keeps the data in this object current. Providers should 
     *          populate basic data elements at the time this object is 
     *          instantiated, or set an error, to ensure some data 
     *          availability. 
     */
    public function isCurrent();


    /**
     *  Gets a list of properties corresponding to the interface type of this 
     *  object. Properties provide a means for applications to display a 
     *  representation of the contents of an object without understanding its 
     *  <code> Type </code> specification. Applications needing to examine a 
     *  specific property or perform updates should use the methods defined by 
     *  the object's interface <code> Type. </code> 
     *
     *  @return object osid_PropertyList a list of properties 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException an authorization failure 
     *          occurred 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getProperties();


    /**
     *  Gets a list of properties corresponding to the specified record type. 
     *  Properties provide a means for applications to display a 
     *  representation of the contents of an object without understanding its 
     *  record <code> Type </code> specification. Applications needing to 
     *  examine a specific property or perform updates should use the methods 
     *  defined by the object interface <code> Type. </code> The resulting set 
     *  includes properties specified by parents of the record <code> type 
     *  </code> in the type hierarchy. 
     *
     *  @param object osid_type_Type $recordType the record type corresponding 
     *          to the properties set to retrieve 
     *  @return object osid_PropertyList a list of properties 
     *  @throws osid_NullArgumentException <code> recordType </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException an authorization failure 
     *          occurred 
     *  @throws osid_UnsupportedException <code> hasRecordType(recordType) 
     *          </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPropertiesByRecordType(osid_type_Type $recordType);

}
