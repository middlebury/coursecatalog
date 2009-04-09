<?php

/**
 * osid_repository_AssetTemporalAssignmentSession
 * 
 *     Specifies the OSID definition for osid_repository_AssetTemporalAssignmentSession.
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

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session defines methods to manage temporal coverage of an asset. 
 *  </p>
 * 
 * @package org.osid.repository
 */
interface osid_repository_AssetTemporalAssignmentSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can manage temporal lookups. A return of true does 
     *  not guarantee successful authorization. A return of false indicates 
     *  that it is known all methods in this session will result in a <code> 
     *  PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer lookup operations. 
     *
     *  @return boolean <code> false </code> if temporal management is not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canManageTemporalCoverage();


    /**
     *  Adds a temporal coverage to this asset expressed as a range between 
     *  two date/times. 
     *
     *  @param object osid_id_Id $assetId <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @param object osid_calendaring_DateTime $begin start date/time 
     *  @param object osid_calendaring_DateTime $end end date/time 
     *  @throws osid_AlreadyExistsException interval already exists 
     *  @throws osid_InvalidArgumentException <code> begin </code> is greater 
     *          than <code> end </code> 
     *  @throws osid_NotFoundException <code> assetId </code> not <code> found 
     *          </code> 
     *  @throws osid_NullArgumentException <code> assetId, begin </code> or 
     *          <code> end </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization fauilure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function addTemporalCoverage(osid_id_Id $assetId, 
                                        osid_calendaring_DateTime $begin, 
                                        osid_calendaring_DateTime $end);


    /**
     *  Removes a temporal range from an asset. 
     *
     *  @param object osid_id_Id $assetId <code> Id </code> of the <code> 
     *          Asset </code> 
     *  @param object osid_calendaring_DateTime $begin start date/time 
     *  @param object osid_calendaring_DateTime $end end date/time 
     *  @throws osid_NotFoundException <code> assetId </code> with <code> 
     *          begin </code> and <code> end </code> not <code> found </code> 
     *  @throws osid_NullArgumentException <code> assetId, begin </code> or 
     *          <code> end </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization fauilure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function removeTemporalCoverage(osid_id_Id $assetId, 
                                           osid_calendaring_DateTime $begin, 
                                           osid_calendaring_DateTime $end);

}
