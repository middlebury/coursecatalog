<?php

/**
 * osid_OsidSearchResults
 * 
 *     Specifies the OSID definition for osid_OsidSearchResults.
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
 * @package org.osid
 */


/**
 *  <p>This interface provides a means to capture results of a search and is 
 *  used as a vehicle to perform a search within a previous result set. An 
 *  example of searching withina result set: 
 *  <pre>
 *       
 *       
 *       OsidSearch os = session.getObjectSearch();
 *       
 *       OsidQuery query;
 *       query = session.getObjectQuery();
 *       query.matchDescription("*food*", wildcardStringMatchType, true);
 *       ObjectSearchResults results = session.getObjectBySearch(query, os);
 *       
 *       // get new search inteface and reference previous result set
 *       os = session.getObjectSearch();
 *       os.searchWithinResults(results);
 *       
 *       query = session.getObjectQuery();
 *       query.matchDisplayName("pickles", wordStringMatchType, true);
 *       results = session.getObjectsBySearch(query, os);
 *       OsidList pickles = results.getObjectList();
 *       
 *                   
 *       
 *  </pre>
 *  </p>
 * 
 * @package org.osid
 */
interface osid_OsidSearchResults
{


    /**
     *  Returns the size of a result set from a search query. This number 
     *  serves as an estimate to provide feedback for refining search queries 
     *  and may not be the number of elements available through an <code> 
     *  OsidList. </code> 
     *
     *  @return integer the result size 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResultSize();


    /**
     *  Gets the search record types available in this search. A record <code> 
     *  Type </code> explicitly indicates the specification of an interface to 
     *  the record. A record may or may not inherit other record interfaces 
     *  through interface inheritance in which case support of a record type 
     *  may not be explicit in the returned list. Interoperability with the 
     *  typed interface to this object should be performed through <code> 
     *  hasSearchRecordType(). </code> 
     *
     *  @return object osid_type_TypeList the search record types available 
     *          through this object 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSearchRecordTypes();


    /**
     *  Tests if this search results supports the given record <code> Type. 
     *  </code> The given record type may be supported by the object through 
     *  interface/type inheritence. This method should be checked before 
     *  retrieving the record interface. 
     *
     *  @param object osid_type_Type $searchRecordType a type 
     *  @return boolean <code> true </code> if a search record the given 
     *          record <code> Type </code> is available, <code> false </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> searchRecordType </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasSearchRecordType(osid_type_Type $searchRecordType);


    /**
     *  Gets a list of properties. Properties provide a means for applications 
     *  to display a representation of the contents of a search record without 
     *  understanding its <code> Type </code> specification. Applications 
     *  needing to examine a specific property should use the extension 
     *  interface defined by its <code> Type. </code> 
     *
     *  @return object osid_PropertyList a list of properties 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException an authorization failure 
     *          occurred 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getProperties();


    /**
     *  Gets a list of properties corresponding to the specified search record 
     *  type. Properties provide a means for applications to display a 
     *  representation of the contents of a search record without 
     *  understanding its record interface specification. Applications needing 
     *  to examine a specific propertyshould use the methods defined by the 
     *  search record <code> Type. </code> The resulting set includes 
     *  properties specified by parents of the record <code> type </code> in 
     *  the case a record's interface extends another. 
     *
     *  @param object osid_type_Type $searchRecordType the search record type 
     *          corresponding to the properties set to retrieve 
     *  @return object osid_PropertyList a list of properties 
     *  @throws osid_NullArgumentException <code> searchRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException an authorization failure 
     *          occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasSearchRecordType(searchRecordType) </code> is <code> false 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPropertiesBySearchRecordType(osid_type_Type $searchRecordType);

}
