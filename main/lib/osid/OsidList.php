<?php

/**
 * osid_OsidList
 * 
 *     Specifies the OSID definition for osid_OsidList.
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
 *  <p><code> OsidList </code> is the top-level interface for all OSID lists. 
 *  An OSID list provides sequential access, one at a time or many at a time, 
 *  access to a set of elements. These elements are not required to be 
 *  OsidObjects but generally are. The element retrieval methods are defined 
 *  in the sub-interface of <code> OsidList </code> where the appropriate 
 *  return type is defined. </p> 
 *  
 *  <p> Osid lists are a once pass through iteration of elements. The size of 
 *  the object set and the means in which the element set is generated or 
 *  stored is not known. Assumptions based on the length of the element set by 
 *  copying the entire contents of the list into a fixed buffer should be done 
 *  with caution a awareness that an implementation may return a number of 
 *  elements ranging from zero to infinity. </p> 
 *  
 *  <p> Lists are returned by methods when multiple return values are 
 *  possible. There is no guarantee that successive calls to the same method 
 *  will return the same set of elements in a list. Unless an order is 
 *  specified in an interface definition, the order of the elements is not 
 *  known. </p>
 * 
 * @package org.osid
 */
interface osid_OsidList
{


    /**
     *  Tests if there are more elements in this list. 
     *
     *  @return boolean <code> true </code> if more elements are available in 
     *          this list, <code> false </code> if the end of the list has 
     *          been reached 
     *  @throws osid_IllegalStateException this list has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  Any errors that may result from accesing the underlying set of 
     *          elements are to be deferred until the consumer attempts 
     *          retrieval in which case the provider must return <code> true 
     *          </code> for this method. 
     */
    public function hasNext();


    /**
     *  Gets the number of elements available for retrieval. The number 
     *  returned by this method may be less than or equal to the total number 
     *  of elements in this list. To determine if the end of the list has been 
     *  reached, the method <code> hasNext() </code> should be used. This 
     *  method conveys what is known about the number of remaining elements at 
     *  a point in time and can be used to determine a minimum size of the 
     *  remaining elements, if known. A valid return is zero even if <code> 
     *  hasNext() </code> is true. 
     *  <br/><br/>
     *  This method does not imply asynchronous usage. All OSID methods may 
     *  block. 
     *
     *  @return integer the number of elements available for retrieval 
     *  @throws osid_IllegalStateException this list has been closed 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  Any errors that may result from accesing the underlying set of 
     *          elements are to be deferred until the consumer attempts 
     *          retrieval in which case the provider must return a positive 
     *          integer for this method so the consumer can continue execution 
     *          to receive the error. In all other circumstances, the provider 
     *          must not return a number greater than the number of elements 
     *          known since this number will be fed as a parameter to the bulk 
     *          retrieval method. 
     */
    public function available();


    /**
     *  Skip the specified number of elements in the list. If the number 
     *  skipped is greater than the number of elements in the list, hasNext() 
     *  becomes false and available() returns zero as there are no more 
     *  elements to retrieve. 
     *
     *  @param integer $n the number of elements to skip 
     *  @throws osid_NullArgumentException null argument provided 
     *  @throws osid_IllegalStateException this list has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function skip($n);


    /**
     *  Closes down this <code>osid.OsidList</code>
     */

    public function done();

}
