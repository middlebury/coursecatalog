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
class phpkit_id_Id
	implements osid_id_Id
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
	public function __construct ($authority, $namespace, $identifier) {
		$regex = '/[^\s]+/i';
		if (!preg_match($regex, $authority))
			throw new osid_InvalidArgumentException("Invalid authority '$authority'. Authority must match $regex.");
		if (!preg_match($regex, $namespace))
			throw new osid_InvalidArgumentException("Invalid namespace '$namespace'. Namespace must match $regex.");
		if (!preg_match($regex, $identifier))
			throw new osid_InvalidArgumentException("Invalid identifier '$identifier'. Identifier must match $regex.");
		
		$this->authority = $authority;
		$this->namespace = $namespace;
		$this->identifier = $identifier;
	}
	
	/**
	 * @var string $authority;  
	 * @access private
	 * @since 10/28/08
	 */
	private $authority;
	
	/**
	 * @var string $namespace;  
	 * @access private
	 * @since 10/28/08
	 */
	private $namespace;
	
	/**
	 * @var string $identifier;  
	 * @access private
	 * @since 10/28/08
	 */
	private $identifier;

    /**
     *  Gets the authority of this <code> Id. </code> The authority is a 
     *  string used to ensure the uniqueness of this <code> Id </code> when 
     *  using a non-federated identifier space. Generally, it is a domain name 
     *  identifying the party responsible for this <code> Id. </code> This 
     *  method is used to compare one <code> Id </code> to anoher. 
     *
     *  @return the authority of this <code> Id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getAuthority() {
    	return $this->authority;
    }


    /**
     *  Gets the namespace of the identifier. This method is used to compare 
     *  one <code> Id </code> to another. 
     *
     *  @return the authority of this <code> Id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getIdentifierNamespace() {
    	return $this->namespace;
    }


    /**
     *  Gets the identifier of this <code> Id. </code> This method is used to 
     *  compare one <code> Id </code> to another. 
     *
     *  @return the identifier of this <code> Id </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getIdentifier() {
    	return $this->identifier;
    }


    /**
     *  Determines if the given <code> Id </code> is equal to this one. Two 
     *  Ids are equal if the namespace, authority and identifier components 
     *  are equal. The identifier is case sensitive while the namespace and 
     *  authority strings are not case sensitive. 
     *
     *  @param object osid_id_Id $id the <code> Id </code> to compare 
     *  @return <code> true </code> if the given <code> Id </code> is equal to 
     *          this one, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isEqual(osid_id_Id $id) {
    	if (strtolower($this->getAuthority()) != strtolower($id->getAuthority()))
    		return false;
    	if (strtolower($this->getIdentifierNamespace()) != strtolower($id->getIdentifierNamespace()))
    		return false;
    		
    	return ($this->getIdentifier() == $id->getIdentifier());
    }

}
