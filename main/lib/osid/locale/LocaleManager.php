<?php

/**
 * osid_locale_LocaleManager
 * 
 *     Specifies the OSID definition for osid_locale_LocaleManager.
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

require_once(dirname(__FILE__)."/../OsidManager.php");
require_once(dirname(__FILE__)."/LocaleProfile.php");

/**
 *  <p>The locale manager provides access to locale sessions and provides 
 *  interoperability tests for various aspects of this service. The sessions 
 *  included in this manager are: </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> <code> LocaleSession: </code> a session translate strings </li> 
 *      <li> <code> LocaleAdminSession: a </code> session to update the string 
 *      translations for a locale </li> 
 *      <li> <code> UnitConversionSession: </code> a session to convert 
 *      measurement units <code> </code> </li> 
 *      <li> <code> CurrencyConversionSession: </code> a session to convert 
 *      currency </li> 
 *      <li> <code> CalendarConversionSession: </code> a session to convert 
 *      dates across calendars </li> 
 *  </ul>
 *  </p>
 * 
 * @package org.osid.locale
 */
interface osid_locale_LocaleManager
    extends osid_OsidManager,
            osid_locale_LocaleProfile
{


    /**
     *  Gets an <code> OsidSession </code> associated with the <code> Locale 
     *  </code> service. 
     *
     *  @return object osid_locale_LocaleSession a <code> LocaleSession 
     *          </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsLocale() </code> is 
     *          <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLocale() </code> is <code> true. </code> 
     */
    public function getLocaleSession();


    /**
     *  Gets an <code> OsidSession </code> associated with the <code> 
     *  LocaleLookup </code> service and the given locale types. 
     *
     *  @param object osid_type_Type $sourceType the type of the source 
     *          language 
     *  @param object osid_type_Type $targetType the type of the target 
     *          language 
     *  @return object osid_locale_LocaleSession a <code> LocaleSession 
     *          </code> 
     *  @throws osid_NullArgumentException <code> sourceType </code> or <code> 
     *          targetType </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsLocale() </code> or 
     *          <code> supportsVisibleFederation() </code> is <code> false 
     *          </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsLocaleTypesForConversion(sourceType, targetType) 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLocale() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getLocaleSessionForLocales(osid_type_Type $sourceType, 
                                               osid_type_Type $targetType);


    /**
     *  Gets a locale administration service for updating a locale dictionary. 
     *
     *  @return object osid_locale_LocaleAdminSession a <code> 
     *          LocaleAdminSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsLocaleAdmin() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLocaleAdmin() </code> is <code> true. </code> 
     */
    public function getLocaleAdminSession();


    /**
     *  Gets a locale administration service for updating a locale dictionary 
     *  using the given locale types. 
     *
     *  @param object osid_type_Type $sourceType the type of the source 
     *          language 
     *  @param object osid_type_Type $targetType the type of the target 
     *          language 
     *  @return object osid_locale_LocaleAdminSession a <code> 
     *          LocaleAdminSession </code> 
     *  @throws osid_NullArgumentException <code> sourceType </code> or <code> 
     *          targetType </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsLocaleAdmin() 
     *          </code> or <code> supportsVisibleFederation() </code> is 
     *          <code> false </code> 
     *  @throws osid_UnsupportedException <code> 
     *          supportsLocaleTypesForConversion(sourceType, targetType) 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsLocaleAdmin() </code> and <code> 
     *              supportsVisibleFederation() </code> are <code> true. 
     *              </code> 
     */
    public function getLocaleAdminSessionForLocales(osid_type_Type $sourceType, 
                                                    osid_type_Type $targetType);


    /**
     *  Gets a unit conversion session. 
     *
     *  @return object osid_locale_UnitConversionSession a <code> 
     *          UnitConversionSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> supportsUnitConversion() 
     *          </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsUnitConversion() </code> is <code> true. </code> 
     */
    public function getUnitConversionSession();


    /**
     *  Gets a currency conversion session. 
     *
     *  @return object osid_locale_CurrencyConversionSession a <code> 
     *          CurrencyConversionSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCurrencyConversion() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCurrencyConversion() </code> is <code> true. 
     *              </code> 
     */
    public function getCurrencyConversionSession();


    /**
     *  Gets a calendar conversion session. 
     *
     *  @return object osid_locale_CalendarConversionSession a <code> 
     *          CalendarConversionSession </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_UnimplementedException <code> 
     *          supportsCalendarConversion() </code> is <code> false </code> 
     *  @throws osid_IllegalStateException this manager has been shut down 
     *  @compliance optional This method must be implemented if <code> 
     *              supportsCalendarConversion() </code> is <code> true. 
     *              </code> 
     */
    public function getCalendarConversionSession();

}
