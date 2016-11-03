<?php
/**
 * @since 5/04/09
 * @package banner.resource
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
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
class banner_resource_Resource_Place
	extends phpkit_AbstractOsidObject
	implements osid_resource_Resource
{

	/**
	 * Constructor
	 *
	 * @param osid_id_Id $id
	 * @param string $displayName
	 * @param string $description
	 * @param osid_type_Type $genusType
	 * @return void
	 * @access public
	 * @since 5/4/09
	 */
	public function __construct (osid_id_Id $id, $displayName, $description, osid_type_Type $genusType) {
		parent::__construct();

		$this->setId($id);
		$this->setDisplayName($displayName);
		$this->setDescription($description);
		$this->setGenusType($genusType);
	}

	/**
	 *  Tests if this resource is a group. A resource that is a group can be
	 *  used in the group sessions.
	 *
	 *  @return boolean <code> true </code> if this resource is a group,
	 *          <code> false </code> otherwise
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
	 *  @return object osid_resource_ResourceRecord the resource record
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
		throw new osid_UnsupportedException("ResourceRecordType passed is not supported.");
	}

}
