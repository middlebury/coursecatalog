<?php
/**
 * @since 4/14/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

/**
 *  <p>This session provides methods for retrieving <code> Term </code>
 *  objects. The <code> Term </code> represents a time period in which courses
 *  are offered. </p>.
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
 *      <li> isolated course catalog view: All term methods in this session
 *      operate, retrieve and pertain to terms defined explicitly in the
 *      current course catalog. Using an isolated view is useful for managing
 *      <code> Terms </code> with the <code> TermAdminSession. </code> </li>
 *      <li> federated course catalog view: All term methods in this session
 *      operate, retrieve and pertain to all terms defined in this course
 *      catalog and any other terms implicitly available in this course
 *      catalog through course catalog inheritence. </li>
 *  </ul>
 *  Generally, the comparative view should be used for most applications as it
 *  permits operation even if there is data that cannot be accessed. The
 *  methods <code> useFederatedCourseCatalogView() </code> and <code>
 *  useIsolatedCourseCatalogView() </code> behave as a radio group and one
 *  should be selected before invoking any lookup methods. </p>
 */
class banner_course_Term_Lookup_CombinedSession extends banner_course_Term_Lookup_Session
{
    /**
     *  Gets the <code> Term </code> specified by its <code> Id. </code> In
     *  plenary mode, the exact <code> Id </code> is found or a <code>
     *  NOT_FOUND </code> results. Otherwise, the returned <code> Term </code>
     *  may have a different <code> Id </code> than requested, such as the
     *  case where a duplicate <code> Id </code> was assigned to a <code> Term
     *  </code> and retained for compatibility.
     *
     *  @param object osid_id_Id $termId <code> Id </code> of the <code> Term
     *          </code>
     *
     * @return object osid_course_Term the term
     *
     * @throws osid_NotFoundException <code>     termId </code> not found
     * @throws osid_NullArgumentException <code> termId </code> is <code>
     *                                           null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method is must be implemented.
     */
    public function getTerm(osid_id_Id $termId)
    {
        if ($this->usesIsolatedView()) {
            throw new osid_NotFoundException('This catalog does not directly contain any terms. Use useFederatedView() to access terms in child catalogs.');
        }

        return parent::getTerm($termId);
    }

    /**
     *  Gets a <code> TermList </code> corresponding to the given <code>
     *  IdList. </code> In plenary mode, the returned list contains all of the
     *  terms specified in the <code> Id </code> list, in the order of the
     *  list, including duplicates, or an error results if an <code> Id
     *  </code> in the supplied list is not found or inaccessible. Otherwise,
     *  inaccessible <code> Terms </code> may be omitted from the list and may
     *  present the elements in any order including returning a unique set.
     *
     *  @param object osid_id_IdList $termIdList the list of <code> Ids
     *          </code> to rerieve
     *
     * @return object osid_course_TermList the returned <code> Term list
     *                </code>
     *
     * @throws osid_NotFoundException            an <code> Id was </code> not found
     * @throws osid_NullArgumentException <code> termIdList </code> is <code>
     *                                           null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTermsByIds(osid_id_IdList $termIdList)
    {
        if ($this->usesIsolatedView()) {
            if ($this->usesPlenaryView()) {
                throw new osid_NotFoundException('This catalog does not directly contain any terms. Use useFederatedView() to access terms in child catalogs.');
            } else {
                return new phpkit_EmptyList();
            }
        }

        return parent::getTermsByIds($termIdList);
    }

    /**
     *  Gets a <code> TermList </code> corresponding to the given term genus
     *  <code> Type </code> which does not include terms of genus types
     *  derived from the specified <code> Type. </code> In plenary mode, the
     *  returned list contains all known terms or an error results. Otherwise,
     *  the returned list may contain only those terms that are accessible
     *  through this session. In both cases, the order of the set is not
     *  specified.
     *
     *  @param object osid_type_Type $termGenusType a term genus type
     *
     * @return object osid_course_TermList the returned <code> Term list
     *                </code>
     *
     * @throws osid_NullArgumentException <code> termGenusType </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTermsByGenusType(osid_type_Type $termGenusType)
    {
        if ($this->usesIsolatedView()) {
            return new phpkit_EmptyList();
        }

        return parent::getTermsByGenusType($termGenusType);
    }

    /**
     *  Gets a <code> TermList </code> corresponding to the given term genus
     *  <code> Type </code> and include any additional terms with genus types
     *  derived from the specified <code> Type. </code> In plenary mode, the
     *  returned list contains all known terms or an error results. Otherwise,
     *  the returned list may contain only those terms that are accessible
     *  through this session. In both cases, the order of the set is not
     *  specified.
     *
     *  @param object osid_type_Type $termGenusType a term genus type
     *
     * @return object osid_course_TermList the returned <code> Term list
     *                </code>
     *
     * @throws osid_NullArgumentException <code> termGenusType </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTermsByParentGenusType(osid_type_Type $termGenusType)
    {
        if ($this->usesIsolatedView()) {
            return new phpkit_EmptyList();
        }

        return parent::getTermsByParentGenusType($termGenusType);
    }

    /**
     *  Gets a <code> TermList </code> containing the given term record <code>
     *  Type. </code> In plenary mode, the returned list contains all known
     *  terms or an error results. Otherwise, the returned list may contain
     *  only those terms that are accessible through this session. In both
     *  cases, the order of the set is not specified.
     *
     *  @param object osid_type_Type $termRecordType a term record type
     *
     * @return object osid_course_TermList the returned <code> Term list
     *                </code>
     *
     * @throws osid_NullArgumentException <code> termRecordType </code> is
     *                                           <code> null </code>
     * @throws osid_OperationFailedException     unable to complete request
     * @throws osid_PermissionDeniedException    authorization failure
     * @throws osid_IllegalStateException        this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTermsByRecordType(osid_type_Type $termRecordType)
    {
        if ($this->usesIsolatedView()) {
            return new phpkit_EmptyList();
        }

        return parent::getTermsByRecordType($termRecordType);
    }

    /**
     *  Gets all <code> Terms. </code> In plenary mode, the returned list
     *  contains all known terms or an error results. Otherwise, the returned
     *  list may contain only those terms that are accessible through this
     *  session. In both cases, the order of the set is not specified.
     *
     * @return object osid_course_TermList a list of <code> Terms </code>
     *
     * @throws osid_OperationFailedException  unable to complete request
     * @throws osid_PermissionDeniedException authorization failure
     * @throws osid_IllegalStateException     this session has been closed
     *
     *  @compliance mandatory This method must be implemented.
     */
    public function getTerms()
    {
        if ($this->usesIsolatedView()) {
            return new phpkit_EmptyList();
        }

        return parent::getTerms();
    }
}
