<?php

/**
 * osid_OsidCatalog
 * 
 *     Specifies the OSID definition for osid_OsidCatalog.
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

require_once(dirname(__FILE__)."/OsidObject.php");

/**
 *  <p><code> OsidCatalog </code> is the top level interface for all OSID 
 *  catalog-like objects. A catalog relates to other OSID objects for the 
 *  purpose of organization and federation. An example catalog is a <code> 
 *  Repository </code> that relates to a collection of <code> Assets. </code> 
 *  </p> 
 *  
 *  <p> <code> Catalogs </code> allow for the retrieval of a provider identity 
 *  and branding. </p>
 * 
 * @package org.osid
 */
interface osid_OsidCatalog
    extends osid_OsidObject
{


    /**
     *  Gets the <code> Id </code> of the <code> Provider </code> of this 
     *  <code> Catalog. </code> 
     *
     *  @return object osid_id_Id the <code> Provider Id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getProviderId();


    /**
     *  Gets the <code> Resource </code> representing the provider of this 
     *  catalog. 
     *
     *  @return object osid_resource_Resource the provider 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getProvider();


    /**
     *  Gets a branding, such as an image or logo, expressed using the <code> 
     *  Asset </code> interface. 
     *
     *  @return object osid_repository_AssetList a list of assets 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getBranding();

}
