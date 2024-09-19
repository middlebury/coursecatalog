<?php
/**
 * @since 4/13/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * <p>A <code> CourseCatalog </code> represents a collection of courses,
 *  course offerings and terms. </p>.
 *
 * @since 4/13/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class banner_course_CourseCatalog_Combined extends phpkit_AbstractOsidCatalog implements osid_course_CourseCatalog
{
    /**
     * Constructor.
     *
     * @return void
     *
     * @since 4/13/09
     */
    public function __construct(osid_id_Id $id)
    {
        parent::__construct();
        $this->setId($id);
        $this->setDisplayName('All');
        $this->setDescription('All courses in the '.$id->getAuthority().' banner database.');
    }

    /**
     *  Gets the record corresponding to the given <code> CourseCatalog
     *  </code> record <code> Type. </code> This method must be used to
     *  retrieve an object implementing the requested record interface along
     *  with all of its ancestor interfaces. The <code>
     *  courseCatalogRecordType </code> may be the <code> Type </code>
     *  returned in <code> getRecordTypes() </code> or any of its parents in a
     *  <code> Type </code> hierarchy where <code>
     *  hasRecordType(courseCatalogRecordType) </code> is <code> true </code>
     *  .
     *
     *  @param object osid_type_Type $courseCatalogRecordType the type of
     *          course catalog record to retrieve
     *
     * @return object osid_course_CourseCatalogRecord the course catalog
     *                record
     *
     * @throws osid_NullArgumentException <code> courseCatalogRecordType
     *                                           </code> is <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure occurred
     * @throws osid_UnsupportedException <code>
     *                                           hasRecordType(courseCatalogRecordType) </code> is <code> false
     *                                           </code>
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getCourseCatalogRecord(osid_type_Type $courseCatalogRecordType)
    {
        throw new osid_UnsupportedException('The type passed is not supported.');
    }
}
