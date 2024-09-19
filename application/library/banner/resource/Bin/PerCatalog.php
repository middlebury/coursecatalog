<?php
/**
 * @since 5/20/10
 *
 * @copyright Copyright &copy; 2010, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 *  <p>An inventory defines a collection of resources. </p>.
 */
class banner_resource_Bin_PerCatalog extends phpkit_AbstractOsidCatalog implements osid_resource_Bin
{
    /**
     * Constructor.
     *
     * @param string $displayName
     *
     * @return void
     *
     * @since 4/13/09
     */
    public function __construct(osid_id_Id $id, $displayName)
    {
        parent::__construct();
        $this->setId($id);
        $this->setDisplayName($displayName);
        $this->setDescription('');
    }

    /**
     *  Gets the record corresponding to the given <code> Bin </code> record
     *  <code> Type. </code> This method must be used to retrieve an object
     *  implementing the requested record interface along with all of its
     *  ancestor interfaces. The <code> binRecordType </code> may be the
     *  <code> Type </code> returned in <code> getRecordTypes() </code> or any
     *  of its parents in a <code> Type </code> hierarchy where <code>
     *  hasRecordType(binRecordType) </code> is <code> true </code> .
     *
     *  @param object osid_type_Type $binRecordType the bin record type
     *
     * @return object osid_resource_BinRecord the bin record
     *
     * @throws osid_NullArgumentException <code> binRecordType </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure occurred
     * @throws osid_UnsupportedException <code>  hasRecordType(binRecordType)
     *                                           </code> is <code> false </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getBinRecord(osid_type_Type $binRecordType)
    {
        throw new osid_UnsupportedException('The type passed is not supported.');
    }
}
