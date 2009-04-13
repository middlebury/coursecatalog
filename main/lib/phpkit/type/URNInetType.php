<?php

/**
 * osid_id_Type
 * 
 *     Specifies the OSID definition for osid_type_Type.
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
 *  <p>The Type is a form of identifier that is primarily used to identify 
 *  interface specifications. The <code> Type </code> differs from <code> Id 
 *  </code> in that it offers display information and <code> Types </code> may 
 *  be arranged in hierarchies to indicate an extended interface. 
 *  Semantically, an <code> Id </code> identifies any OSID object while the 
 *  <code> Type </code> identifies a specification. </p> 
 *  
 *  <p> The components of the Type that make up its identification are: </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> identifier: a unique key or guid </li> 
 *      <li> namespace: the namespace of the identifier </li> 
 *      <li> authority: the isuer of the identifier </li> 
 *  </ul>
 *  Persisting a type reference means to persist the above identification 
 *  elements. In addition to these identifier components, A <code> Type 
 *  </code> mai also provide some additional metadata such as a name, 
 *  description and domain. </p>
 * 
 * @package org.osid.type
 */
class phpkit_type_URNInetType
	extends phpkit_type_Type
{

	/**
	 * Construct a new <code>Type</code> from a URN
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
		
		parent::__construct($namespace, $authority, $identifier, 'Unspecified', $urn, $urn, "a type");
	}
	
	/**
	 * Convert this Type to a string for debugging purposes.
	 * 
	 * @return string
	 * @access public
	 * @since 4/13/09
	 */
	public function __toString () {
		return self::getInetURNString($this);
	}
	
	/**
	 * Answer an Type as a Inet URN string
	 * 
	 * @param object osid_type_Type $type
	 * @return string
	 * @throws osid_OperationFailedException The Id Passed does not have the urn namespace.
	 * @access public
	 * @since 10/30/08
	 * @static
	 */
	public static function getInetURNString (osid_type_Type $type) {
		if (strtolower($type->getIdentifierNamespace()) != 'urn')
			throw new osid_OperationFailedException('The Type Passed does not have the urn namespace.');
		
		if (preg_match('/:/', $type->getAuthority()))
			throw new osid_OperationFailedException('The Type Passed has a \':\' in the authority and will not generate a valid URN string.');
			
		return 'urn:inet:'.$type->getAuthority().':'.$type->getIdentifier();
	}
}
