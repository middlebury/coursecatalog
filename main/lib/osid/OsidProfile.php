<?php

/**
 * osid_OsidProfile
 * 
 *     Specifies the OSID definition for osid_OsidProfile.
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
 *  <p>The <code> OsidProfile </code> defines the interoperability areas of an 
 *  OSID. An <code> OsidProfile </code> is implemented by an <code> 
 *  OsidManager. </code> The top level <code> OsidProfile </code> tests for 
 *  version compatibility. Each OSID extends this interface to include its own 
 *  interoperability definitions within its managers. </p>
 * 
 * @package org.osid
 */
interface osid_OsidProfile
{


    /**
     *  Gets an identifier for this service implementation. The identifier is 
     *  unique among services but multiple instantiations of the same service 
     *  use the same <code> Id. </code> This identifier is the same identifier 
     *  used in managing OSID installations. 
     *
     *  @return object osid_id_Id the <code> Id </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getId();


    /**
     *  Gets a display name for this service implementation. 
     *
     *  @return string a display name 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDisplayName();


    /**
     *  Gets a description of this service implementation. 
     *
     *  @return string a description 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDescription();


    /**
     *  Gets the version of this service implementation. 
     *
     *  @return string the version 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getVersion();


    /**
     *  Gets the date this service implementation was released. 
     *
     *  @return object osid_calendaring_DateTime the release date 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getReleaseDate();


    /**
     *  Gets the terms of usage with respect to this service implementation. 
     *
     *  @return string the license 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLicense();


    /**
     *  Gets the <code> Resource Id </code> representing the provider of this 
     *  service. 
     *
     *  @return object osid_id_Id the provider <code> Id </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getProviderId();


    /**
     *  Gets the provider of this service, expressed using the <code> Resource 
     *  </code> interface. 
     *
     *  @return object osid_resource_Resource the service provider resource 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  The <code> Resource </code> at minimum may only contain some 
     *          identifier along with a name and description, or a typed 
     *          interface extension can be used to reveal more information 
     *          such as contact information about the provider. 
     */
    public function getProvider();


    /**
     *  Gets a branding, such as an image or logo, expressed using the <code> 
     *  Asset </code> interface. 
     *
     *  @return object osid_repository_AssetList a list of assets 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getBranding();


    /**
     *  Test for support of an OSID version. 
     *
     *  @param string $version the version string to test 
     *  @return boolean <code> true </code> if this manager supports the given 
     *          version, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  An implementation may support multiple versions of an OSID. 
     */
    public function supportsOSIDVersion($version);


    /**
     *  Test for support of a journaling service. 
     *
     *  @return boolean <code> true </code> if this manager supports the 
     *          journaling, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsJournaling();

}
