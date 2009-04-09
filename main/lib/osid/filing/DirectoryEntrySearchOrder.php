<?php

/**
 * osid_filing_DirectoryEntrySearchOrder
 * 
 *     Specifies the OSID definition for osid_filing_DirectoryEntrySearchOrder.
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
 *  <p>An interface for specifying the order of search results. </p>
 * 
 * @package org.osid.filing
 */
interface osid_filing_DirectoryEntrySearchOrder
{


    /**
     *  Specifies a preference for ordering the result set in an ascending 
     *  manner. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function ascend();


    /**
     *  Specifies a preference for ordering the result set in a descending 
     *  manner. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function descend();


    /**
     *  Specifies a preference for ordering the result set by the entry name. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByName();


    /**
     *  Specifies a preference for ordering the result set by the entry path. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByPath();


    /**
     *  Specifies a preference for ordering the result set by the entry owner. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByOwner();


    /**
     *  Specifies a preference for ordering the result set by the entry 
     *  creation time. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByCreatedTime();


    /**
     *  Specifies a preference for ordering the result set by the entry 
     *  modification time. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByModifiedTime();


    /**
     *  Specifies a preference for ordering the result set by the entry last 
     *  access time. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByLastAccessTime();


    /**
     *  Specifies a preference for ordering the result set by the entry genus 
     *  type. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByGenusType();


    /**
     *  Tests if this search order supports the given record <code> Type. 
     *  </code> The given record type may be supported by the object through 
     *  interface/type inheritence. This method should be checked before 
     *  retrieving the record interface. 
     *
     *  @param object osid_type_Type $recordType a type 
     *  @return boolean <code> true </code> if an order record of the given 
     *          record <code> Type </code> is available, <code> false </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> recordType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasRecordType(osid_type_Type $recordType);

}
