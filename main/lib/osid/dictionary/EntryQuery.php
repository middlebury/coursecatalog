<?php

/**
 * osid_dictionary_EntryQuery
 * 
 *     Specifies the OSID definition for osid_dictionary_EntryQuery.
 * 
 * Copyright (C) 2002-2008 Massachusetts Institute of Technology. All Rights 
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
 * @package org.osid.dictionary
 */


/**
 *  <p>This is the query interface for searching dictionary entries. Each 
 *  method specifies an <code> AND </code> term while multiple invocations of 
 *  the same method produce a nested <code> OR. </code> </p>
 * 
 * @package org.osid.dictionary
 */
interface osid_dictionary_EntryQuery
{


    /**
     *  Gets the string matching types supported. 
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
     *  Adds a keyword to match among the key and value. 
     *
     *  @param string $keyword a keyword 
     *  @param object osid_type_Type $stringMatchType the string match type 
     *  @param boolean $match <code> true </code> if the keyword is a positive 
     *          match, <code> false </code> for negative match 
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
     *  Sets the <code> Type </code> for querying keys of a given type. 
     *
     *  @param object osid_type_Type $keyType the key <code> Type </code> 
     *  @param boolean $match <code> true </code> if the key type is a 
     *          positive match, <code> false </code> for negative match 
     *  @throws osid_NullArgumentException <code> keyType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchKeyType(osid_type_Type $keyType, $match);


    /**
     *  Matches entries of this key. 
     *
     *  @param object $key the key 
     *  @param boolean $match <code> true </code> if the key is a positive 
     *          match, <code> false </code> for negative match 
     *  @throws osid_NullArgumentException <code> keyType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchKey($key, $match);


    /**
     *  Sets the <code> Type </code> of this entry value. 
     *
     *  @param object osid_type_Type $valueType the value <code> Type </code> 
     *  @param boolean $match <code> true </code> if the value type is a 
     *          positive match, <code> false </code> for negative match 
     *  @throws osid_NullArgumentException <code> valueType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchValueType(osid_type_Type $valueType, $match);


    /**
     *  Sets the value in this entry. 
     *
     *  @param object $value the value 
     *  @param boolean $match <code> true </code> if the value is a positive 
     *          match, <code> false </code> for negative match 
     *  @throws osid_NullArgumentException <code> value </code> is <code> null 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchValue($value, $match);


    /**
     *  Sets the dictionary <code> Id </code> for this query. 
     *
     *  @param object osid_id_Id $dictionaryId a dictionary <code> Id </code> 
     *  @param boolean $match <code> true </code> if a positive match, <code> 
     *          false </code> for negative match 
     *  @throws osid_NullArgumentException <code> dictionaryId </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchDictionaryId(osid_id_Id $dictionaryId, $match);


    /**
     *  Tests if a <code> DictionaryQuery </code> is available. 
     *
     *  @return boolean <code> true </code> if a dictionary query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsDictionaryQuery();


    /**
     *  Gets the query interface for a dictionary. Multiple retrievals produce 
     *  a nested boolean <code> OR </code> term. 
     *
     *  @return object osid_dictionary_DictionaryQuery the dictionary query 
     *  @throws osid_UnimplementedException <code> supportsDictionaryQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsDictionaryQuery() </code> is <code> true. </code> 
     */
    public function getDictionaryQuery();

}
