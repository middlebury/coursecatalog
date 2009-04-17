<?php
/**
 * @since 4/13/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 *  <p>This session defines methods for retrieving courses. A <code> Course 
 *  </code> is a canonical course listed in a course catalog. A <code> 
 *  CourseOffering </code> is derived from a <code> Course </code> and maps to 
 *  an offering time and registered students. </p> 
 *  
 *  <p> This lookup session defines several views: </p> 
 *  
 *  <p> 
 *  <ul>
 *      <li> comparative view: elements may be silently omitted or re-ordered 
 *      </li> 
 *      <li> plenary view: provides a complete result set or is an error 
 *      condition </li> 
 *      <li> isolated course catalog view: All course methods in this session 
 *      operate, retrieve and pertain to courses defined explicitly in the 
 *      current course catalog. Using an isolated view is useful for managing 
 *      <code> Courses </code> with the <code> CourseAdminSession. </code> 
 *      </li> 
 *      <li> federated course catalog view: All course lookup methods in this 
 *      session operate, retrieve and pertain to all courses defined in this 
 *      course catalog and any other courses implicitly available in this 
 *      course catalog through repository inheritence. </li> 
 *  </ul>
 *  The methods <code> useFederatedCourseCatalogView() </code> and <code> 
 *  useIsolatedCourseCatalogView() </code> behave as a radio group and one 
 *  should be selected before invoking any lookup methods. Courses may have an 
 *  additional records indicated by their respective record types. The record 
 *  may not be accessed through a cast of the <code> Course. </code> </p>
 * 
 * @since 4/13/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class banner_course_CombinedCourseLookupSession
    extends banner_course_CourseLookupSession
    implements osid_course_CourseLookupSession
{


    /**
     *  Gets the <code> Course </code> specified by its <code> Id. </code> In 
     *  plenary mode, the exact <code> Id </code> is found or a <code> 
     *  NOT_FOUND </code> results. Otherwise, the returned <code> Course 
     *  </code> may have a different <code> Id </code> than requested, such as 
     *  the case where a duplicate <code> Id </code> was assigned to a <code> 
     *  Course </code> and retained for compatibility. 
     *
     *  @param object osid_id_Id $courseId the <code> Id </code> of the <code> 
     *          Course </code> to rerieve 
     *  @return object osid_course_Course the returned <code> Course </code> 
     *  @throws osid_NotFoundException no <code> Course </code> found with the 
     *          given <code> Id </code> 
     *  @throws osid_NullArgumentException <code> courseId </code> is <code> 
     *          null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourse(osid_id_Id $courseId) {
    	if ($this->usesIsolatedView())
    		throw new osid_NotFoundException('This catalog does not directly contain any courses. Use useFederatedView() to access courses in child catalogs.');
    	
    	if (!isset($this->getCourse_stmt)) {
	    	$query =
"SELECT 
	SCBCRSE_SUBJ_CODE , 
	SCBCRSE_CRSE_NUMB , 
	MAX( SCBCRSE_EFF_TERM ) AS SCBCRSE_EFF_TERM , 
	SCBCRSE_COLL_CODE , 
	SCBCRSE_DIVS_CODE , 
	SCBCRSE_DEPT_CODE , 
	SCBCRSE_CSTA_CODE , 
	SCBCRSE_TITLE ,
	SCBCRSE_CREDIT_HR_HIGH
FROM 
	scbcrse
WHERE
	SCBCRSE_SUBJ_CODE = :subject_code
	AND SCBCRSE_CRSE_NUMB = :course_number
	AND SCBCRSE_CSTA_CODE NOT IN (
		'C', 'I', 'P', 'T', 'X'
	)
GROUP BY SCBCRSE_SUBJ_CODE , SCBCRSE_CRSE_NUMB
ORDER BY SCBCRSE_SUBJ_CODE ASC , SCBCRSE_CRSE_NUMB ASC	
";
			$this->getCourse_stmt = $this->manager->getDB()->prepare($query);
		}
		
		$courseIdString = $this->getDatabaseIdString($courseId, 'course/');
		if (!preg_match('/^([A-Z]{2,4})([0-9]{3,4})$/', $courseIdString, $matches))
			throw new osid_NotFoundException('Course id component \''.$courseIdString.'\' could not be converted to a subject code and number.');
		
		$this->getCourse_stmt->execute(array(
			':subject_code' =>  $matches[1],
			':course_number' => $matches[2]
		));
		$row = $this->getCourse_stmt->fetch(PDO::FETCH_ASSOC);
		$this->getCourse_stmt->closeCursor();
		
		if (!($row['SCBCRSE_SUBJ_CODE'] && $row['SCBCRSE_CRSE_NUMB']))
			throw new osid_NotFoundException("Could not find a course matching the id-component $courseIdString.");
		
		return new banner_course_Course(
					new phpkit_id_URNInetId('urn:inet:'.$this->manager->getIdAuthority().':course/'
						.$row['SCBCRSE_SUBJ_CODE'].$row['SCBCRSE_CRSE_NUMB']),
					$row['SCBCRSE_SUBJ_CODE'].$row['SCBCRSE_CRSE_NUMB'],
					'',	// Description
					$row['SCBCRSE_TITLE'], 
					$row['SCBCRSE_CREDIT_HR_HIGH']);
    }


    /**
     *  Gets a <code> CourseList </code> corresponding to the given <code> 
     *  IdList. </code> In plenary mode, the returned list contains all of the 
     *  courses specified in the <code> Id </code> list, in the order of the 
     *  list, including duplicates, or an error results if an <code> Id 
     *  </code> in the supplied list is not found or inaccessible. Otherwise, 
     *  inaccessible <code> Courses </code> may be omitted from the list and 
     *  may present the elements in any order including returning a unique 
     *  set. 
     *
     *  @param object osid_id_IdList $courseIdList the list of <code> Ids 
     *          </code> to rerieve 
     *  @return object osid_course_CourseList the returned <code> Course list 
     *          </code> 
     *  @throws osid_NotFoundException an <code> Id was </code> not found 
     *  @throws osid_NullArgumentException <code> courseIdList </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCoursesByIds(osid_id_IdList $courseIdList) {
    	$courses = array();
    	
    	while ($courseIdList->hasNext()) {
    		try {
    			$courses[] = $this->getCourse($courseIdList->getNextId());
    		} catch (osid_NotFoundException $e) {
    			if ($this->usesPlenaryView())
    				throw $e;
    		} catch (osid_PermissionDeniedException $e) {
    			if ($this->usesPlenaryView())
    				throw $e;
    		}
    	}
    	
    	return new phpkit_course_ArrayCourseList($courses);
    }


    /**
     *  Gets a <code> CourseList </code> corresponding to the given course 
     *  genus <code> Type </code> which does not include courses of types 
     *  derived from the specified <code> Type. </code> In plenary mode, the 
     *  returned list contains all known courses or an error results. 
     *  Otherwise, the returned list may contain only those courses that are 
     *  accessible through this session. In both cases, the order of the set 
     *  is not specified. 
     *
     *  @param object osid_type_Type $courseGenusType a course genus type 
     *  @return object osid_course_CourseList the returned <code> Course list 
     *          </code> 
     *  @throws osid_NullArgumentException <code> courseGenusType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCoursesByGenusType(osid_type_Type $courseGenusType) {
    	if ($courseGenusType->isEqual(new phpkit_type_URNInetType("urn:inet:osid.org:genera:none")))
    		return $this->getCourses();
    	else
    		return new phpkit_EmptyList;
    }


    /**
     *  Gets a <code> CourseList </code> corresponding to the given course 
     *  genus <code> Type </code> and include any additional courses with 
     *  genus types derived from the specified <code> Type. </code> In plenary 
     *  mode, the returned list contains all known courses or an error 
     *  results. Otherwise, the returned list may contain only those courses 
     *  that are accessible through this session. In both cases, the order of 
     *  the set is not specified. 
     *
     *  @param object osid_type_Type $courseGenusType a course genus type 
     *  @return object osid_course_CourseList the returned <code> Course list 
     *          </code> 
     *  @throws osid_NullArgumentException <code> courseGenusType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCoursesByParentGenusType(osid_type_Type $courseGenusType) {
    	return $this->getCoursesByGenusType($courseGenusType);
    }


    /**
     *  Gets a <code> CourseList </code> containing the given course record 
     *  <code> Type. </code> In plenary mode, the returned list contains all 
     *  known courses or an error results. Otherwise, the returned list may 
     *  contain only those courses that are accessible through this session. 
     *  In both cases, the order of the set is not specified. 
     *
     *  @param object osid_type_Type $courseRecordType a course record type 
     *  @return object osid_course_CourseList the returned <code> CourseList 
     *          list </code> 
     *  @throws osid_NullArgumentException <code> courseRecordType </code> is 
     *          <code> null </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCoursesByRecordType(osid_type_Type $courseRecordType) {
    	return new phpkit_EmptyList;
    }


    /**
     *  Gets all <code> Courses. </code> In plenary mode, the returned list 
     *  contains all known courses or an error results. Otherwise, the 
     *  returned list may contain only those courses that are accessible 
     *  through this session. In both cases, the order of the set is not 
     *  specifed. 
     *
     *  @return object osid_course_CourseList a list of <code> Courses </code> 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_PermissionDeniedException authorization failure 
     *  @throws osid_IllegalStateException this session has been closed 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getCourses() {
    	return new banner_course_CombinedAllCoursesList(
    		$this->manager->getDB(), 
    		$this->manager->getIdAuthority(),
    		'course/');
    }

}
