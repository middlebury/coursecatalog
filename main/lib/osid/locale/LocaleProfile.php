<?php

/**
 * osid_locale_LocaleProfile
 * 
 *     Specifies the OSID definition for osid_locale_LocaleProfile.
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

require_once(dirname(__FILE__)."/../OsidProfile.php");

/**
 *  <p>The locale profile describes the interoperability of locale services. 
 *  </p>
 * 
 * @package org.osid.locale
 */
interface osid_locale_LocaleProfile
    extends osid_OsidProfile
{


    /**
     *  Tests if visible federation is supported. 
     *
     *  @return boolean <code> true </code> if visible federation is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsVisibleFederation();


    /**
     *  Tests if locale is supported. 
     *
     *  @return boolean <code> true </code> if locale is supported, <code> 
     *          false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLocale();


    /**
     *  Tests if unit conversion is supported. 
     *
     *  @return boolean <code> true </code> if unit conversion is supported, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsUnitConversion();


    /**
     *  Tests if currency conversion is supported. 
     *
     *  @return boolean <code> true </code> if currency conversion is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCurrencyConversion();


    /**
     *  Tests if calendar conversion is supported. 
     *
     *  @return boolean <code> true </code> if calendar conversion is 
     *          supported, <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCalendarConversion();


    /**
     *  Tests if a given locale type is supported. 
     *
     *  @param object osid_type_Type $localeType the type of locale 
     *  @return boolean <code> true </code> if the given locale type is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLocaleType(osid_type_Type $localeType);


    /**
     *  Tests if a given locale translation is supported. 
     *
     *  @param object osid_type_Type $sourceType the type of the source locale 
     *  @param object osid_type_Type $targetType the type of the target locale 
     *  @return boolean <code> true </code> if the given source and target 
     *          translation is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsLocaleConversionTypes(osid_type_Type $sourceType, 
                                                  osid_type_Type $targetType);


    /**
     *  Gets the list of target locale types for a given source language type. 
     *
     *  @param object osid_type_Type $sourceType the type of the source 
     *          language 
     *  @return object osid_type_TypeList the list of supported types for the 
     *          given source type 
     *  @throws osid_NullArgumentException null argument provided 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLocaleTypesForSource(osid_type_Type $sourceType);


    /**
     *  Gets all the locale types supported. 
     *
     *  @return object osid_type_TypeList the list of supported locale types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getLocaleTypes();


    /**
     *  Tests if a given unit type is supported. 
     *
     *  @param object osid_type_Type $unitType the type of unit 
     *  @return boolean <code> true </code> if the given unit type is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsUnitType(osid_type_Type $unitType);


    /**
     *  Tests if a given measure conversion is supported. 
     *
     *  @param object osid_type_Type $sourceType the type of the source 
     *          measure 
     *  @param object osid_type_Type $targetType the type of the target 
     *          measure 
     *  @return boolean <code> true </code> if the given source and target 
     *          conversion is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsUnitTypesForConversion(osid_type_Type $sourceType, 
                                                   osid_type_Type $targetType);


    /**
     *  Gets the list of target measure types for a given source measure type. 
     *
     *  @param object osid_type_Type $unitType the type of the source measure 
     *  @return object osid_type_TypeList the list of supported measure types 
     *  @throws osid_NullArgumentException null argument provided 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getUnitTargetTypesForConversion(osid_type_Type $unitType);


    /**
     *  Gets all the unit types supported. 
     *
     *  @return object osid_type_TypeList the list of supported unit types 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getUnitTypes();


    /**
     *  Tests if the given currency type is supported. 
     *
     *  @param object osid_type_Type $currencyType the type of the source 
     *          currency 
     *  @return boolean <code> true </code> if the given currency type is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCurrencyType(osid_type_Type $currencyType);


    /**
     *  Tests if a given currency conversion is supported. 
     *
     *  @param object osid_type_Type $sourceType the type of the source 
     *          currency 
     *  @param object osid_type_Type $targetType the type of the target 
     *          currency 
     *  @return boolean <code> true </code> if the given source and target 
     *          conversion is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCurrencyTypesForConversion(osid_type_Type $sourceType, 
                                                       osid_type_Type $targetType);


    /**
     *  Gets the list of target currency types for a given source measure 
     *  type. 
     *
     *  @param object osid_type_Type $currencyType the type of the source 
     *          currency 
     *  @return object osid_type_TypeList the list of supported currency types 
     *  @throws osid_NullArgumentException null argument provided 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCurrencyTargetTypesForConversion(osid_type_Type $currencyType);


    /**
     *  Gets the list of target measure types for a given source measure type. 
     *
     *  @param object osid_type_Type $currencyType the type of the source 
     *          currency 
     *  @return object osid_type_TypeList the list of supported currency types 
     *  @throws osid_NullArgumentException null argument provided 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCurrencyTypes(osid_type_Type $currencyType);


    /**
     *  Tests if the given calendar type is supported. 
     *
     *  @param object osid_type_Type $calendarType the type of the source 
     *          calendar 
     *  @return boolean <code> true </code> if the given calendar type is 
     *          supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCalendarType(osid_type_Type $calendarType);


    /**
     *  Tests if a given calendar conversion is supported. 
     *
     *  @param object osid_type_Type $sourceType the type of the source 
     *          calendar 
     *  @param object osid_type_Type $targetType the type of the target 
     *          calendar 
     *  @return boolean <code> true </code> if the given source and target 
     *          conversion is supported, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function supportsCalendarTypesForConversion(osid_type_Type $sourceType, 
                                                       osid_type_Type $targetType);


    /**
     *  Gets the list of target calerndar types for a given source measure 
     *  type. 
     *
     *  @param object osid_type_Type $calendarType the type of the source 
     *          calendar 
     *  @return object osid_type_TypeList the list of supported calendar types 
     *  @throws osid_NullArgumentException null argument provided 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCalendarTargetTypesForConversion(osid_type_Type $calendarType);


    /**
     *  Gets the list of target measure types for a given source measure type. 
     *
     *  @param object osid_type_Type $currencyType the type of the source 
     *          measure 
     *  @return object osid_type_TypeList the list of supported currency types 
     *  @throws osid_NullArgumentException null argument provided 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCalendarTypes(osid_type_Type $currencyType);

}
