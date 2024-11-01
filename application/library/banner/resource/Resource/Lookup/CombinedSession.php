<?php
/**
 * @since 5/04/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 *  <p>This session defines methods for retrieving resources. A <code>
 *  Resource </code> is an arbitrary entity that may represent a person, place
 *  or thing used to identify an object used in various services. </p>.
 *
 *  <p> This lookup session defines several views: </p>
 *
 *  <p>
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered
 *      </li>
 *      <li> plenary view: provides a complete result set or is an error
 *      condition </li>
 *      <li> isolated bin view: All resource methods in this session operate,
 *      retrieve and pertain to resources defined explicitly in the current
 *      bin. Using an isolated view is useful for managing <code> Resources
 *      </code> with the <code> ResourceAdminSession. </code> </li>
 *      <li> federated bin view: All resource methods in this session operate,
 *      retrieve and pertain to all resources defined in this bin and any
 *      other resources implicitly available in this bin through bin
 *      inheritence. </li>
 *  </ul>
 *  The methods <code> useFederatedBinView() </code> and <code>
 *  useIsolatedBinView() </code> behave as a radio group and one should be
 *  selected before invoking any lookup methods. Resources may have an
 *  additional records indicated by their respective record types. The record
 *  may not be accessed through a cast of the <code> Resource. </code> </p>
 */
class banner_resource_Resource_Lookup_CombinedSession extends banner_AbstractSession implements osid_resource_ResourceLookupSession
{
    /**
     * Constructor.
     *
     * @return void
     *
     * @since 5/4/09
     */
    public function __construct(banner_ManagerInterface $manager)
    {
        parent::__construct($manager, 'resource.');
    }

    /**
     *  Gets the <code> Bin </code> <code> Id </code> associated with this
     *  session.
     *
     * @return object osid_id_Id the <code> Bin Id </code> associated with
     *                this session
     *
     * @throws osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getBinId()
    {
        return $this->manager->getCombinedBinId();
    }

    /**
     *  Gets the <code> Bin </code> associated with this session.
     *
     * @return object osid_resource_Bin the <code> Bin </code> associated
     *                with this session
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     * @throws osid_IllegalStateException     this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getBin()
    {
        return new banner_resource_Bin_Combined($this->manager);
    }

    /**
     *  Tests if this user can perform <code> Resource </code> lookups. A
     *  return of true does not guarantee successful authorization. A return
     *  of false indicates that it is known all methods in this session will
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a
     *  hint to an application that may opt not to offer lookup operations.
     *
     * @return boolean <code> false </code> if lookup methods are not
     *                        authorized, <code> true </code> otherwise
     *
     * @throws osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function canLookupResources()
    {
        return true;
    }

    /**
     *  The returns from the lookup methods may omit or translate elements
     *  based on this session, such as authorization, and not result in an
     *  error. This view is used when greater interoperability is desired at
     *  the expense of precision.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function useComparativeResourceView()
    {
        $this->useComparativeView();
    }

    /**
     *  A complete view of the <code> Resource </code> returns is desired.
     *  Methods will return what is requested or result in an error. This view
     *  is used when greater precision is desired at the expense of
     *  interoperability.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function usePlenaryResourceView()
    {
        $this->usePlenaryView();
    }

    /**
     *  Federates the view for methods in this session. A federated view will
     *  include resources in bins which are children of this bin in the bin
     *  hierarchy.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function useFederatedBinView()
    {
        $this->useFederatedView();
    }

    /**
     *  Isolates the view for methods in this session. An isolated view
     *  restricts lookups to this bin only.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function useIsolatedBinView()
    {
        $this->useIsolatedView();
    }

    /**
     *  Gets the <code> Resource </code> specified by its <code> Id. </code>
     *  In plenary mode, the exact <code> Id </code> is found or a <code>
     *  NOT_FOUND </code> results. Otherwise, the returned <code> Resource
     *  </code> may have a different <code> Id </code> than requested, such as
     *  the case where a duplicate <code> Id </code> was assigned to a <code>
     *  Resource </code> and retained for compatibility.
     *
     *  @param object osid_id_Id $resourceId the <code> Id </code> of the
     *          <code> Resource </code> to rerieve
     *
     * @return object osid_resource_Resource the returned <code> Resource
     *                </code>
     *
     * @throws osid_NotFoundException            no <code> Resource </code> found with
     *                                           the given <code> Id </code>
     * @throws osid_NullArgumentException <code> resourceId </code> is <code>
     *                                           null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getResource(osid_id_Id $resourceId)
    {
        //     	if ($this->usesIsolatedView() && $this->getBinId()->isEqual($this->manager->getCombinedBinId()))
        //     		throw new osid_NotFoundException('This Bin does not directly contain any resources. Use useFederatedView() to access resources in child bins.');
        $type = $this->getResourceType($resourceId);
        switch ($type) {
            case 'person':
                return $this->getPersonResource($resourceId);
            case 'place.room':
                return $this->getRoomResource($resourceId);
            case 'place.building':
                return $this->getBuildingResource($resourceId);
            case 'place.campus':
                return $this->getCampusResource($resourceId);
            default:
                throw new osid_NotFoundException('No resource found with category '.$type);
        }
    }

    /**
     * Answer the type string corresponding to the resource id.
     *
     * @return string
     *
     * @since 4/24/09
     */
    public function getResourceType(osid_id_Id $resourceId)
    {
        $string = $this->getDatabaseIdString($resourceId, 'resource.');
        if (!preg_match('#(person|place\.room|place\.building|place\.campus)\.(.+)#', $string, $matches)) {
            throw new osid_NotFoundException('Could not turn "'.$string.'" into a resource type.');
        }

        return $matches[1];
    }

    /**
     * Answer the value string corresponding to the resource id.
     *
     * @return string
     *
     * @since 4/24/09
     */
    public function getResourceValue(osid_id_Id $resourceId)
    {
        $string = $this->getDatabaseIdString($resourceId, 'resource.');
        if (!preg_match('#(person|place\.room|place\.building|place\.campus)\.(.+)#', $string, $matches)) {
            throw new osid_NotFoundException('Could not turn "'.$string.'" into a resource type.');
        }

        return $matches[2];
    }

    private static $getPersonResource_stmt;

    /**
     * Answer a person Resource by id.
     *
     * @return osid_resource_Resource
     *
     * @since 4/24/09
     */
    private function getPersonResource(osid_id_Id $resourceId)
    {
        if (!isset(self::$getPersonResource_stmt)) {
            $query =
'SELECT
	WEB_ID,
	SYVINST_LAST_NAME,
	SYVINST_FIRST_NAME
FROM
	SYVINST
WHERE
	WEB_ID = :webid
';
            self::$getPersonResource_stmt = $this->manager->getDB()->prepare($query);
        }

        $parameters = [
            ':webid' => $this->getDatabaseIdString($resourceId, 'resource.person.'),
        ];
        self::$getPersonResource_stmt->execute($parameters);
        $row = self::$getPersonResource_stmt->fetch(PDO::FETCH_ASSOC);
        self::$getPersonResource_stmt->closeCursor();

        if (empty($row) || !$row['WEB_ID']) {
            throw new osid_NotFoundException('Could not find a resource  matching the person code '.$this->getDatabaseIdString($resourceId, 'resource.person.').'.');
        }

        return new banner_resource_Resource_Person(
            $this->getOsidIdFromString($row['WEB_ID'], 'resource.person.'),
            $row['SYVINST_LAST_NAME'],
            $row['SYVINST_FIRST_NAME']
        );
    }

    private static $getPersonResources_stmt;

    /**
     * Answer all of the person resources.
     *
     * @return osid_resource_ResourceList
     *
     * @since 4/24/09
     */
    private function getPersonResources()
    {
        if (!isset(self::$getPersonResources_stmt)) {
            $query =
'SELECT
	WEB_ID,
	SYVINST_LAST_NAME,
	SYVINST_FIRST_NAME
FROM
	SYVINST
GROUP BY WEB_ID
';
            self::$getPersonResources_stmt = $this->manager->getDB()->prepare($query);
        }

        self::$getPersonResources_stmt->execute();

        $resources = [];
        while ($row = self::$getPersonResources_stmt->fetch(PDO::FETCH_ASSOC)) {
            $resources[] = new banner_resource_Resource_Person(
                $this->getOsidIdFromString($row['WEB_ID'], 'resource.person.'),
                $row['SYVINST_LAST_NAME'],
                $row['SYVINST_FIRST_NAME']
            );
        }
        self::$getPersonResources_stmt->closeCursor();

        return new phpkit_resource_ArrayResourceList($resources);
    }

    private static $getBuildingResource_stmt;

    /**
     * Answer a building Resource by id.
     *
     * @return osid_resource_Resource
     *
     * @since 4/24/09
     */
    private function getBuildingResource(osid_id_Id $resourceId)
    {
        if (!isset(self::$getBuildingResource_stmt)) {
            $query =
'SELECT
	STVBLDG_CODE,
	STVBLDG_DESC
FROM
	STVBLDG
WHERE
	STVBLDG_CODE = :code
';
            self::$getBuildingResource_stmt = $this->manager->getDB()->prepare($query);
        }

        $parameters = [
            ':code' => $this->getDatabaseIdString($resourceId, 'resource.place.building.'),
        ];
        self::$getBuildingResource_stmt->execute($parameters);
        $row = self::$getBuildingResource_stmt->fetch(PDO::FETCH_ASSOC);
        self::$getBuildingResource_stmt->closeCursor();

        if (!$row || !$row['STVBLDG_CODE']) {
            throw new osid_NotFoundException('Could not find a resource  matching the building code '.$this->getDatabaseIdString($resourceId, 'resource.place.building.').'.');
        }

        return new banner_resource_Resource_Building(
            $this->getOsidIdFromString($row['STVBLDG_CODE'], 'resource.place.building.'),
            $row['STVBLDG_DESC'],
            $row['STVBLDG_CODE']
        );
    }

    private static $getBuildingResources_stmt;

    /**
     * Answer all of the building resources.
     *
     * @return osid_resource_ResourceList
     *
     * @since 4/24/09
     */
    private function getBuildingResources()
    {
        if (!isset(self::$getBuildingResources_stmt)) {
            $query =
'SELECT
	STVBLDG_CODE,
	STVBLDG_DESC
FROM
	STVBLDG
';
            self::$getBuildingResources_stmt = $this->manager->getDB()->prepare($query);
        }

        self::$getBuildingResources_stmt->execute();

        $resources = [];
        while ($row = self::$getBuildingResources_stmt->fetch(PDO::FETCH_ASSOC)) {
            $resources[] = new banner_resource_Resource_Building(
                $this->getOsidIdFromString($row['STVBLDG_CODE'], 'resource.place.building.'),
                $row['STVBLDG_DESC'],
                $row['STVBLDG_CODE']
            );
        }
        self::$getBuildingResources_stmt->closeCursor();

        return new phpkit_resource_ArrayResourceList($resources);
    }

    private static $getRoomResource_stmt;

    /**
     * Answer a room Resource by id.
     *
     * @return osid_resource_Resource
     *
     * @since 4/24/09
     */
    private function getRoomResource(osid_id_Id $resourceId)
    {
        if (!isset(self::$getRoomResource_stmt)) {
            $query =
'SELECT
	SSRMEET_ROOM_CODE,
	STVBLDG_CODE,
	STVBLDG_DESC
FROM
	SSRMEET
	LEFT JOIN STVBLDG ON SSRMEET_BLDG_CODE = STVBLDG_CODE
WHERE
	SSRMEET_BLDG_CODE = :bldg_code
	AND SSRMEET_ROOM_CODE = :room_code
GROUP BY
	STVBLDG_CODE, SSRMEET_ROOM_CODE
';
            self::$getRoomResource_stmt = $this->manager->getDB()->prepare($query);
        }

        $roomString = $this->getDatabaseIdString($resourceId, 'resource.place.room.');
        if (!preg_match('#^([a-z0-9_-]+)\.(.+)$#i', $roomString, $matches)) {
            throw new osid_NotFoundException("Room string '$roomString' doesn't match.");
        }

        $parameters = [
            ':bldg_code' => $matches[1],
            ':room_code' => $matches[2],
        ];
        self::$getRoomResource_stmt->execute($parameters);
        $row = self::$getRoomResource_stmt->fetch(PDO::FETCH_ASSOC);
        self::$getRoomResource_stmt->closeCursor();

        if (!$row || !$row['STVBLDG_CODE']) {
            throw new osid_NotFoundException('Could not find a resource  matching the room code '.$this->getDatabaseIdString($resourceId, 'resource.place.room.').'.');
        }

        return new banner_resource_Resource_Room(
            $this->getOsidIdFromString($row['STVBLDG_CODE'].'.'.$row['SSRMEET_ROOM_CODE'], 'resource.place.room.'),
            $row['STVBLDG_DESC'],
            $row['STVBLDG_CODE'],
            $row['SSRMEET_ROOM_CODE']
        );
    }

    private static $getRoomResources_stmt;

    /**
     * Answer all of the room resources.
     *
     * @return osid_resource_ResourceList
     *
     * @since 4/24/09
     */
    private function getRoomResources()
    {
        if (!isset(self::$getRoomResources_stmt)) {
            $query =
'SELECT
	SSRMEET_ROOM_CODE,
	STVBLDG_CODE,
	STVBLDG_DESC
FROM
	SSRMEET
	LEFT JOIN STVBLDG ON SSRMEET_BLDG_CODE = STVBLDG_CODE
WHERE
	SSRMEET_BLDG_CODE IS NOT NULL
GROUP BY
	STVBLDG_CODE, SSRMEET_ROOM_CODE
';
            self::$getRoomResources_stmt = $this->manager->getDB()->prepare($query);
        }

        self::$getRoomResources_stmt->execute();

        $resources = [];
        while ($row = self::$getRoomResources_stmt->fetch(PDO::FETCH_ASSOC)) {
            $resources[] = new banner_resource_Resource_Room(
                $this->getOsidIdFromString($row['STVBLDG_CODE'].'.'.$row['SSRMEET_ROOM_CODE'], 'resource.place.room.'),
                $row['STVBLDG_DESC'],
                $row['STVBLDG_CODE'],
                $row['SSRMEET_ROOM_CODE']
            );
        }
        self::$getRoomResources_stmt->closeCursor();

        return new phpkit_resource_ArrayResourceList($resources);
    }

    private static $getCampusResource_stmt;

    /**
     * Answer a campus Resource by id.
     *
     * @return osid_resource_Resource
     *
     * @since 4/24/09
     */
    private function getCampusResource(osid_id_Id $resourceId)
    {
        if (!isset(self::$getCampusResource_stmt)) {
            $query =
'SELECT
	STVCAMP_CODE,
	STVCAMP_DESC
FROM
	STVCAMP
WHERE
	STVCAMP_CODE = :code
';
            self::$getCampusResource_stmt = $this->manager->getDB()->prepare($query);
        }

        $parameters = [
            ':code' => $this->getDatabaseIdString($resourceId, 'resource.place.campus.'),
        ];
        self::$getCampusResource_stmt->execute($parameters);
        $row = self::$getCampusResource_stmt->fetch(PDO::FETCH_ASSOC);
        self::$getCampusResource_stmt->closeCursor();

        if (!$row || !$row['STVCAMP_CODE']) {
            throw new osid_NotFoundException('Could not find a resource  matching the campus code '.$this->getDatabaseIdString($resourceId, 'resource.place.campus.').'.');
        }

        return new banner_resource_Resource_Place(
            $this->getOsidIdFromString($row['STVCAMP_CODE'], 'resource.place.campus.'),
            $row['STVCAMP_DESC'],
            '',
            new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:resource.place.campus')
        );
    }

    private static $getCampusResources_stmt;

    /**
     * Answer all of the campus resources.
     *
     * @return osid_resource_ResourceList
     *
     * @since 4/24/09
     */
    private function getCampusResources()
    {
        if (!isset(self::$getCampusResources_stmt)) {
            $query =
'SELECT
	STVCAMP_CODE,
	STVCAMP_DESC
FROM
	STVCAMP
';
            self::$getCampusResources_stmt = $this->manager->getDB()->prepare($query);
        }

        self::$getCampusResources_stmt->execute();

        $resources = [];
        while ($row = self::$getCampusResources_stmt->fetch(PDO::FETCH_ASSOC)) {
            $resources[] = new banner_resource_Resource_Place(
                $this->getOsidIdFromString($row['STVCAMP_CODE'], 'resource.place.campus.'),
                $row['STVCAMP_DESC'],
                '',
                new phpkit_type_URNInetType('urn:inet:middlebury.edu:genera:resource.place.campus')
            );
        }
        self::$getCampusResources_stmt->closeCursor();

        return new phpkit_resource_ArrayResourceList($resources);
    }

    /**
     *  Gets a <code> ResourceList </code> corresponding to the given <code>
     *  IdList. </code> In plenary mode, the returned list contains all of the
     *  resources specified in the <code> Id </code> list, in the order of the
     *  list, including duplicates, or an error results if an <code> Id
     *  </code> in the supplied list is not found or inaccessible. Otherwise,
     *  inaccessible <code> Resources </code> may be omitted from the list and
     *  may present the elements in any order including returning a unique
     *  set.
     *
     *  @param object osid_id_IdList $resourceIdList the list of <code> Ids
     *          </code> to rerieve
     *
     * @return object osid_resource_ResourceList the returned <code> Resource
     *                list </code>
     *
     * @throws osid_NotFoundException            an <code> Id was </code> not found
     * @throws osid_NullArgumentException <code> resourceIdList </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getResourcesByIds(osid_id_IdList $resourceIdList)
    {
        $resources = [];

        while ($resourceIdList->hasNext()) {
            try {
                $resources[] = $this->getResource($resourceIdList->getNextId());
            } catch (osid_NotFoundException $e) {
                if ($this->usesPlenaryView()) {
                    throw $e;
                }
            } catch (osid_PermissionDeniedException $e) {
                if ($this->usesPlenaryView()) {
                    throw $e;
                }
            }
        }

        return new phpkit_resource_ArrayResourceList($resources);
    }

    /**
     *  Gets a <code> ResourceList </code> corresponding to the given resource
     *  genus <code> Type </code> which does not include resources of types
     *  derived from the specified <code> Type. </code> In plenary mode, the
     *  returned list contains all known resources or an error results.
     *  Otherwise, the returned list may contain only those resources that are
     *  accessible through this session. In both cases, the order of the set
     *  is not specified.
     *
     *  @param object osid_type_Type $resourceGenusType a resource genus type
     *
     * @return object osid_resource_ResourceList the returned <code> Resource
     *                list </code>
     *
     * @throws osid_NullArgumentException <code> resourceGenusType </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getResourcesByGenusType(osid_type_Type $resourceGenusType)
    {
        if ('urn' != strtolower($resourceGenusType->getIdentifierNamespace())
            || 'middlebury.edu' != $resourceGenusType->getAuthority()) {
            return new phpkit_EmptyList('osid_resource_ResourceList');
        }
        switch ($resourceGenusType->getIdentifier()) {
            case 'genera:resource.person':
                return $this->getPersonResources();
            case 'genera:resource.place.campus':
                return $this->getCampusResources();
            case 'genera:resource.place.building':
                return $this->getBuildingResources();
            case 'genera:resource.place.room':
                return $this->getRoomResources();
                //    			case 'genera:resource.place':
                //    				return $this->getPlaceResources();
            default:
                return new phpkit_EmptyList('osid_resource_ResourceList');
        }
    }

    /**
     *  Gets a <code> ResourceList </code> corresponding to the given resource
     *  genus <code> Type </code> and include any additional resources with
     *  genus types derived from the specified <code> Type. </code> In plenary
     *  mode, the returned list contains all known resources or an error
     *  results. Otherwise, the returned list may contain only those resources
     *  that are accessible through this session. In both cases, the order of
     *  the set is not specified.
     *
     *  @param object osid_type_Type $resourceGenusType a resource genus type
     *
     * @return object osid_resource_ResourceList the returned <code> Resource
     *                list </code>
     *
     * @throws osid_NullArgumentException <code> resourceGenusType </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getResourcesByParentGenusType(osid_type_Type $resourceGenusType)
    {
        if ('urn' != strtolower($resourceGenusType->getIdentifierNamespace())
            || 'middlebury.edu' != $resourceGenusType->getAuthority()) {
            return new phpkit_EmptyList('osid_resource_ResourceList');
        }

        if ('genera:resource.place' == $resourceGenusType->getIdentifier()) {
            $resourceList = new phpkit_CombinedList('osid_resource_ResourceList');
            $resourceList->addList($this->getCampusResources());
            $resourceList->addList($this->getBuildingResources());
            $resourceList->addList($this->getRoomResources());

            return $resourceList;
        } else {
            return $this->getResourcesByGenusType($resourceGenusType);
        }
    }

    /**
     *  Gets a <code> ResourceList </code> containing the given resource
     *  record <code> Type. </code> In plenary mode, the returned list
     *  contains all known resources or an error results. Otherwise, the
     *  returned list may contain only those resources that are accessible
     *  through this session. In both cases, the order of the set is not
     *  specified.
     *
     *  @param object osid_type_Type $resourceRecordType a resource record
     *          type
     *
     * @return object osid_resource_ResourceList the returned <code> Resource
     *                list </code>
     *
     * @throws osid_NullArgumentException <code> resourceRecordType </code>
     *                                           is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getResourcesByRecordType(osid_type_Type $resourceRecordType)
    {
        return new phpkit_EmptyList();
    }

    /**
     *  Gets all <code> Resources. </code> In plenary mode, the returned list
     *  contains all known resources or an error results. Otherwise, the
     *  returned list may contain only those resources that are accessible
     *  through this session. In both cases, the order of the set is not
     *  specifed.
     *
     * @return object osid_resource_ResourceList a list of <code> Resources
     *                </code>
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     * @throws osid_IllegalStateException     this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getResources()
    {
        $resourceList = new phpkit_CombinedList('osid_resource_ResourceList');
        $resourceList->addList($this->getPersonResources());
        $resourceList->addList($this->getCampusResources());
        $resourceList->addList($this->getBuildingResources());
        $resourceList->addList($this->getRoomResources());

        return $resourceList;
    }
}
