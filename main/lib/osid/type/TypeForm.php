<?php

/**
 * osid_type_TypeForm
 * 
 *     Specifies the OSID definition for osid_type_TypeForm.
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
 *  <p>This form provides a means of updating various fields in the <code> 
 *  Type. </code> Note that the domain, authority and identifier are part of 
 *  the <code> Type </code> identification, and as such not modifiable. </p>
 * 
 * @package org.osid.type
 */
interface osid_type_TypeForm
{


    /**
     *  Gets the metadata for the display name. 
     *
     *  @return object osid_Metadata metadata for the display name 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDisplayNameMetadata();


    /**
     *  Sets a display name. A display name is required and if not set will be 
     *  set by the provider. 
     *
     *  @param string $displayName the new display name 
     *  @throws osid_InvalidArgumentException <code> displayName </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> displayName </code> cannot be 
     *          modified 
     *  @throws osid_NullArgumentException <code> displayName </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setDisplayName($displayName);


    /**
     *  Gets the metadata for the display label. 
     *
     *  @return object osid_Metadata metadata for the display label 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDisplayLabelMetadata();


    /**
     *  Seta a display label. 
     *
     *  @param string $displayLabel the new display label 
     *  @throws osid_InvalidArgumentException <code> displayLabel </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> displayLabel </code> cannot be 
     *          modified 
     *  @throws osid_NullArgumentException <code> displayLabel </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setDisplayLabel($displayLabel);


    /**
     *  Gets the metadata for the description. 
     *
     *  @return object osid_Metadata metadata for the description 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDescriptionMetadata();


    /**
     *  Sets a description. 
     *
     *  @param string $description the new description 
     *  @throws osid_InvalidArgumentException <code> description </code> is 
     *          invalid 
     *  @throws osid_NoAccessException <code> description </code> cannot be 
     *          modified 
     *  @throws osid_NullArgumentException <code> description </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setDescription($description);


    /**
     *  Clears the description. 
     *
     *  @throws osid_NoAccessException <code> description </code> cannot be 
     *          modified 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearDescription();


    /**
     *  Gets the metadata for the domain. 
     *
     *  @return object osid_Metadata metadata for the domain 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDomainMetadata();


    /**
     *  Sets a domain. 
     *
     *  @param string $domain the new domain 
     *  @throws osid_InvalidArgumentException <code> domain </code> is invalid 
     *  @throws osid_NoAccessException <code> domain </code> cannot be 
     *          modified 
     *  @throws osid_NullArgumentException <code> domain </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function setDomain($domain);


    /**
     *  Clears the domain. 
     *
     *  @throws osid_NoAccessException <code> domain </code> cannot be 
     *          modified 
     *  @compliance mandatory This method must be implemented. 
     */
    public function clearDomain();

}
