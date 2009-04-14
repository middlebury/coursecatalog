<?php
/**
 * @since 4/13/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * An iterator for retrieving all courses from a catalog
 * 
 * @since 4/13/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class banner_course_AllCoursesList
	extends phpkit_PdoQueryList
	implements osid_course_CourseList
{

	/**
	 * Constructor
	 * 
	 * @param PDO $db
	 * @param string $catalogDatabaseId
	 * @return void
	 * @access public
	 * @since 4/13/09
	 */
	public function __construct (PDO $db, $catalogDatabaseId, $idAuthority, $idPrefix) {
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
	SCBCRSE_CSTA_CODE NOT IN (
		'C', 'I', 'P', 'T', 'X'
	)
	AND SCBCRSE_COLL_CODE IN (
		SELECT
			coll_code
		FROM
			course_catalog_college
		WHERE
			catalog_id = :catalog_id
	)
GROUP BY SCBCRSE_SUBJ_CODE , SCBCRSE_CRSE_NUMB
ORDER BY SCBCRSE_SUBJ_CODE ASC , SCBCRSE_CRSE_NUMB ASC	
";
		parent::__construct($db, $query, array(':catalog_id' => $catalogDatabaseId));
		$this->idAuthority = $idAuthority;
		$this->idPrefix = $idPrefix;
	}
		
	/**
	 * Answer an object from a result row
	 * 
	 * @param array $row
	 * @return mixed
	 * @access protected
	 * @since 4/13/09
	 */
	protected function getObjectFromRow (array $row) {
		return new banner_course_Course(
					new phpkit_id_URNInetId('urn:inet:'.$this->idAuthority.':'.$this->idPrefix
						.$row['SCBCRSE_SUBJ_CODE'].$row['SCBCRSE_CRSE_NUMB']),
					$row['SCBCRSE_SUBJ_CODE'].$row['SCBCRSE_CRSE_NUMB'],
					'',	// Description
					$row['SCBCRSE_TITLE'], 
					$row['SCBCRSE_CREDIT_HR_HIGH']);
	}
	
	 /**
     *  Gets the next <code> Course </code> in this list. 
     *
     *  @return object osid_course_Course the next <code> Course </code> in 
     *          this list. The <code> hasNext() </code> method should be used 
     *          to test that a next <code> Course </code> is available before 
     *          calling this method. 
     *  @throws osid_IllegalStateException no more elements available in this 
     *          list or this list has been closed 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNextCourse() {
    	return $this->next();
    }


    /**
     *  Gets the next set of <code> Course </code> elements in this list. The 
     *  specified amount must be less than or equal to the return from <code> 
     *  available(). </code> 
     *
     *  @param integer $n the number of <code> Course </code> elements 
     *          requested which must be less than or equal to <code> 
     *          available() </code> 
     *  @return array of osid_course_Course objects  an array of <code> Course 
     *          </code> elements. <code> </code> The length of the array is 
     *          less than or equal to the number specified. 
     *  @throws osid_IllegalStateException no more elements available in this 
     *          list or this list has been closed 
     *  @throws osid_OperationFailedException unable to complete request 
     *  @throws osid_NullArgumentException null argument provided 
     *  @compliance mandatory This method must be implemented. 
     */
    public function getNextCourses($n) {
    	return $this->getNext($n);
    }
    
}

?>