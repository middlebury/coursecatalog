<?php

/**
 * osid_configuration_ParameterQuery
 * 
 *     Specifies the OSID definition for osid_configuration_ParameterQuery.
 * 
 * Copyright (C) 2008 Massachusetts Institute of Technology. All Rights 
 * Reserved. 
 * 
 *     This Work is being provided by the copyright holder(s) subject to the 
 *     following license. By obtaining, using and/or copying this Work, you 
 *     agree that you have read, understand, and will comply with the 
 *     following terms and conditions. 
 *     
 *     This Work and the information contained herein is provided on an ""AS 
 *     IS"" basis. The Massachusetts Institute of Technology, the Open 
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
 * @package org.osid.configuration
 */


/**
 *  <p>The <code> ParameterQuery </code> is used to assemble search queries. A 
 *  <code> Parameter </code> is available from a <code> ParameterSearchSession 
 *  </code> and defines methods to query for a <code> Parameter </code> that 
 *  includes setting a display name and a description. Once the desired 
 *  parameters are set, the <code> ParameterQuery </code> is given to the 
 *  designated search method. The same <code> ParameterQuery </code> returned 
 *  from the session must be used in the search as the provider may utilize 
 *  implementation-specific data wiithin the object. </p> 
 *  
 *  <p> If multiple data elements are set in this interface, the results 
 *  matching all the given data (eg: <code> AND </code> ) are returned. Search 
 *  methods throughout the OSIDs accept multiple <code> OsidQuery </code> 
 *  interfaces. Each <code> ParaneterQuery </code> in the array behaves like 
 *  an <code> OR </code> such that results are returned that match any of the 
 *  given <code> ParameterQuery </code> objects. Any match method inside a 
 *  <code> ParaneterQuery </code> may be invoked multiple times. In the case 
 *  of a match method, each invocation adds an element to an <code> OR </code> 
 *  expression. Any of these terms may also be negated through the <code> 
 *  match </code> flag. </p> 
 *  
 *  <p> 
 *  <pre>
 *       Parameter { OsidQuery.matchDisplayName AND (ParameterQuery.matchDescription OR Parameter.matchDescription)} OR ParaneterQuery
 *       
 *                   
 *       
 *  </pre>
 *  </p> 
 *  
 *  <p> String searches are described using a string search Type that 
 *  indicates the type of regular expression or wildcarding encoding. 
 *  Compatibility with a strings search Type can be tested within this 
 *  interface. </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_ParameterQuery
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
     *  Adds a description to match. Multiple display name matches can be 
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
     *  Matches a description that has no value. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchUnsetDescription();


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
     *  Matches a genus that has no value. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchUnsetGenusType();


    /**
     *  Sets a <code> Type </code> for querying parameters of a given value 
     *  type. 
     *
     *  @param object osid_type_Type $valueType the parameter value type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> valueType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchValueType(osid_type_Type $valueType, $match);


    /**
     *  Sets the registry <code> Id </code> for this query. 
     *
     *  @param object osid_id_Id $registryId a registryId <code> Id </code> 
     *  @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     *  @throws osid_NullArgumentException <code> registryId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchRegistryId(osid_id_Id $registryId, $match);


    /**
     *  Tests if a <code> RegistryQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a registry query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsRegistryyQuery();


    /**
     *  Gets the query interface for a registry. 
     *
     *  @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     *  @return object osid_configuration_RegistryQuery the registry query 
     *  @throws osid_UnimplementedException <code> supportsRegistryQuery() 
     *          </code> is <code> false </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsRegistryQuery() </code> is <code> true. </code> 
     */
    public function getRegistryQuery($match);


    /**
     *  Gets the registry query interface for the registry type. Supported 
     *  types are defined in the <code> ConnfigurationManager. </code> 
     *
     *  @param object osid_type_Type $registryInterfaceType a registry type 
     *  @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     *  @return object osid_configuration_RegistryQuery the registry query 
     *  @throws osid_NullArgumentException <code> registryInterfaceType 
     *          </code> is <code> null </code> 
     *  @throws osid_UnimplementedException <code> supportsRegistryQuery() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException <code> 
     *          ConfigurationManager.supportsRegistryInterfaceType(registryInterfaceType) 
     *          is false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsRegistryQuery() </code> is <code> true. </code> 
     */
    public function getRegistryQueryForInterfaceType(osid_type_Type $registryInterfaceType, 
                                                     $match);

}
