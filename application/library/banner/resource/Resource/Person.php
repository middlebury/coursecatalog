<?php
/**
 * @since 4/14/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 *  <p>A <code> Resource </code> represents an arbitrary entity. Resources are
 *  used to define an object to accompany an OSID <code> Id </code> used in
 *  other OSIDs. A resource may be used to represent a meeting room in the
 *  Scheduling OSID, or a student in the Course OSID. </p>.
 *
 *  <p> A <code> Resource </code> may also represent a group or organization.
 *  A provider may present such a group in an opaque manner through a single
 *  resource definition, or the provider may expose the resource collection
 *  for examination or manipulation. If such a resource collection is visible,
 *  <code> isGroup() </code> is <code> true </code> and can be used in one of
 *  the group sessions available in this OSID. </p>
 */
class banner_resource_Resource_Person extends phpkit_AbstractOsidObject implements osid_resource_Resource, middlebury_resource_Resource_PersonNamesRecord
{
    /**
     * Constructor.
     *
     * @param string $surname
     * @param string $givenName
     * @param optional string $middleNames
     * @param optional string $nameSuffix
     * @param optional string $prefixTitle
     * @param optional string $suffixTitle
     *
     * @return void
     *
     * @since 4/13/09
     */
    public function __construct(osid_id_Id $id, $surname, $givenName, $middleNames = '', $nameSuffix = '', $prefixTitle = '', $suffixTitle = '')
    {
        $this->namesType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:person_names');

        parent::__construct();
        $this->setId($id);

        $this->surname = $surname;
        $this->givenName = $givenName;
        $this->middleNames = $middleNames;
        $this->nameSuffix = $nameSuffix;
        $this->prefixTitle = $prefixTitle;
        $this->suffixTitle = $suffixTitle;

        $parts = [];
        if ($part = $this->getPrefixTitle()) {
            $parts[] = $part;
        }
        if ($part = $this->getGivenName()) {
            $parts[] = $part;
        }
        if ($part = $this->getMiddleNames()) {
            $parts[] = $part;
        }
        if ($part = $this->getSurname()) {
            $parts[] = $part;
        }
        if ($part = $this->getNameSuffix()) {
            $parts[] = $part;
        }
        if ($part = $this->getSuffixTitle()) {
            if (count($parts)) {
                $parts[count($parts) - 1] .= ',';
            }
            $parts[] = $part;
        }
        $this->setDisplayName(implode(' ', $parts));

        $this->setDescription('');

        $this->addRecordType($this->namesType);

        $this->setGenusType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:resource.person'));
    }

    /**
     *  Tests if this resource is a group. A resource that is a group can be
     *  used in the group sessions.
     *
     * @return boolean <code> true </code> if this resource is a group,
     *                        <code> false </code> otherwise
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function isGroup()
    {
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
     *
     * @return object osid_resource_ResourceRecord the resource record
     *
     * @throws osid_NullArgumentException <code> resourceRecordType </code>
     *                                           is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure occurred
     * @throws osid_UnsupportedException <code>
     *                                           hasRecordType(resourceRecordType) </code> is <code> false
     *                                           </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getResourceRecord(osid_type_Type $resourceRecordType)
    {
        if ($resourceRecordType->isEqual($this->namesType)) {
            return $this;
        }

        throw new osid_UnsupportedException('ResourceRecordType passed is not supported.');
    }

    /*********************************************************
     * Resource Record
     *********************************************************/
    /**
     *  Tests if the given type is implemented by this record. Other types
     *  than that directly indicated by <code> getType() </code> may be
     *  supported through an inheritance scheme where the given type specifies
     *  a record that is a parent interface of the interface specified by
     *  <code> getType(). </code>.
     *
     *  @param object osid_type_Type $recordType a type
     *
     * @return boolean <code> true </code> if the given record <code> Type
     *                        </code> is implemented by this record, <code> false </code>
     *                        otherwise
     *
     * @throws osid_NullArgumentException <code> recordType </code> is <code>
     *                                           null </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function implementsRecordType(osid_type_Type $recordType)
    {
        return $recordType->isEqual($this->namesType);
    }

    /**
     *  Gets the <code> Resource </code> from which this record originated.
     *
     * @return object osid_resource_Resource the resource
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getResource()
    {
        return $this;
    }

    /*********************************************************
     * Person Names Record
     *********************************************************/

    /**
     * Gets the given (first) name of a person.
     *
     * @return string
     *
     * @compliance mandatory This method must be implemented.
     */
    public function getGivenName()
    {
        return $this->givenName;
    }

    /**
     * Gets the surname (family name/last name) of a person.
     *
     * @return string
     *
     * @compliance mandatory This method must be implemented.
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Gets the middle name[s] of a person separated by spaces.
     *
     * @return string
     *
     * @compliance mandatory This method must be implemented.
     */
    public function getMiddleNames()
    {
        return $this->middleNames;
    }

    /**
     * Gets the middle initial[s] of a person with any appropriate punctuation.
     *
     * @return string
     *
     * @compliance mandatory This method must be implemented.
     */
    public function getMiddleInitials()
    {
        $initials = '';
        foreach (explode(' ', $this->getMiddleNames()) as $name) {
            $name = trim($name);
            if (strlen($name)) {
                $initials .= $name[0].'.';
            }
        }

        return $initials;
    }

    /**
     * Gets any suffix non-title suffix of a person that would appear after their name.
     * E.g. 'Junior', 'Jr.', 'Sr.', 'III'.
     *
     * @return string
     *
     * @compliance mandatory This method must be implemented.
     */
    public function getNameSuffix()
    {
        return $this->nameSuffix;
    }

    /**
     * Gets any title of a person that would appear before their name. E.g. 'Mr.',
     * 'Dr.', 'Miss', 'Admiral', etc.
     *
     * @return string
     *
     * @compliance mandatory This method must be implemented.
     */
    public function getPrefixTitle()
    {
        return $this->prefixTitle;
    }

    /**
     * Gets any title of a person that would appear after their name. E.g. 'Ph.D.',
     * 'Esquire', etc.
     *
     * @return string
     *
     * @compliance mandatory This method must be implemented.
     */
    public function getSuffixTitle()
    {
        return $this->suffixTitle;
    }
}
