<?php

/**
 * osid_id_Id
 * 
 *     Specifies the OSID definition for osid_id_Id.
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
 * @package org.osid.id
 */


/**
 *  <p>Id represents an identifier object. Ids are designated by the following 
 *  elements: </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> <code> identifier: </code> a unique key or guid </li> 
 *      <li> <code> namespace: </code> the namespace of the identifier </li> 
 *      <li> <code> authority: </code> the issuer of the identifier </li> 
 *  </ul>
 *  Two Ids are equal if their namespace, identifier and authority strings are 
 *  equal. Only the identifier is case-sensitive. Persisting an <code> Id 
 *  </code> means persisting the above components. </p>
 * 
 * @package org.osid.id
 */
class phpkit_id_URNInetId
	extends phpkit_id_Id
{

	/**
	 * Construct a new <code>Id</code>
	 * 
	 * @param string $authority
	 * @param string $namespace
	 * @param string $identifier
	 * @return void
	 * @access public
	 * @since 10/28/08
	 */
	public function __construct ($urn) {
		if (!preg_match('/^([^:]+):([^:]+):([^:]+):(.+)$/i', $urn, $m))
			throw new osid_InvalidArgumentException("error parsing urn '$urn'.");
		
		if ($m[1] != 'urn' || !strlen($m[3]) || !strlen($m[4]))
			throw new osid_InvalidArgumentException("'$urn' is not a valid inet URN");
			
		if ($m[2] != 'inet')
			throw new osid_InvalidArgumentException("nid for urn is not inet namespace");
		
		$authority = $m[3];
		$namespace = 'urn';
		$identifier = $m[4];
		
		parent::__construct($authority, $namespace, $identifier);
	}
	
	/**
	 * Convert this Id to a string for debugging purposes.
	 * 
	 * @return string
	 * @access public
	 * @since 4/13/09
	 */
	public function __toString () {
		return self::getInetURNString($this);
	}
	
	/**
	 * Answer an Id as a Inet URN string
	 * 
	 * @param object osid_id_Id $id
	 * @return string
	 * @throws osid_OperationFailedException The Id Passed does not have the urn namespace.
	 * @access public
	 * @since 10/30/08
	 * @static
	 */
	public static function getInetURNString (osid_id_Id $id) {
		if (strtolower($id->getIdentifierNamespace()) != 'urn')
			throw new osid_OperationFailedException('The Id Passed does not have the urn namespace.');
		
		if (preg_match('/:/', $id->getAuthority()))
			throw new osid_OperationFailedException('The Id Passed has a \':\' in the authority and will not generate a valid URN string.');
			
		return 'urn:inet:'.$id->getAuthority().':'.$id->getIdentifier();
	}
}
