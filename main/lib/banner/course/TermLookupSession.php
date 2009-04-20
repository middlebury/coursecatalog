<?php
/**
 * @since 4/14/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 *  <p>This session provides methods for retrieving <code> Term </code> 
 *  objects. The <code> Term </code> represents a time period in which courses 
 *  are offered. </p> 
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
 * 
 * @package org.osid.course
 */
class banner_course_TermLookupSession
    extends banner_course_AbstractCourseSession
    implements osid_course_TermLookupSession
{
	
	/**
	 * Constructor
	 * 
	 * @param banner_course_CourseManagerInterface $manager
	 * @param optional osid_id_Id $catalogId
	 * @return void
	 * @access public
	 * @since 4/10/09
	 */
	public function __construct (banner_course_CourseManagerInterface $manager, osid_id_Id $catalogId = null) {
		parent::__construct($manager, 'term/');
		
		if (is_null($catalogId))
			$this->catalogId = $manager->getCombinedCatalogId();
		else
			$this->catalogId = $catalogId;
	}

    /**
     *  Gets the <code> CourseCatalog </code> <code> Id </code> associated 
     *  with this session. 
     *
     *  @return object osid_id_Id the <code> CourseCatalog Id </code> 
     *          associated with this session 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogId() {
    	return $this->catalogId;
    }


    /**
     *  Gets the <code> CourseCatalog </code> associated with this session. 
     *
     *  @return object osid_course_CourseCatalog the course catalog 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalog() {
    	if (!isset($this->catalog)) {
	    	$lookup = $this->manager->getCourseCatalogLookupSession();
			$lookup->usePlenaryView();
			$this->catalog = $lookup->getCourseCatalog($this->getCourseCatalogId());
		}
    	return $this->catalog;
    }


    /**
     *  Tests if this user can perform <code> Term </code> lookups. A return 
     *  of true does not guarantee successful authorization. A return of false 
     *  indicates that it is known all methods in this session will result in 
     *  a <code> PERMISSION_DENIED. </code> This is intended as a hint to an 
     *  application that may opt not to offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupTerms() {
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
    public function useComparativeTermView() {
    	$this->useComparativeView();
    }


    /**
     *  A complete view of the <code> Term </code> returns is desired. Methods 
     *  will return what is requested or result in an error. This view is used 
     *  when greater precision is desired at the expense of interoperability. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function usePlenaryTermView() {
    	$this->usePlenaryView();
    }


    /**
     *  Federates the view for methods in this session. A federated view will 
     *  include terms in course catalogs which are children of this course 
     *  catalog in the course catalog hierarchy. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useFederatedCourseCatalogView() {
    	$this->useFederatedView();
    }


    /**
     *  Isolates the view for methods in this session. An isolated view 
     *  restricts lookups to this course catalog only. 
     *
     *  @compliance mandatory This method is must be implemented. 
     */
    public function useIsolatedCourseCatalogView() {
    	$this->useIsolateView();
    }


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
     *  @return object osid_course_Term the term 
     *  @throws osid_NotFoundException <code> termId </code> not found 
     *  @throws osid_NullArgumentException <code> termId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function getTerm(osid_id_Id $termId) {
    	$idString = $this->getDatabaseIdString($termId, 'term/');
    	if (!preg_match('/^([0-9]{6})$/', $idString))
			throw new osid_NotFoundException('Term id component \''.$idString.'\' could not be converted to a term code.');
			
		if (!isset($this->getTerm_stmt)) {
	    	$query =
"SELECT 
    section_coll_code,
    STVTERM_CODE,
	STVTERM_DESC,
	STVTERM_START_DATE,
	STVTERM_END_DATE
FROM 
	course_section_college
	INNER JOIN stvterm ON section_term_code = STVTERM_CODE
	
WHERE 
	STVTERM_CODE = :term_code
	AND section_coll_code IN (
		SELECT
			coll_code
		FROM
			course_catalog_college
		WHERE
			".$this->getCatalogWhereTerms()."
	)
GROUP BY section_term_code
ORDER BY STVTERM_CODE DESC
";
			$this->getTerm_stmt = $this->manager->getDB()->prepare($query);
		}
		
		$parameters = array_merge(
			array(
				':term_code' => $idString
			),
			$this->getCatalogParameters());
		$this->getTerm_stmt->execute($parameters);
		
		$row = $this->getTerm_stmt->fetch(PDO::FETCH_ASSOC);
		$this->getTerm_stmt->closeCursor();
		
		if (!$row['STVTERM_CODE'])
			throw new osid_NotFoundException("Could not find a term matching the term code $idString.");
		
		return new banner_course_Term(
					$this->getOsidIdFromString($row['STVTERM_CODE'], 'term/'),
					$row['STVTERM_DESC'],
					$row['STVTERM_START_DATE'], 
					$row['STVTERM_END_DATE']);
    }
    
    /**
	 * Answer the catalog where terms
	 * 
	 * @return string
	 * @access private
	 * @since 4/20/09
	 */
	private function getCatalogWhereTerms () {
		if (is_null($this->catalogId) || $this->catalogId->isEqual($this->getCombinedCatalogId()))
			return 'TRUE';
		else
			return 'catalog_id = :catalog_id';
	}
	
	/**
	 * Answer the input parameters
	 * 
	 * @return array
	 * @access private
	 * @since 4/17/09
	 */
	private function getCatalogParameters () {
		$params = array();
		if (!is_null($this->catalogId) && !$this->catalogId->isEqual($this->getCombinedCatalogId()))
			$params[':catalog_id'] = $this->getDatabaseIdString($this->catalogId, 'catalog/');
		return $params;
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
     *  @return object osid_course_TermList the returned <code> Term list 
     *          </code> 
     *  @throws osid_NotFoundException an <code> Id was </code> not found 
     *  @throws osid_NullArgumentException <code> termIdList </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermsByIds(osid_id_IdList $termIdList) {
    	$terms = array();
    	
    	while ($termIdList->hasNext()) {
    		try {
    			$terms[] = $this->getTerm($termIdList->getNextId());
    		} catch (osid_NotFoundException $e) {
    			if ($this->usesPlenaryView())
    				throw $e;
    		} catch (osid_PermissionDeniedException $e) {
    			if ($this->usesPlenaryView())
    				throw $e;
    		}
    	}
    	
    	return new phpkit_course_ArrayCourseList($terms);
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
     *  @return object osid_course_TermList the returned <code> Term list 
     *          </code> 
     *  @throws osid_NullArgumentException <code> termGenusType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermsByGenusType(osid_type_Type $termGenusType) {
    	if ($termGenusType->isEqual(new phpkit_type_URNInetType("urn:inet:osid.org:genera:none")))
    		return $this->getTerms();
    	else
    		return new phpkit_EmptyList;
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
     *  @return object osid_course_TermList the returned <code> Term list 
     *          </code> 
     *  @throws osid_NullArgumentException <code> termGenusType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermsByParentGenusType(osid_type_Type $termGenusType) {
    	return $this->getTermsByGenusType($termGenusType);
    }


    /**
     *  Gets a <code> TermList </code> containing the given term record <code> 
     *  Type. </code> In plenary mode, the returned list contains all known 
     *  terms or an error results. Otherwise, the returned list may contain 
     *  only those terms that are accessible through this session. In both 
     *  cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $termRecordType a term record type 
     *  @return object osid_course_TermList the returned <code> Term list 
     *          </code> 
     *  @throws osid_NullArgumentException <code> termRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTermsByRecordType(osid_type_Type $termRecordType) {
    	return new phpkit_EmptyList;
    }


    /**
     *  Gets all <code> Terms. </code> In plenary mode, the returned list 
     *  contains all known terms or an error results. Otherwise, the returned 
     *  list may contain only those terms that are accessible through this 
     *  session. In both cases, the order of the set is not specified. 
     *
     *  @return object osid_course_TermList a list of <code> Terms </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getTerms() {
    	return new banner_course_AllTermsList(
    		$this->manager->getDB(),
    		$this);
    }

}
