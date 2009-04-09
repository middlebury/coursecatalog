<?php

/**
 * osid_OsidCatalogSearchOrder
 * 
 *     Specifies the OSID definition for osid_OsidCatalogSearchOrder.
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

require_once(dirname(__FILE__)."/OsidSearchOrder.php");

/**
 *  <p>An interface for specifying the ordering of catalog search results. 
 *  </p>
 * 
 * @package org.osid
 */
interface osid_OsidCatalogSearchOrder
    extends osid_OsidSearchOrder
{


    /**
     *  Specifies a preference for ordering the results by provider. The 
     *  element of the provider to order is not specified but may be managed 
     *  through the provider ordering interface. 
     *
     *  @compliance mandatory This method must be implemented. 
     */
    public function orderByProvider();


    /**
     *  Tests if a <code> ProviderSearchOrder </code> interface is available. 
     *
     *  @return boolean <code> true </code> if a provider search order 
     *          interface is available, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsProviderSearchOrder();


    /**
     *  Gets the search order interface for a provider 
     *
     *  @return object osid_resource_ResourceSearchOrder the provider search 
     *          order interface 
     *  @throws osid_UnimplementedException <code> 
     *          supportsProviderSearchOrder() </code> is <code> false </code> 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsProviderSearchOrder() </code> is <code> true. 
     *              </code> 
     */
    public function getProviderSearchOrder();

}
