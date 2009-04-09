<?php

/**
 * osid_type_Type
 * 
 *     Specifies the OSID definition for osid_type_Type.
 * 
 * Copyright (C) 2002-2007 Massachusetts Institute of Technology. All Rights 
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
 * @package org.osid.type
 */


/**
 *  <p>The Type is a form of identifier that is primarily used to identify 
 *  interface specifications. The <code> Type </code> differs from <code> Id 
 *  </code> in that it offers display information and <code> Types </code> may 
 *  be arranged in hierarchies to indicate an extended interface. 
 *  Semantically, an <code> Id </code> identifies any OSID object while the 
 *  <code> Type </code> identifies a specification. </p> 
 *  
 *  <p> The components of the Type that make up its identification are: </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> identifier: a unique key or guid </li> 
 *      <li> namespace: the namespace of the identifier </li> 
 *      <li> authority: the isuer of the identifier </li> 
 *  </ul>
 *  Persisting a type reference means to persist the above identification 
 *  elements. In addition to these identifier components, A <code> Type 
 *  </code> mai also provide some additional metadata such as a name, 
 *  description and domain. </p>
 * 
 * @package org.osid.type
 */
interface osid_type_Type
{


    /**
     *  Gets the full display name of this <code> Type. </code> 
     *
     *  @return string the display name of this <code> Type </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDisplayName();


    /**
     *  Gets the shorter display label for this <code> Type </code> . Where a 
     *  display name of a <code> Type </code> might be <code> " </code> 
     *  Critical Logging Priority Type", the display label could be 
     *  "critical". 
     *
     *  @return string the display label for this <code> Type. </code> The 
     *          display name is returned when there is no metadata availavle 
     *          for this <code> Type. </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDisplayLabel();


    /**
     *  Gets a description of this <code> Type. </code> 
     *
     *  @return string the description of this <code> Type. </code> An empty 
     *          string is returned when no description is available for this 
     *          <code> Type. </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDescription();


    /**
     *  Gets the domain. The domain can provide an information label about ths 
     *  application space of this Type. 
     *
     *  @return string the domain of this <code> Type </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDomain();


    /**
     *  Gets the authority of this <code> Type. </code> The authority is a 
     *  string used to ensure the uniqueness of this <code> Type </code> when 
     *  using a non-federated identifier space. Generally, it is a domain name 
     *  identifying the party responsible for this <code> Type. </code> This 
     *  method is used to compare one <code> Type </code> to another. 
     *
     *  @return string the authority of this <code> Type </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAuthority();


    /**
     *  Gets the namespace of the identifier. This method is used to compare 
     *  one <code> Type </code> to another. 
     *
     *  @return string the authority of this <code> Type </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getIdentifierNamespace();


    /**
     *  Gets the identifier of this <code> Type. </code> This method is used 
     *  to compare one <code> Type </code> to another. 
     *
     *  @return string the identifier of this <code> Type </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getIdentifier();


    /**
     *  Determines if the given <code> Type </code> is equal to this one. Two 
     *  Types are equal if the authority, namespace and identifier components 
     *  are equal. The identifier is case sensitive while the authority 
     *  strings are not case sensitive. 
     *
     *  @param object osid_type_Type $type the <code> Type </code> to compare 
     *  @return boolean <code> true </code> if the given <code> Type </code> 
     *          is equal to this one, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isEqual(osid_type_Type $type);

}
