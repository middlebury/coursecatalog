<?php

/**
 * osid_type_TypeProxyManager
 * 
 *     Specifies the OSID definition for osid_type_TypeProxyManager.
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

require_once(dirname(__FILE__)."/../OsidProxyManager.php");
require_once(dirname(__FILE__)."/TypeProfile.php");

/**
 *  <p>This manager provides access to the available sessions of the type 
 *  service. Methods in this manager support the passing of an <code> 
 *  Authentication </code> object for the purpose of proxy authentication. The 
 *  <code> TypeLookupSession </code> is used for looking up <code> Types 
 *  </code> and the <code> TypeAdminSession </code> is used for managing and 
 *  registering new Types. </p>
 * 
 * @package org.osid.type
 */
interface osid_type_TypeProxyManager
    extends osid_OsidProxyManager,
            osid_type_TypeProfile
{


    /**
     *  Gets the <code> OsidSession </code> associated with the <code> 
     *  TypeBrowser </code> service using the supplied <code> Authentication. 
     *  </code> 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_type_TypeLookupSession a <code> TypeLookupSession 
     *          </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException <code> unable to complete 
     *          request </code> 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsTypeLookup() 
     *          </code> is <code> false </code> 
     *  @throws osid_UnsupportedException the authentication service is not 
     *          supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTypeLookup() </code> is <code> true. </code> 
     */
    public function getTypeLookupSession(osid_authentication_Authentication $authentication);


    /**
     *  Gets the <code> OsidSession </code> associated with the <code> 
     *  TypeAdmin </code> service and supplied <code> Authentication. </code> 
     *
     *  @param object osid_authentication_Authentication $authentication proxy 
     *          authentication 
     *  @return object osid_type_TypeAdminSession the new <code> 
     *          TypeAdminSession </code> 
     *  @throws osid_NullArgumentException <code> authentication </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException <code> unable to complete 
     *          request </code> 
     *  @throws osid_PermissionDeniedException <code> authentication </code> 
     *          is invalid 
     *  @throws osid_UnimplementedException <code> supportsTypeAdmin() </code> 
     *          is <code> false </code> 
     *  @throws osid_UnsupportedException the authentication service is not 
     *          supported 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsTypeAdmin() </code> is <code> true. </code> 
     */
    public function getTypeAdminSession(osid_authentication_Authentication $authentication);

}
