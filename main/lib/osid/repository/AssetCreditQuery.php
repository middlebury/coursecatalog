<?php

/**
 * osid_repository_AssetCreditQuery
 * 
 *     Specifies the OSID definition for osid_repository_AssetCreditQuery.
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
 *  <p>This is the query interface for searching asset credits. Each method 
 *  specifies an <code> AND </code> term while multiple invocations of the 
 *  same method produce a nested <code> OR. </code> </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_AssetCreditQuery
{


    /**
     *  Sets the credit type for this query. 
     *
     *  @param object osid_type_Type $type asset credit type 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> type </code> is <code> null 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchType(osid_type_Type $type, $match);


    /**
     *  Matches a principal credit. 
     *
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchPrincipal($match);


    /**
     *  Sets the resource <code> Id </code> or alias <code> Id </code> for 
     *  this query. 
     *
     *  @param object osid_id_Id $resourceId the resource <code> Id </code> 
     *  @param boolean $match <code> true </code> for a positive match, <code> 
     *          false </code> for a negative match 
     *  @throws osid_NullArgumentException <code> providerId </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function matchResourceId(osid_id_Id $resourceId, $match);


    /**
     *  Tests if a <code> ResourceQuery </code> is availabe. Each query 
     *  retrieved is a nested <code> OR </code> term. 
     *
     *  @return boolean <code> true </code> if a resource query interface is 
     *          available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsResourceQuery();


    /**
     *  Gets the query interface for the resource or alias. Multiple 
     *  retrievals produce a nested <code> OR </code> term. 
     *
     *  @return object osid_resource_ResourceQuery the resource query 
     *  @throws osid_UnimplementedException <code> supportsResourceQuery() 
     *          </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsResourceQuery() </code> is <code> true. </code> 
     */
    public function getResourceQuery();

}
