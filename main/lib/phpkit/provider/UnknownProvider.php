<?php

/**
 * osid_resource_Resource
 * 
 *     Specifies the OSID definition for osid_resource_Resource.
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
 * @package org.osid.resource
 */

/**
 *  <p>A <code> Resource </code> represents an arbitrary entity. Resources are 
 *  used to define an object to accompany an OSID <code> Id </code> used in 
 *  other OSIDs. A resource may be used to represent a meeting room in the 
 *  Scheduling OSID, or a student in the Course OSID. </p> 
 *  
 *  <p> A <code> Resource </code> may also represent a group or organization. 
 *  A provider may present such a group in an opaque manner through a single 
 *  resource definition, or the provider may expose the resource collection 
 *  for examination or manipulation. If such a resource collection is visible, 
 *  <code> isGroup() </code> is <code> true </code> and can be used in one of 
 *  the group sessions available in this OSID. </p>
 * 
 * @package org.osid.resource
 */
class phpkit_provider_UnknownProvider
	extends phpkit_AbstractOsidObject
    implements osid_resource_Resource
{
	
	/**
	 * Constructor
	 * 
	 * @return void
	 * @access public
	 * @since 10/28/08
	 */
	public function __construct () {
		$this->setId(new phpkit_id_URNInetId('urn:inet:osid.org:providers/unknown'));
		$this->setDisplayName('Unknown Provider');
		$this->setDisplayName('There is no record as to who this provider is.');
	}
	
    /**
     *  Tests if this resource is a group. A resource that is a group can be 
     *  used in the group sessions. 
     *
     *  @return <code> true </code> if this resource is a group, <code> false 
     *          </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isGroup() {
    	return false;
    }


    /**
     *  Gets the record corresponding to the given <code> Resource </code> 
     *  record <code> Type. </code> This method must be used to retrieve an 
     *  object implementing the requested record interface along with all of 
     *  its ancestor interfaces. The <code> resourceRecordType </code> may be 
     *  the <code> Type </code> returned in <code> getRecordTypes() </code> or 
     *  any of its parents in a <code> Type </code> hierarchy where <code> 
     *  hasRecordType(resourceRecordType) </code> is <code> true </code> . 
     *
     *  @param object osid_type_Type $resourceRecordType the resource record 
     *          type 
     *  @return the resource record 
     *  @throws osid_NullArgumentException <code> resourceRecordType </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure occurred 
     *  @throws osid_UnsupportedException <code> 
     *          hasRecordType(resourceRecordType) </code> is <code> false 
     *          </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getResourceRecord(osid_type_Type $resourceRecordType) {
    	throw new osid_UnsupportedException("resourceRecordType not supported");
    }

}
