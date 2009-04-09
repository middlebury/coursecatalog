<?php

/**
 * osid_configuration_Configuration
 * 
 *     Specifies the OSID definition for osid_configuration_Configuration.
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

require_once(dirname(__FILE__)."/../OsidCatalog.php");

/**
 *  <p><code> Configuration </code> represents a configuration object. It 
 *  contains a name, description and a set of properties that describe a 
 *  configuration data set. </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_Configuration
    extends osid_OsidCatalog
{


    /**
     *  Gets the <code> Configuration </code> interface corresponding to the 
     *  given <code> Configuration </code> interface <code> Type. </code> A 
     *  <code> Configuration </code> returned from another method is only 
     *  required to implement the root <code> Configuration </code> interface 
     *  and this method must be used to retrieve a configuration object 
     *  implementing the requested interface along with all of its ancestor 
     *  interfaces, including the core <code> Configuration </code> interface. 
     *  The <code> configurationInterfaceType </code> may be the <code> Type 
     *  </code> returned by <code> getInterfaceType() </code> or any parent of 
     *  <code> getInterfaceType() </code> in the <code> Type </code> hierarchy 
     *  where <code> implementsInterfaceType(configurationInterfaceType) 
     *  </code> is <code> true </code> . 
     *
     *  @param object osid_type_Type $rconfigurationInterfaceType the type of 
     *          the interface to retrieve 
     *  @return object osid_configuration_Configuration the configuration with 
     *          the specified interface 
     *  @throws osid_NullArgumentException <code> configurationInterfaceType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          implementsInterfaceType(configurationInterfaceType) </code> is 
     *          <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getConfigurationInterface(osid_type_Type $rconfigurationInterfaceType);

}
