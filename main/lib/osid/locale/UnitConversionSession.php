<?php

/**
 * osid_locale_UnitConversionSession
 * 
 *     Specifies the OSID definition for osid_locale_UnitConversionSession.
 * 
 * Copyright (C) 2002-2008 Massachusetts Institute of Technology. All Rights 
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
 * @package org.osid.locale
 */

require_once(dirname(__FILE__)."/../OsidSession.php");

/**
 *  <p>This session defines methods to convert units across measurement 
 *  systems. </p>
 * 
 * @package org.osid.locale
 */
interface osid_locale_UnitConversionSession
    extends osid_OsidSession
{


    /**
     *  Tests if this user can perform unit conversions. A return of true does 
     *  not guarantee successful authorization. A return of false indicates 
     *  that it is known all methods in this session will result in a <code> 
     *  PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer lookup operations. 
     *
     *  @return boolean <code> false </code> if conversion methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canConvertUnits();


    /**
     *  Convert units. 
     *
     *  @param float $sourceUnit the measure to convert 
     *  @param object osid_type_Type $sourceUnitType the type of measure 
     *          specified 
     *  @param object osid_type_Type $targetUnitType the type of converted 
     *          measure 
     *  @return float resulting measure 
     *  @throws osid_NullArgumentException <code> null </code> argument 
     *          provided 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_UnsupportedException <code> 
     *          LocaleManager.supportsUnitTypesForConversion(measureType, 
     *          conversionType) </code> is false 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function convertUnits($sourceUnit, osid_type_Type $sourceUnitType, 
                                 osid_type_Type $targetUnitType);

}
