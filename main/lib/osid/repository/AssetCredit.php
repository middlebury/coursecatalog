<?php

/**
 * osid_repository_AssetCredit
 * 
 *     Specifies the OSID definition for osid_repository_AssetCredit.
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
 * @package org.osid.repository
 */


/**
 *  <p>An <code> AssetCredit </code> is the relationship between a Resource 
 *  and an <code> Asset </code> with respect to its production. The <code> 
 *  Resource </code> is generally a person and the <code> Type </code> may 
 *  indicate ""author"", ""composer"", ""director"", ""grip"", etc. </p> 
 *  
 *  <p> An alias is used when the primary resource worked or appeared in the 
 *  asset under another name. An alias for Samuel Clemens may indicate Mark 
 *  Twain, or an alias for Sean Connery may indicate James Bond. </p> 
 *  
 *  <p> Two credits are unique if there type, resource or alias differ. The 
 *  sequence number exists for the purpose of specifying an order to the 
 *  credits. The sequence may not be continuous and the provider may change 
 *  the sequence numbers at any time. </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_AssetCredit
{


    /**
     *  Gets the sequence of this credit. 
     *
     *  @return integer the sequence of this credit 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSequence();


    /**
     *  Gets the <code> Type </code> of this credit. 
     *
     *  @return object osid_type_Type the type of this credit 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getType();


    /**
     *  Tests if this is a principal credit. 
     *
     *  @return boolean <code> true </code> if this is a principal credit, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isPrincipal();


    /**
     *  Gets the <code> Resource </code> <code> Id. </code> 
     *
     *  @return object osid_id_Id the resource <code> Id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourceId();


    /**
     *  Gets the <code> Resource. </code> 
     *
     *  @return object osid_resource_Resource the resource 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResource();


    /**
     *  Gets the <code> Resource </code> <code> Id </code> of the alias. If 
     *  there is no alias defined for this credit, then <code> getResourceId() 
     *  </code> is returned. 
     *
     *  @return object osid_id_Id the resource <code> Id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAliasId();


    /**
     *  Gets the <code> Resource </code> of the alias. 
     *
     *  @return object osid_resource_Resource the resource 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAlias();

}
