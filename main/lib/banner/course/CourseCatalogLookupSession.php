<?php
/**
 * @since 4/9/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * This class provides support for lookups of the various catalogs listed in banner.
 * For example, Undergraduate, Breadload School of English, etc.
 *
 * <p>This session provides methods for retrieving <code> CourseCatalog 
 *  </code> objects. The <code> CourseCatalog </code> represents a collection 
 *  of courses, offerings and tems. </p> 
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
 *  example, a browsing application may only need to examine the <code> 
 *  CourseCatalogs </code> it can access, without breaking execution. However, 
 *  an assessment may only be useful if all <code> CourseCatalogs </code> 
 *  referenced by it are available, and a test-taking application may 
 *  sacrifice some interoperability for the sake of precision. </p>
 * 
 * @since 4/9/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class banner_course_CourseCatalogLookupSession
	extends banner_course_AbstractCourseSession
	implements osid_course_CourseCatalogLookupSession
{
	
	/**
	 * Constructor
	 * 
	 * @param PDO $db
	 * @return void
	 * @access public
	 * @since 4/10/09
	 */
	public function __construct (PDO $db, $idAuthority) {
		parent::construct($db, $idAuthority, 'catalog/');
	}
		
	/**
     *  Tests if this user can perform <code> CourseCatalog </code> lookups. A 
     *  return of true does not guarantee successful authorization. A return 
     *  of false indicates that it is known all methods in this session will 
     *  result in a <code> PERMISSION_DENIED. </code> This is intended as a 
     *  hint to an application that may opt not to offer lookup operations to 
     *  unauthorized users. 
     *
     *  @return boolean <code> false </code> if lookup methods are not 
     *          authorized, <code> true </code> otherwise 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function canLookupCourseCatalog() {
    	return true;
    }
    

    /**
     *  Gets the <code> CourseCatalog </code> specified by its <code> Id. 
     *  </code> In plenary mode, the exact <code> Id </code> is found or a 
     *  <code> NOT_FOUND </code> results. Otherwise, the returned <code> 
     *  CourseCatalog </code> may have a different <code> Id </code> than 
     *  requested, such as the case where a duplicate <code> Id </code> was 
     *  assigned to a <code> CourseCatalog </code> and retained for 
     *  compatibility. 
     *
     *  @param object osid_id_Id $courseCatalogId <code> Id </code> of the 
     *          <code> CourseCatalog </code> 
     *  @return object osid_course_CourseCatalog the course catalog 
     *  @throws osid_NotFoundException <code> courseCatalogId </code> not 
     *          found 
     *  @throws osid_NullArgumentException <code> courseCatalogId </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method is must be implemented. 
     */
    public function getCourseCatalog(osid_id_Id $courseCatalogId) {
    	if (!isset($this->getCatalogById_stmt)) {
    		$this->getCatalogById_stmt = $this->db->prepare(
"SELECT
	catalog_id,
	catalog_title
FROM
	 course_catalog
WHERE
	catalog_id = :catalog_id
");
    	}
    	
    	$this->getCatalogById_stmt->execute(array(':catalog_id' => $this->getDatabaseId($courseCatalogId)));
    	
 		if (!$this->getCatalogById_stmt->rowCount())
 			throw new osid_NotFoundException("Catalog id not found.");
    	
    	$result = $this->getCatalogById_stmt->fetchAll(PDO::FETCH_ASSOC);
    	
    	// @todo - Finish this method.
    	throw new osid_OperationFailedException("I haven't yet finished implementing this method.");
    
    }


    /**
     *  Gets a <code> CourseCatalogList </code> corresponding to the given 
     *  <code> IdList. </code> In plenary mode, the returned list contains all 
     *  of the repositories specified in the <code> Id </code> list, in the 
     *  order of the list, including duplicates, or an error results if an 
     *  <code> Id </code> in the supplied list is not found or inaccessible. 
     *  Otherwise, inaccessible <code> CourseCatalogs </code> may be omitted 
     *  from the list and may present the elements in any order including 
     *  returning a unique set. 
     *
     *  @param object osid_id_IdList $courseCatalogIdList the list of <code> 
     *          Ids </code> to rerieve 
     *  @return object osid_course_CourseCatalogList the returned <code> 
     *          CourseCatalog list </code> 
     *  @throws osid_NotFoundException an <code> Id was </code> not found 
     *  @throws osid_NullArgumentException <code> courseCatalogIdList </code> 
     *          is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogsByIds(osid_id_IdList $courseCatalogIdList) {
    	throw new osid_OperationFailedException('Unimplemented');
    }


    /**
     *  Gets a <code> CourseCatalogList </code> corresponding to the given 
     *  course catalog genus <code> Type </code> which does not include 
     *  repositories of types derived from the specified <code> Type. </code> 
     *  In plenary mode, the returned list contains all known course catalogs 
     *  or an error results. Otherwise, the returned list may contain only 
     *  those course catalogs that are accessible through this session. In 
     *  both cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $courseCatalogGenusType a course catalog 
     *          genus type 
     *  @return object osid_course_CourseCatalogList the returned <code> 
     *          CourseCatalog list </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogGenusType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogsByGenusType(osid_type_Type $courseCatalogGenusType) {
    	throw new osid_OperationFailedException('Unimplemented');
    }


    /**
     *  Gets a <code> CourseCatalogList </code> corresponding to the given 
     *  course catalog genus <code> Type </code> and include any additional 
     *  course catalogs with genus types derived from the specified <code> 
     *  Type. </code> In plenary mode, the returned list contains all known 
     *  course catalogs or an error results. Otherwise, the returned list may 
     *  contain only those course catalogs that are accessible through this 
     *  session. In both cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $courseCatalogGenusType a course catalog 
     *          genus type 
     *  @return object osid_course_CourseCatalogList the returned <code> 
     *          Course Catalog list </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogGenusType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogsByParentGenusType(osid_type_Type $courseCatalogGenusType) {
    	throw new osid_OperationFailedException('Unimplemented');
    }


    /**
     *  Gets a <code> CourseCatalogList </code> containing the given course 
     *  catalog record <code> Type. </code> In plenary mode, the returned list 
     *  contains all known course catalogs or an error results. Otherwise, the 
     *  returned list may contain only those course catalogs that are 
     *  accessible through this session. In both cases, the order of the set 
     *  is not specified. 
     *
     *  @param object osid_type_Type $courseCatalogRecordType a course catalog 
     *          record type 
     *  @return object osid_course_CourseCatalogList the returned <code> 
     *          CourseCatalog list </code> 
     *  @throws osid_NullArgumentException <code> courseCatalogRecordType 
     *          </code> is <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogsByRecordType(osid_type_Type $courseCatalogRecordType) {
    	throw new osid_OperationFailedException('Unimplemented');
    }


    /**
     *  Gets all <code> CourseCatalogs. </code> In plenary mode, the returned 
     *  list contains all known course catalogs or an error results. 
     *  Otherwise, the returned list may contain only those course catalogs 
     *  that are accessible through this session. In both cases, the order of 
     *  the set is not specified. 
     *
     *  @return object osid_course_CourseCatalogList a list of <code> 
     *          CourseCatalogs </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourseCatalogs() {
    	throw new osid_OperationFailedException('Unimplemented');
    }
}

?>