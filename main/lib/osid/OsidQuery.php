<?php

/**
 * osid_OsidQuery
 * 
 *     Specifies the OSID definition for osid_OsidQuery.
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
 *  <p>The <code> OsidQuery </code> is used to assemble search queries. An 
 *  <code> OsidQuery </code> is available from an <code> OsidSession </code> 
 *  and defines methods to query for an <code> OsidObject </code> that 
 *  includes setting a display name and a description. Once the desired 
 *  parameters are set, the <code> OsidQuery </code> is given to the 
 *  designated search method. The same <code> OsidQuery </code> returned from 
 *  the session must be used in the search as the provider may utilize 
 *  implementation-specific data wiithin the object. </p> 
 *  
 *  <p> If multiple data elements are set in this interface, the results 
 *  matching all the given data (eg: AND) are returned. Any match method 
 *  inside an <code> OsidQuery </code> may be invoked multiple times. In the 
 *  case of a match method, each invocation adds an element to an <code> OR 
 *  </code> expression. Any of these terms may also be negated through the 
 *  <code> match </code> flag. </p> 
 *  
 *  <p> 
 *  <pre>
 *       
 *       
 *       OsidQuery { OsidQuery.matchDisplayName AND (OsidQuery.matchDescription OR OsidQuery.matchDescription)}
 *       
 *                   
 *       
 *  </pre>
 *  </p> 
 *  
 *  <p> <code> OsidObjects </code> allow for the definition of an additonal 
 *  records and the <code> OsidQuery </code> parallels this mechanism. An 
 *  interface type of an <code> OsidObject </code> record must also define the 
 *  corresponding <code> OsidQuery </code> record which is available through 
 *  query interfaces. Multiple requests of these typed interfaces may return 
 *  the same underlying object and thus it is only useful to request once. 
 *  String searches are described using a string search <code> Type </code> 
 *  that indicates the type of regular expression or wildcarding encoding. 
 *  Compatibility with a strings search <code> Type </code> can be tested 
 *  within this interface. <code> </code> </p> 
 *  
 *  <p> <code> </code> As with all aspects of OSIDs, <code> </code> nulls 
 *  cannot be used. Separate tests are available for querying for unset values 
 *  except for required fields. </p> 
 *  
 *  <p> An example to find all objects whose name starts with "Fred" or whose 
 *  name starts with "Barney", but the word "dinosaur" does not appear in the 
 *  description and not the color is not purple.. <code> ColorQuery </code> is 
 *  a record of the object that defines a color. </p> 
 *  
 *  <p> 
 *  <pre>
 *       
 *       
 *       ObjectQuery query;
 *       
 *                   
 *       
 *  </pre>
 *  </p> 
 *  
 *  <p> 
 *  <pre>
 *       
 *       
 *       query = session.getObjectQuery();
 *       query.matchDisplayName("Fred*", wildcardStringMatchType, true);
 *       query.matchDisplayName("Barney*", wildcardStringMatchType, true);
 *       query.matchDescriptionMatch("dinosaur", wordStringMatchType, false);
 *       
 *       ColorQuery recordQuery;
 *       recordQuery = query.getObjectRecord(colorRecordType);
 *       recordQuery.matchColor("purple", false);
 *       ObjectList list = session.getObjectsByQuery(query);
 *       
 *                   
 *       
 *  </pre>
 *  </p>
 * 
 * @package org.osid
 */
interface osid_OsidQuery
{


    /**
     *  Gets the string matching types supported. A string match type 
     *  specifies the syntax of the string query, such as matching a word or 
     *  including a wildcard or regular expression. 
     *
     *  @return object osid_type_TypeList a list containing the supported 
     *          string match types 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getStringMatchTypes();


    /**
     *  Tests if the given string matching type is supported. 
     *
     *  @param object osid_type_Type $searchType a <code> Type </code> 
     *          indicating a string match type 
     *  @return boolean <code> true </code> if the given Type is supported, 
     *          <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsStringMatchType(osid_type_Type $searchType);


    /**
     *  Adds a keyword to match. Multiple keywords can be added to perform a 
     *  boolean <code> OR </code> among them. A keyword may be applied to any 
     *  of the elements defined in this object such as the display name, 
     *  description or any method defined in an interface implemented by this 
     *  object. 
     *
     *  @param string $keyword keyword to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> keyword is </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> keyword </code> or <code> 
     *          stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchKeyword($keyword, osid_type_Type $stringMatchType, 
                                 $match);


    /**
     *  Adds a display name to match. Multiple display name matches can be 
     *  added to perform a boolean <code> OR </code> among them. 
     *  <br/><br/>
     *  
     *
     *  @param string $displayName display name to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> keyword is </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> displayName </code> or 
     *          <code> stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchDisplayName($displayName, 
                                     osid_type_Type $stringMatchType, $match);


    /**
     *  Adds a description name to match. Multiple description matches can be 
     *  added to perform a boolean <code> OR </code> among them. 
     *
     *  @param string $description description to match 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_InvalidArgumentException <code> keyword is </code> not of 
     *          <code> stringMatchType </code> 
     *  @throws osid_NullArgumentException <code> description </code> or 
     *          <code> stringMatchType </code> is <code> null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsStringMatchType(stringMatchType) </code> is <code> 
     *          false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchDescription($description, 
                                     osid_type_Type $stringMatchType, $match);


    /**
     *  Matches a description that has any value. 
     *
     *  @param boolean $match <code> true </code> to match any description, 
     *          <code> false </code> to match descriptions with no values 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchAnyDescription($match);


    /**
     *  Sets a <code> Type </code> for querying objects of a given genus. A 
     *  genus type matches if the specified type is the same genus as the 
     *  object genus type. 
     *
     *  @param object osid_type_Type $genusType the object genus type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> genusType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchGenusType(osid_type_Type $genusType, $match);


    /**
     *  Sets a <code> Type </code> for querying objects of a given genus. A 
     *  genus type matches if the specified type is the same genus as the 
     *  object or if the specified type is an ancestor of the object genus in 
     *  a type hierarchy. 
     *
     *  @param object osid_type_Type $genusType the object genus type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> genusType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchParentGenusType(osid_type_Type $genusType, $match);


    /**
     *  Sets a <code> Type </code> for querying objects having records 
     *  implementing a given record type. This includes records of the same 
     *  interface type as the one provided and records implementing an 
     *  ancestor interface type in an interface hierarchy. 
     *
     *  @param object osid_type_Type $recordType the record interface type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> recordType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchRecordType(osid_type_Type $recordType, $match);


    /**
     *  Tests if this query supports the given record <code> Type. </code> The 
     *  given record type may be supported by the object through 
     *  interface/type inheritence. This method should be checked before 
     *  retrieving the record interface. 
     *
     *  @param object osid_type_Type $recordType a type 
     *  @return boolean <code> true </code> if a record query of the given 
     *          record <code> Type </code> is available, <code> false </code> 
     *          otherwise 
     *  @throws osid_NullArgumentException <code> recordType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasRecordType(osid_type_Type $recordType);

}
