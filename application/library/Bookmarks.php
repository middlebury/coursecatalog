<?php
/**
 * @since 7/29/10
 * @package catalog.bookmarks
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * The Bookmarks class provides access to a list of courses bookmarked by a given
 * user.
 * 
 * @since 7/29/10
 * @package catalog.bookmarks
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Bookmarks {
		
	/**
	 * Constructor
	 * 
	 * @param Zend_Db_Adapter_Abstract $db
	 * @param string $userId
	 * @return void
	 * @access public
	 * @since 7/29/10
	 */
	public function __construct (Zend_Db_Adapter_Abstract $db, $userId, osid_course_CourseManager $courseManager) {
		if (!strlen($userId))
			throw new InvalidArgumentException('No $userId passed.');
		
		$this->db = $db;
		$this->userId = $userId;
		$this->courseManager = $courseManager;
	}
	
	private $db;
	private $userId;
	
	
	/**
	 * Add a bookmark
	 * 
	 * @param osid_id_Id $courseId
	 * @return void
	 * @access public
	 * @since 7/29/10
	 */
	public function add (osid_id_Id $courseId) {
		$stmt = $this->db->prepare("INSERT INTO user_savedcourses (user_id, course_id_keyword, course_id_authority, course_id_namespace) VALUES (?, ?, ?, ?);");
		try {
			$stmt->execute(array(
				$this->userId,
				$courseId->getIdentifier(),
				$courseId->getAuthority(),
				$courseId->getIdentifierNamespace()
			));
		} catch (Zend_Db_Statement_Exception $e) {
			if ($e->getCode() == 23000)
				throw new Exception('Bookmark already added.', 23000);
			else
				throw $e;
		}
	}
	
	/**
	 * Remove a bookmark
	 * 
	 * @param osid_id_Id $courseId
	 * @return void
	 * @access public
	 * @since 7/29/10
	 */
	public function remove (osid_id_Id $courseId) {
		$stmt = $this->db->prepare("DELETE FROM user_savedcourses WHERE user_id = ? AND course_id_keyword = ? AND course_id_authority = ? AND course_id_namespace = ? LIMIT 1;");
		$stmt->execute(array(
			$this->userId,
			$courseId->getIdentifier(),
			$courseId->getAuthority(),
			$courseId->getIdentifierNamespace()
		));
	}
	
	/**
	 * Answer true if the course Id passed is bookmarked
	 * 
	 * @param osid_id_Id $courseId
	 * @return boolean
	 * @access public
	 * @since 7/29/10
	 */
	public function isBookmarked (osid_id_Id $courseId) {
		$stmt = $this->db->prepare("SELECT COUNT(*) as is_bookmarked FROM user_savedcourses WHERE user_id = ? AND course_id_keyword = ? AND course_id_authority = ? AND course_id_namespace = ?");
		$stmt->execute(array(
			$this->userId,
			$courseId->getIdentifier(),
			$courseId->getAuthority(),
			$courseId->getIdentifierNamespace()
		));
		$num = intval($stmt->fetchColumn());
		return ($num > 0);
	}
	
	/**
	 * Answer an array of all bookmarked courseIds
	 * 
	 * @return osid_id_IdList
	 * @access public
	 * @since 7/30/10
	 */
	public function getAllBookmarkedCourseIds () {
		$stmt = $this->db->prepare("SELECT * FROM user_savedcourses WHERE user_id = ?");
		$stmt->execute(array(
			$this->userId
		));
		$ids = array();
		foreach ($stmt->fetchAll() as $row) {
			$ids[] = new phpkit_id_Id($row['course_id_authority'], $row['course_id_namespace'], $row['course_id_keyword']);
		}
		return new phpkit_id_ArrayIdList($ids);
	}
	
	/**
	 * Answer an array of all bookmarked courses
	 * 
	 * @return osid_course_CourseList
	 * @access public
	 * @since 7/30/10
	 */
	public function getAllBookmarkedCourses () {
		$courseIdList = $this->getAllBookmarkedCourseIds();
		if (!$courseIdList->hasNext())
			return new phpkit_course_ArrayCourseList(array());
		
		$courseLookupSession = $this->courseManager->getCourseLookupSession();
		$courseLookupSession->useFederatedView();
		return $courseLookupSession->getCoursesByIds($courseIdList);
	}
	
	/**
	 * Answer an array of all bookmarked courses that match a given catalog and term.
	 * 
	 * @param osid_id_Id $catalogId
	 * @param osid_id_Id $termId
	 * @return osid_course_CourseList
	 * @access public
	 * @since 7/30/10
	 */
	public function getBookmarkedCoursesInCatalogForTerm (osid_id_Id $catalogId, osid_id_Id $termId) {
		$courseIdList = $this->getAllBookmarkedCourseIds();
		if (!$courseIdList->hasNext())
			return new phpkit_course_ArrayCourseList(array());
		
		$searchSession = $this->courseManager->getCourseSearchSessionForCatalog($catalogId);
		
		$search = $searchSession->getCourseSearch();
		$search->searchAmongCourses($courseIdList);
		
		$query = $searchSession->getCourseQuery();
		$record = $query->getCourseQueryRecord(new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:term'));
		$record->matchTermId($termId, true);
		
		$results = $searchSession->getCoursesBySearch($query, $search);
		return $results->getCourses();
	}
}

?>