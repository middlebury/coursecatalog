<?php

/**
 * osid_dictionary_DictionaryReceiver
 * 
 *     Specifies the OSID definition for osid_dictionary_DictionaryReceiver.
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
 * @package org.osid.dictionary
 */

require_once(dirname(__FILE__)."/../OsidReceiver.php");

/**
 *  <p>The dictionary receiver is the consumer supplied interface for 
 *  receiving notifications pertaining to new, updated or deleted <code> 
 *  Dictionary </code> objects. </p>
 * 
 * @package org.osid.dictionary
 */
interface osid_dictionary_DictionaryReceiver
    extends osid_OsidReceiver
{


    /**
     *  The callback for notifications of new dictionaries. 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          new <code> Dictionary </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function newDictionary(osid_id_Id $dictionaryId);


    /**
     *  The callback for notifications of new ancestors of a dictionary. 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          registered <code> Dictionary </code> 
     *  @param object osid_id_Id $ancestorId the <code> Id </code> of the new 
     *          ancestor dictionary 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function newAncestorDictionary(osid_id_Id $dictionaryId, 
                                          osid_id_Id $ancestorId);


    /**
     *  The callback for notifications of new descendant of a dictionary. 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          registered <code> Dictionary </code> 
     *  @param object osid_id_Id $descendantId the <code> Id </code> of the 
     *          new descendant dictionary 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function newDescendantDictionary(osid_id_Id $dictionaryId, 
                                            osid_id_Id $descendantId);


    /**
     *  The callback for notification of updated dictionaries. 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          updated <code> Dictionary </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function changedDictionary(osid_id_Id $dictionaryId);


    /**
     *  the callback for notification of deleted dictionaries. 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          deleted <code> Dictionary </code> 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deletedDictionary(osid_id_Id $dictionaryId);


    /**
     *  The callback for notifications of deleted ancestors of a dictionary. 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          registered <code> Dictionary </code> 
     *  @param object osid_id_Id $ancestorId the <code> Id </code> of the 
     *          removed ancestor dictionary 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deletedAncestorDictionary(osid_id_Id $dictionaryId, 
                                              osid_id_Id $ancestorId);


    /**
     *  The callback for notifications of deleted descendanta of a dictionary. 
     *
     *  @param object osid_id_Id $dictionaryId the <code> Id </code> of the 
     *          registered <code> Dictionary </code> 
     *  @param object osid_id_Id $descendantId the <code> Id </code> of the 
     *          deleted descendant dictionary 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function deletedDescendantDictionary(osid_id_Id $dictionaryId, 
                                                osid_id_Id $descendantId);

}
