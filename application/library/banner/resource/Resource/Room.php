<?php
/**
 * @since 5/04/09
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
class banner_resource_Resource_Room extends banner_resource_Resource_Building
{
    private $room;

    /**
     * Constructor.
     *
     * @return void
     *
     * @since 5/4/09
     */
    public function __construct(osid_id_Id $id, $buildingDisplayName, $buildingCode, $room)
    {
        parent::__construct($id, $buildingDisplayName, $buildingCode);
        $this->setGenusType(new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:resource.place.room'));
        $this->setDisplayName($buildingDisplayName.' '.$room);
        $this->setDescription($buildingCode.' '.$room);
        $this->room = $room;
    }

    /**
     * Answers the room number if exists, null otherwise.
     *
     * @return string
     *
     * @compliance mandatory This method must be implemented.
     */
    public function getRoom()
    {
        return $this->room;
    }
}
