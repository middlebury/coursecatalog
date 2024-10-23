<?php
/**
 * @since 5/04/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 *  <p>This session provides methods for retrieving <code> Bin </code>
 *  objects. The <code> Bin </code> represents a collection resources. </p>.
 *
 *  <p> This session defines views that offer differing behaviors when
 *  retrieving multiple objects. </p>
 *
 *  <p>
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered
 *      </li>
 *      <li> plenary view: provides a complete set or is an error condition
 *      </li>
 *  </ul>
 *  Generally, the comparative view should be used for most applications as it
 *  permits operation even if there is data that cannot be accessed. For
 *  example, a browsing application may only need to examine the <code> Bins
 *  </code> it can access, without breaking execution. However, an
 *  administrative application may require all <code> Bin </code> elements to
 *  be available. Bins may have an additional records indicated by their
 *  respective record types. The record may not be accessed through a cast of
 *  the <code> Bin. </code> </p>
 */
class banner_resource_Bin_Lookup_Session extends banner_AbstractSession implements osid_resource_BinLookupSession
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
        parent::__construct($manager, 'catalog.');
    }

    /**
     *  Tests if this user can perform <code> Bin </code> lookups. A return of
     *  true does not guarantee successful authorization. A return of false
     *  indicates that it is known all methods in this session will result in
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an
     *  application that may opt not to offer lookup operations to
     *  unauthorized users.
     *
     * @return boolean <code> false </code> if lookup methods are not
     *                        authorized, <code> true </code> otherwise
     *
     * @throws osid_IllegalStateException this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function canLookupBins()
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
    public function useComparativeBinView()
    {
        $this->useComparativeView();
    }

    /**
     *  A complete view of the <code> Bin </code> returns is desired. Methods
     *  will return what is requested or result in an error. This view is used
     *  when greater precision is desired at the expense of interoperability.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function usePlenaryBinView()
    {
        $this->usePlenaryView();
    }

    private static $getBinById_stmt;

    /**
     *  Gets the <code> Bin </code> specified by its <code> Id. </code> In
     *  plenary mode, the exact <code> Id </code> is found or a <code>
     *  NOT_FOUND </code> results. Otherwise, the returned <code> Bin </code>
     *  may have a different <code> Id </code> than requested, such as the
     *  case where a duplicate <code> Id </code> was assigned to a <code> Bin
     *  </code> and retained for compatibility.
     *
     *  @param object osid_id_Id $binId <code> Id </code> of the <code> Bin
     *          </code>
     *
     * @return object osid_resource_Bin the bin
     *
     * @throws osid_NotFoundException <code>     binId </code> not found
     * @throws osid_NullArgumentException <code> binId </code> is <code> null
     *                                           </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function getBin(osid_id_Id $binId)
    {
        if ($binId->isEqual($this->manager->getCombinedBinId())) {
            return new banner_resource_Bin_Combined($this->manager->getCombinedBinId());
        }

        if (!isset(self::$getBinById_stmt)) {
            self::$getBinById_stmt = $this->manager->getDB()->prepare(
                'SELECT
	catalog_id,
	catalog_title
FROM
	course_catalog
WHERE
	catalog_id = :catalog_id
');
        }

        self::$getBinById_stmt->execute([':catalog_id' => $this->getDatabaseIdString($binId)]);

        $result = self::$getBinById_stmt->fetch(PDO::FETCH_ASSOC);
        self::$getBinById_stmt->closeCursor();

        if (!$result) {
            throw new osid_NotFoundException('Bin id not found. ');
        }

        return new banner_resource_Bin_PerCatalog(
            $this->getOsidIdFromString($result['catalog_id']),
            $result['catalog_title']);
    }

    /**
     *  Gets a <code> BinList </code> corresponding to the given <code>
     *  IdList. </code> In plenary mode, the returned list contains all of the
     *  bins specified in the <code> Id </code> list, in the order of
     *  the list, including duplicates, or an error results if an <code> Id
     *  </code> in the supplied list is not found or inaccessible. Otherwise,
     *  inaccessible <code> Bins </code> may be omitted from the list
     *  and may present the elements in any order including returning a unique
     *  set.
     *
     *  @param object osid_id_IdList $binIdList the list of <code> Ids
     *          </code> to rerieve
     *
     * @return object osid_resource_BinList the returned <code>
     *                Bin list </code>
     *
     * @throws osid_NotFoundException            an <code> Id was </code> not found
     * @throws osid_NullArgumentException <code> binIdList </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getBinsByIds(osid_id_IdList $binIdList)
    {
        $bins = [];

        while ($binIdList->hasNext()) {
            try {
                $bins[] = $this->getBin($binIdList->getNextId());
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

        return new phpkit_resource_ArrayBinList($bins);
    }

    /**
     *  Gets a <code> BinList </code> corresponding to the given
     *  bin genus <code> Type </code> which does not include
     *  bins of types derived from the specified <code> Type. </code>
     *  In plenary mode, the returned list contains all known bins or
     *  an error results. Otherwise, the returned list may contain only those
     *  bins that are accessible through this session. In both cases,
     *  the order of the set is not specified.
     *
     *  @param object osid_type_Type $binGenusType a bin genus
     *          type
     *
     * @return object osid_resource_BinList the returned <code>
     *                Bin list </code>
     *
     * @throws osid_NullArgumentException <code> binGenusType </code>
     *                                           is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getBinsByGenusType(osid_type_Type $binGenusType)
    {
        if ($binGenusType->isEqual(new phpkit_type_URNInetType('urn:inet:osid.org:genera:none'))) {
            return $this->getBins();
        } else {
            return new phpkit_resource_ArrayBinList([]);
        }
    }

    /**
     *  Gets a <code> BinList </code> corresponding to the given
     *  bin genus <code> Type </code> and include any additional
     *  bins with genus types derived from the specified <code> Type.
     *  </code> In plenary mode, the returned list contains all known
     *  bins or an error results. Otherwise, the returned list may
     *  contain only those bins that are accessible through this
     *  session. In both cases, the order of the set is not specified.
     *
     *  @param object osid_type_Type $binGenusType a bin genus
     *          type
     *
     * @return object osid_resource_BinList the returned <code>
     *                Bin list </code>
     *
     * @throws osid_NullArgumentException <code> binGenusType </code>
     *                                           is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getBinsByParentGenusType(osid_type_Type $binGenusType)
    {
        return $this->getBinsByGenusType($binGenusType);
    }

    /**
     *  Gets a <code> BinList </code> containing the given bin record <code>
     *  Type. </code> In plenary mode, the returned list contains all known
     *  bins or an error results. Otherwise, the returned list may contain
     *  only those bins that are accessible through this session. In both
     *  cases, the order of the set is not specified.
     *
     *  @param object osid_type_Type $binInterfaceType a bin
     *          interface type
     *
     * @return object osid_resource_BinList the returned <code>
     *                Bin list </code>
     *
     * @throws osid_NullArgumentException <code> binInterfaceType
     *                                           </code> is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getBinsByRecordType(osid_type_Type $binInterfaceType)
    {
        return new phpkit_resource_ArrayBinList([]);
    }

    private static $getBins_stmt;

    /**
     *  Gets all <code> Bins. </code> In plenary mode, the returned
     *  list contains all known bins or an error results. Otherwise,
     *  the returned list may contain only those bins that are
     *  accessible through this session. In both cases, the order of the set
     *  is not specified.
     *
     * @return object osid_resource_BinList a list of <code>
     *                Bins </code>
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     * @throws osid_IllegalStateException     this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getBins()
    {
        if (!isset(self::$getBins_stmt)) {
            self::$getBins_stmt = $this->manager->getDB()->prepare(
                'SELECT
	catalog_id,
	catalog_title
FROM
	course_catalog
');
        }

        self::$getBins_stmt->execute();

        $bins = [];
        //     	$bins[] = new banner_resource_Bin_Combined($this->manager->getCombinedBinId());
        while ($result = self::$getBins_stmt->fetch(PDO::FETCH_ASSOC)) {
            $bins[] = new banner_resource_Bin_PerCatalog(
                $this->getOsidIdFromString($result['catalog_id']),
                $result['catalog_title']);
        }

        self::$getBins_stmt->closeCursor();

        return new phpkit_resource_ArrayBinList($bins);
    }
}
