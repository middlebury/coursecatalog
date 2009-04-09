<?php

/**
 * osid_logging_LogEntry
 * 
 *     Specifies the OSID definition for osid_logging_LogEntry.
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
 * @package org.osid.logging
 */


/**
 *  <p>A log entry consists of a time, an agent, and some content. </p>
 * 
 * @package org.osid.logging
 */
interface osid_logging_LogEntry
{


    /**
     *  Gets the <code> Id </code> associated with this instance of this log 
     *  entry. Persisting any reference to this object is done by persisting 
     *  the <code> Id </code> returned from this method. The <code> Id </code> 
     *  returned may be different than the <code> Id </code> used to query 
     *  this object. In this case, the new <code> Id </code> should be 
     *  preferred over the old one for future queries. 
     *
     *  @return object osid_id_Id the <code> Id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getId();


    /**
     *  Gets the priority level og this entry. 
     *
     *  @return object osid_type_Type the priority level 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPriority();


    /**
     *  Gets the time this entry was logged. 
     *
     *  @return object osid_calendaring_DateTime the time stamp of this entry 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTimestamp();


    /**
     *  Gets the agent <code> Id </code> who created this entry. 
     *
     *  @return object osid_id_Id the agent <code> id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgentId();


    /**
     *  Gets the <code> Agent </code> who created this entry. 
     *
     *  @return object osid_authentication_Agent the <code> Agent </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAgent();


    /**
     *  Gets the type implemented by the entry ocntent. The <code> Type 
     *  </code> explicitly indicates the specification of the object and 
     *  implicitly may define an object family. The interface <code> Type 
     *  </code> returned may be a child in a type hierarchy. Interoperability 
     *  with the typed interface to the content should be performed through 
     *  <code> implementsContentType(). </code> 
     *
     *  @return object osid_type_Type the type implemeneted in the content 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getContentType();


    /**
     *  Tests if the content supports the given <code> Type. </code> The given 
     *  type may be supported by the content through interface/type 
     *  inheritence. This method should be checked before retrieving the typed 
     *  interface extension. 
     *
     *  @param object osid_type_Type $contentType a type 
     *  @return boolean <code> true </code> if the content implements the 
     *          given <code> Type, </code> <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> contentType </code> is 
     *          <code> null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasContentType(osid_type_Type $contentType);


    /**
     *  Gets the content of this log entry as specified by the interface type. 
     *
     *  @param object osid_type_Type $contentType the type of the object to 
     *          retrieve 
     *  @return object the log entry content 
     *  @throws osid_NullArgumentException <code> contentType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> hasContentType(contentype) 
     *          </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getContent(osid_type_Type $contentType);

}
