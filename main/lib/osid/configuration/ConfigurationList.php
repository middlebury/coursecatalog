<?php

/**
 * osid_configuration_ConfigurationList
 * 
 *     Specifies the OSID definition for osid_configuration_ConfigurationList.
 * 
 * Copyright (C) 2008 Massachusetts Institute of Technology. All Rights 
 * Reserved. 
 * 
 *     This Work is being provided by the copyright holder(s) subject to the 
 *     following license. By obtaining, using and/or copying this Work, you 
 *     agree that you have read, understand, and will comply with the 
 *     following terms and conditions. 
 *     
 *     This Work and the information contained herein is provided on an ""AS 
 *     IS"" basis. The Massachusetts Institute of Technology, the Open 
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
 * @package org.osid.configuration
 */

require_once(dirname(__FILE__)."/../OsidList.php");

/**
 *  <p>Like all <code> OsidLists, </code> <code> ConfigurationList </code> 
 *  provides a means for accessing <code> Configuration </code> elements 
 *  sequentially either one at a time or many at a time. Examples: </p> 
 *  
 *  <p> 
 *  <pre>
 *       while (cl.hasNext()) {
 *            Configuration config = cl.getNextConfiguration();
 *       }
 *       
 *       
 *                   
 *       
 *  </pre>
 *  or 
 *  <pre>
 *       while (cl.hasNext()) {
 *            Configuration[] configs = cl.getNextConfigurations(cl.available());
 *       }
 *                   
 *       
 *  </pre>
 *  </p>
 * 
 * @package org.osid.configuration
 */
interface osid_configuration_ConfigurationList
    extends osid_OsidList
{


    /**
     *  Gets the next <code> Configuration </code> in this list. 
     *
     *  @return object osid_configuration_Configuration the next <code> 
     *          Configuration </code> in this list. The <code> hasNext() 
     *          </code> method should be used to test that a next <code> 
     *          Configuration </code> is available before calling this method. 
     *  @throws osid_IllegalStateException no more elements available in this 
     *          list or this list has been closed 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNextConfiguration();


    /**
     *  Gets the next set of <code> Configuration </code> objects in this lis 
     *  which must be less than or equal to whst is returned from <code> 
     *  available(). </code> 
     *
     *  @param integer $n the number of <code> Configuration </code> elements 
     *          requested which should be less than or equal to <code> 
     *          available() </code> 
     *  @return array of osid_configuration_Configuration objects  an array of 
     *          <code> Configuration </code> elements. <code> </code> The 
     *          length of the array is less than or equal to the number 
     *          specified. 
     *  @throws osid_IllegalStateException no more elements available in this 
     *          list or this list has been closed 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNextConfigurations($n);

}
