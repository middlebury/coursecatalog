<?php

/**
 * osid_SpatialUnit
 * 
 *     Specifies the OSID definition for osid_SpatialUnit.
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
 *  <p>The <code> SpatialUnit </code> interface defines a point or region in 
 *  space. The domain indicates the spatial corrdinate system that maps to an 
 *  interface specifcation of its type. </p>
 * 
 * @package org.osid
 */
interface osid_SpatialUnit
{


    /**
     *  Tests if the diven domain is available for this spatial unit. 
     *
     *  @param object osid_type_Type $domainType the domain type 
     *  @return boolean <code> true </code> if the given domain type is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function implementsDomainType(osid_type_Type $domainType);


    /**
     *  Gets the domain type for this spatial unit. <code> supportsDomain() 
     *  </code> should be used to test for interoperability. 
     *
     *  @return object osid_type_Type the domain type 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getDomainType();


    /**
     *  Tests if the given spatial unit is completely included in this one. 
     *
     *  @param object osid_SpatialUnit $spatialUnit the spatial unit to 
     *          compare 
     *  @return boolean <code> true </code> if the given spatial unit is 
     *          included in this one, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> spatialUnit </code> is 
     *          <code> null </code> 
     *  @throws osid_UnsupportedException <code> spatialUnit </code> is not 
     *          supported 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isInclusive(osid_SpatialUnit $spatialUnit);


    /**
     *  Tests if the given spatial unit is completely exclusive in this one. 
     *
     *  @param object osid_SpatialUnit $spatialUnit the spatial unit to 
     *          compare 
     *  @return boolean <code> true </code> if the given spatial unit is 
     *          exclsuive of this one, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> spatialUnit </code> is 
     *          <code> null </code> 
     *  @throws osid_UnsupportedException <code> spatialUnit </code> is not 
     *          supported 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isExclusive(osid_SpatialUnit $spatialUnit);


    /**
     *  Tests if the given spatial unit is equal to this one. 
     *
     *  @param object osid_SpatialUnit $spatialUnit the spatial unit to 
     *          compare 
     *  @return boolean <code> true </code> if the given spatial unit is equal 
     *          to this one, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> spatialUnit </code> is 
     *          <code> null </code> 
     *  @throws osid_UnsupportedException <code> spatialUnit </code> is not 
     *          supported 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isEqual(osid_SpatialUnit $spatialUnit);


    /**
     *  Gets the typed interface corresponding to this <code> Spatial </code> 
     *  domain. 
     *
     *  @param object osid_type_Type $domainType the domain type 
     *  @return object osid_SpatialUnit the spatial domain with the typed 
     *          interface 
     *  @throws osid_NullArgumentException <code> domainType </code> is <code> 
     *          null </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsDomainType(domainType) </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSpatialDomain(osid_type_Type $domainType);


    /**
     *  Gets the typed interface corresponding to this <code> SpatialUnit 
     *  </code> <code> Type. </code> 
     *
     *  @param object osid_type_Type $domainType the spatial unit domain type 
     *  @return object osid_SpatialUnit the spatial unit with the typed 
     *          interface 
     *  @throws osid_NullArgumentException <code> domainType </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          implementsDomainType(domainType) </code> is <code> false 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getSpatialUnitExtension(osid_type_Type $domainType);

}
