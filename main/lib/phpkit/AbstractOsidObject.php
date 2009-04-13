<?php

/**
 * osid_OsidObject
 * 
 *     Specifies the OSID definition for osid_OsidObject.
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
 *  <p><code> OsidObject </code> is the top level interface for all OSID 
 *  objects. An OSID object is an object identified by an OSID <code> Id 
 *  </code> and may implements optional interfaces. OSID objects also contain 
 *  a display name and a description. These fields are required but may be 
 *  used for a variety of purposes ranging from a primary name and description 
 *  of the object to a more user friendly display of various attributes. </p> 
 *  
 *  <p> Creation of OSID objects and the modification of their data is managed 
 *  through the associated <code> OsidSession </code> which removes the 
 *  dependency of updating data elements upon object retrieval. <code> </code> 
 *  The <code> OsidManager </code> should be used to test if updates are 
 *  available and determine what <code> PropertyTypes </code> are supported. 
 *  The <code> OsidManager </code> is also used to create the appropriate 
 *  <code> OsidSession </code> for object creation, updates and deletes. All 
 *  <code> OsidObjects </code> are identified by an immutable <code> Id. 
 *  </code> An <code> Id </code> is assigned to an object upon creation of the 
 *  object and cannot be changed once assigned. An OSID object may support one 
 *  or more supplementary records which are expressed in the form of 
 *  interfaces. Each record interface is identified by a Type. A record 
 *  interface may extend another record interface where support of the parent 
 *  record interface is implied. In this case of interface inheritance, 
 *  support of the parent record type may be implied through <code> 
 *  hasRecordType() </code> and not explicit in <code> getRecordTypes(). 
 *  </code> For example, if recordB extends recordA, typeB is a child of 
 *  typeA. If a record implements typeB, than it also implements typeA. An 
 *  application that only knows about typeA retrieves recordA. An application 
 *  that knows about typeB, retrieves recordB which is the union of methods 
 *  specified in typeA and typeB. If an application requests typeA, it may not 
 *  attempt to access methods defined in typeB as they may not exist until 
 *  explicitly requested. The mechanics of this polymorphism is defined by the 
 *  language binder. One mechanism might be the use of casting. In addition to 
 *  the record <code> Types, </code> OSID objects also have a genus <code> 
 *  Type. </code> A genus <code> Type </code> indicates a classification or 
 *  kind of the object where an "is a" relationship exists. The purpose of of 
 *  the genus <code> Type </code> is to avoid the creation of unnecessary 
 *  record types that may needlessly complicate an interface hierarchy or 
 *  introduce interoperability issues. For example, an OSID object may have a 
 *  record <code> Type </code> of <code> Publication </code> that defines 
 *  methods pertinent to publications, such as an ISBN number. A provider may 
 *  wish to distinguish between books and journals without having the need of 
 *  new record interfaces. In this case, the genus <code> Type </code> may be 
 *  one of <code> Book </code> or <code> Journal. </code> While this 
 *  distinction can aid a search, these genres should be treated in such a way 
 *  that do not introduce interoperability problems. Like record Types, the 
 *  genus Types may also exist in an implicit type hierarchy. An OSID object 
 *  always has at least one genus. Genus types should not be confused with 
 *  subject tagging, which is managed externally to the object. Unlike record 
 *  <code> Types, </code> an object's genus may be modified. However, once an 
 *  object's record is created with a record <code> Type, </code> it cannot be 
 *  changed. </p> 
 *  
 *  <p> Methods that return values are not permitted to return nulls. If a 
 *  value is not set, it is indicated in the <code> Metadata </code> of the 
 *  update form. </p>
 * 
 * @package org.osid
 */
abstract class phpkit_AbstractOsidObject 
	implements osid_OsidObject
{

    private $id;
    private $displayName = "Not Sure Who I Am...";
    private $description = "This is an abstract class for an object template. I'm sure I will do something great someday.";
    private $recordTypes;
    
    /**
	 * Constructor
	 * 
	 * @return void
	 * @access protected
	 * @since 10/28/08
	 */
	protected function __construct () {
		$this->recordTypes = array();
		$this->id = new phpkit_id_URNInetId("urn:inet:osid.org:id:implementation/SampleChangeMe");
	}


    /**
     *  Gets the <code> Id </code> associated with this instance of this OSID 
     *  object. Persisting any reference to this object is done by persisting 
     *  the <code> Id </code> returned from this method. The <code> Id </code> 
     *  returned may be different than the <code> Id </code> used to query 
     *  this object. In this case, the new <code> Id </code> should be 
     *  preferred over the old one for future queries. 
     *
     *  @return the <code> Id </code> 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  The <code> Id </code> is intended to be constant and 
     *          persistent. A consumer may at any time persist the <code> Id 
     *          </code> for retrieval at any future time. Ideally, the <code> 
     *          Id </code> should consistently resolve into the designated 
     *          object and not be reused. In cases where objects are 
     *          deactivated after a certain lifetime the provider should 
     *          endeavor not to obliterate the object or its <code> Id </code> 
     *          but instead should update the properties of the object 
     *          including the deactiavted status and the elimination of any 
     *          unwanted pieces of data. As such, there is no means for 
     *          updating an <code> Id </code> and providers should consider 
     *          carefully the identification scheme to implement. 
     *          <br/><br/>
     *          <code> Id </code> assignments for objects are strictly in the 
     *          realm of the provider and any errors should be fixed directly 
     *          with the backend supporting system. Once an <code> Id </code> 
     *          has been assigned in a production service it should be honored 
     *          such that it may be necessary for the backend system to 
     *          support <code> Id </code> aliasing to redirect the lookup to 
     *          the current <code> Id. </code> Use of an <code> Id </code> 
     *          OSID may be helpful to accomplish this task in a modular 
     *          manner. 
     */
    public function getId() {
		return $this->id;
	}

	/**
	 * Set the Id
	 * 
	 * @param object osid_id_Id $id
	 * @return void
	 * @access protected
	 * @since 10/28/08
	 */
	protected function setId (osid_id_Id $id) {
		if (isset($this->id) && !$this->id->isEqual(new phpkit_id_URNInetId("urn:inet:osid.org:id:implementation/SampleChangeMe")))
			throw new osid_IllegalStateException("Already initialized");
		
		$this->id = $id;
	}


    /**
     *  Gets the preferred display name associated with this instance of this 
     *  OSID object appropriate for display to the user. 
     *
     *  @return the display name 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  A display name is a string used for identifying an object in 
     *          human terms. A provider may wish to initialize the display 
     *          name based on one or more object attributes. In some cases, 
     *          the display name may not map to a specific or significant 
     *          object attribute but simply be used as a preferred display 
     *          name that can be modified. A provider may also wish to 
     *          translate the display name into a specific locale using the 
     *          Locale service. Some OSIDs define methods for more detailed 
     *          naming. 
     */
    public function getDisplayName() {
		return $this->displayName;
	}

	/**
	 * Set the DisplayName
	 * 
	 * @param string $displayName
	 * @return void
	 * @access protected
	 * @since 10/28/08
	 */
	protected function setDisplayName ($displayName) {
		$this->displayName = $displayName;
	}


    /**
     *  Gets the description associated with this instance of this OSID 
     *  object. 
     *
     *  @return the description 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  A description is a string used for describing an object in 
     *          human terms and may not have significance in the underlying 
     *          system. A provider may wish to initialize the description 
     *          based on one or more object attributes and/or treat it as an 
     *          auxiliary piece of data that can be modified. A provider may 
     *          also wish to translate the description into a specific locale 
     *          using the Locale service. 
     */
    public function getDescription() {
    	return $this->description;
	}

	/**
	 * Set the description
	 * 
	 * @param string $description
	 * @return void
	 * @access protected
	 * @since 10/28/08
	 */
	protected function setDescription ($description) {		
		$this->description = $description;
	}


    /**
     *  Gets the record types available in this object. A record <code> Type 
     *  </code> explicitly indicates the specification of an interface to the 
     *  record. A record may or may not inherit other record interfaces 
     *  through interface inheritance in which case support of a record type 
     *  may not be explicit in the returned list. Interoperability with the 
     *  typed interface to this object should be performed through <code> 
     *  hasRecordType(). </code> 
     *
     *  @return the record types available through this object 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getRecordTypes() {
    	return new phpkit_type_ArrayTypeList($this->recordTypes);
    }


    /**
     *  Tests if this object supports the given record <code> Type. </code> 
     *  The given record type may be supported by the object through 
     *  interface/type inheritence. This method should be checked before 
     *  retrieving the record interface. 
     *
     *  @param object osid_type_Type $recordType a type 
     *  @return <code> true </code> if a record of the given record <code> 
     *          Type </code> is available, <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> recordType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function hasRecordType(osid_type_Type $recordType) {
    	foreach ($this->recordTypes as $type) {
    		if ($type->isEqual($recordType))
    			return true;
    	}
    	return false;
    }

	/**
	 * Add a record type
	 * 
	 * @param object osid_type_Type $recordType
	 * @return void
	 * @access protected
	 * @since 10/28/08
	 */
	protected function addRecordType (osid_type_Type $recordType) {
		if (!$this->hasRecordType($recordType))
			$this->recordTypes[] = $recordType;
	}

    /**
     *  Gets the genus type of this object. 
     *
     *  @return the genus type of this object 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getGenusType() {
    	return $this->genusType;
    }
    
    /**
	 * Set the genus type
	 * 
	 * @param object osid_type_Type $genusType
	 * @return void
	 * @access protected
	 * @since 10/28/08
	 */
	protected function setGenusType (osid_type_Type $genusType) {
		$this->genusType = $genusType;
	}

    /**
     *  Tests if this object is of the given genus <code> Type. </code> The 
     *  given genus type may be supported by the object through the type 
     *  hierarchy. 
     *
     *  @param object osid_type_Type $genusType a genus type 
     *  @return <code> true </code> if this object is of the given genus 
     *          <code> Type, </code> <code> false </code> otherwise 
     *  @throws osid_NullArgumentException <code> genusType </code> is <code> 
     *          null </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function isOfGenusType(osid_type_Type $genusType) {
    	return $this->genusType->isEqual($genusType);
    }


    /**
     *  Tests to see if the last method invoked retrieved up-to-date data. 
     *  Simple retrieval methods do not specify errors as, generally, the data 
     *  is retrieved once at the time this object is instantiated. Some 
     *  implementations may provide real-time data though the application may 
     *  not always care. An implementation providing a real-time service may 
     *  fall back to a previous snapshot in case of error. This method returns 
     *  false if the data last retrieved was stale. 
     *
     *  @return <code> true </code> if the last data retrieval was up to date, 
     *          <code> false </code> otherwise 
     *  @compliance mandatory This method must be implemented. 
     *  @notes  Providers should return false unless all getters are 
     *          implemented using real-time queries, or some trigger process 
     *          keeps the data in this object current. Providers should 
     *          populate basic data elements at the time this object is 
     *          instantiated, or set an error, to ensure some data 
     *          availability. 
     */
    public function isCurrent() {
    	return false;
    }


    /**
     *  Gets a list of all properties of this object including those 
     *  corresponding to data within this object's records. Properties provide 
     *  a means for applications to display a representation of the contents 
     *  of an object without understanding its record interface 
     *  specifications. Applications needing to examine a specific property or 
     *  perform updates should use the methods defined by the object's record 
     *  <code> Type. </code> 
     *
     *  @return a list of properties 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException an authorization failure 
     *          occurred 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getProperties() {
    	$allProperties = array();
    	foreach ($this->properties as $typeString => $propertySet) {
    		$allProperties = array_merge($allProperties, $propertySet);
    	}
    	return new phpkit_ArrayPropertyList($allProperties);
    }


    /**
     *  Gets a list of properties corresponding to the specified record type. 
     *  Properties provide a means for applications to display a 
     *  representation of the contents of an object without understanding its 
     *  record interface specifications. Applications needing to examine a 
     *  specific property or perform updates should use the methods defined by 
     *  the object record <code> Type. </code> The resulting set includes 
     *  properties specified by parents of the record <code> type </code> in 
     *  the case a record's interface extends another. 
     *
     *  @param object osid_type_Type $recordType the record type corresponding 
     *          to the properties set to retrieve 
     *  @return a list of properties 
     *  @throws osid_NullArgumentException <code> recordType </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException an authorization failure 
     *          occurred 
     *  @throws osid_UnsupportedException <code> hasRecordType(recordType) 
     *          </code> is <code> false </code> 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getPropertiesByRecordType(osid_type_Type $recordType) {
    	if (!$this->hasRecordType($recordType)) {
			throw new osid_UnsupportedException("record type not supported");
		}
		
    	$typeString = phpkit_type_TypeUtils::typeToString($recordType);
    	if (isset($this->properties[$typeString]))
    		return new phpkit_ArrayPropertyList($this->properties[$typeString]);
    	else
    		return new phpkit_ArrayPropertyList(array());
    }
    
    /**
     * Add an array of Property objects to this object for the type specified
     * 
     * @param array of osid_Property objects $properties
     * @param osid_type_Type $recordType
     * @return void
     * @access protected
     * @since 10/28/08
     */
    protected function addProperties (array $properties, osid_type_Type $recordType) {
    	// Verify that the array has only properties objects
    	foreach ($properties as $property) {
    		if (!($property instanceof osid_Property))
    			throw new osid_IlleagalArguementException('$properties must be osid_Property objects');
    	}
    	
    	if (!$this->hasRecordType($recordType))
			$this->addRecordType($recordType);
    	
    	$this->properties[phpkit_type_TypeUtils::typeToString($recordType)] = $properties;
    }

}
