<?php
/**
 * @since 4/10/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 * This is an abstract course session that includes much of the common methods needed
 * by all course sessions in this package.
 *
 * @since 4/10/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class banner_AbstractSession extends phpkit_AbstractOsidSession
{
    /**
     * Constructor.
     *
     * @return void
     *
     * @since 4/10/09
     */
    public function __construct(banner_ManagerInterface $manager, $prefix)
    {
        $this->manager = $manager;
        $this->idPrefix = (string) $prefix;
    }

    /**
     * @var bool;
     *
     * @since 4/10/09
     */
    private $plenaryView = true;

    /**
     *  The returns from the lookup methods may omit or translate elements
     *  based on this session, such as authorization, and not result in an
     *  error. This view is used when greater interoperability is desired at
     *  the expense of precision.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function useComparativeView()
    {
        $this->plenaryView = false;
    }

    /**
     *  A complete view of the <code> CourseCatalog </code> returns is
     *  desired. Methods will return what is requested or result in an error.
     *  This view is used when greater precision is desired at the expense of
     *  interoperability.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function usePlenaryView()
    {
        $this->plenaryView = true;
    }

    /**
     * Answer the value of the view state.
     *
     * @return bool
     *
     * @since 4/10/09
     */
    protected function usesPlenaryView()
    {
        return $this->plenaryView;
    }

    /**
     * @var bool;
     *
     * @since 4/10/09
     */
    private $isolatedView = true;

    /**
     *  Federates the view for methods in this session. A federated view will
     *  include courses in catalogs which are children of this catalog in the
     *  course catalog hierarchy.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function useFederatedView()
    {
        $this->isolatedView = false;
    }

    /**
     *  Isolates the view for methods in this session. An isolated view
     *  restricts retrievals to this course catalog only.
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function useIsolatedView()
    {
        $this->isolatedView = true;
    }

    /**
     * Answer the value of the view state.
     *
     * @return bool
     *
     * @since 4/10/09
     */
    protected function usesIsolatedView()
    {
        return $this->isolatedView;
    }

    /**
     * @var banner_course_CourseManagerInterface;
     *
     * @since 4/10/09
     */
    protected $manager;

    /**
     * @var string;
     *
     * @since 4/10/09
     */
    private $idPrefix;

    /**
     * Answer a database-id for an Id object passed or throw an osid_NotFoundException
     * if the Id is not one that this implementation might know about.
     *
     * @param object osid_id_Id $id
     * @param string optional $prefix
     *
     * @return string
     *
     * @since 4/10/09
     */
    public function getDatabaseIdString(osid_id_Id $id, $prefix = null)
    {
        if ('urn' != $id->getIdentifierNamespace()) {
            throw new osid_NotFoundException('I only know about Ids in the urn namespace, got: '.$id->getIdentifierNamespace());
        }

        if ($id->getAuthority() != $this->manager->getIdAuthority()) {
            throw new osid_NotFoundException('I only know about Ids under the '.$this->manager->getIdAuthority().' authority, got: '.$id->getAuthority());
        }

        if (null === $prefix) {
            $prefix = $this->idPrefix;
        } else {
            $prefix = (string) $prefix;
        }

        if (!str_starts_with($id->getIdentifier(), $prefix)) {
            throw new osid_NotFoundException('I only know about Ids with the '.$prefix.' prefix, got: '.$id->getIdentifier());
        }

        return substr($id->getIdentifier(), strlen($prefix));
    }

    /**
     * Answer an Id object from a string database Id.
     *
     * @param string $databaseId
     * @param string optional $prefix
     *
     * @return osid_id_Id
     *
     * @since 4/10/09
     */
    public function getOsidIdFromString($databaseId, $prefix = null)
    {
        if (null === $prefix) {
            $prefix = $this->idPrefix;
        }

        return new phpkit_id_Id($this->manager->getIdAuthority(), 'urn', $prefix.$databaseId);
    }

    /**
     * Answer the term code from a Term id object.
     *
     * @return string
     *
     * @throws an osid_NotFoundException if the id cannot match
     *
     * @since 4/16/09
     */
    public function getTermCodeFromTermId(osid_id_Id $id)
    {
        $string = $this->getDatabaseIdString($id, 'term.');
        if (!preg_match('#^([0-9]{6})$#', $string)) {
            throw new osid_NotFoundException("String '$string' cannot be broken into a term-code");
        }

        return $string;
    }

    /**
     * Answer the term code from an id object.
     *
     * @return string
     *
     * @throws an osid_NotFoundException if the id cannot match
     *
     * @since 4/16/09
     */
    public function getTermCodeFromOfferingId(osid_id_Id $id)
    {
        $string = $this->getDatabaseIdString($id, 'section.');
        if (!preg_match('#^([0-9]{6})\.([0-9]{1,5})$#', $string, $matches)) {
            throw new osid_NotFoundException("String '$string' cannot be broken into a term-code and CRN.");
        }

        return $matches[1];
    }

    /**
     * Answer the CRN from an id object.
     *
     * @return string
     *
     * @throws an osid_NotFoundException if the id cannot match
     *
     * @since 4/16/09
     */
    public function getCrnFromOfferingId(osid_id_Id $id)
    {
        $string = $this->getDatabaseIdString($id, 'section.');
        if (!preg_match('#^([0-9]{6})\.([0-9]{1,5})$#', $string, $matches)) {
            throw new osid_NotFoundException("String '$string' cannot be broken into a term-code and CRN.");
        }

        return $matches[2];
    }

    /**
     * Answer a course subject code from an id.
     *
     * @return string
     *
     * @since 4/17/09
     */
    public function getSubjectFromCourseId(osid_id_Id $id)
    {
        $string = $this->getDatabaseIdString($id, 'course.');
        if (!preg_match('#^([A-Z]{2,4})([A-Z0-9]{3,5})$#i', $string, $matches)) {
            throw new osid_NotFoundException("String '$string' cannot be broken into a subject-code and Number.");
        }

        return $matches[1];
    }

    /**
     * Answer a course number from an id.
     *
     * @return string
     *
     * @since 4/17/09
     */
    public function getNumberFromCourseId(osid_id_Id $id)
    {
        $string = $this->getDatabaseIdString($id, 'course.');
        if (!preg_match('#^([A-Z]{2,4})([A-Z0-9]{3,5})$#i', $string, $matches)) {
            throw new osid_NotFoundException("String '$string' cannot be broken into a subject-code and Number.");
        }

        return $matches[2];
    }

    /**
     * Answer the manager for this session.
     *
     * @return osid_course_CourseManager
     *
     * @since 10/16/09
     */
    public function getManager()
    {
        return $this->manager;
    }
}
