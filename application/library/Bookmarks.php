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
	public function __construct (Zend_Db_Adapter_Abstract $db, $userId) {
		if (!strlen($userId))
			throw new InvalidArgumentException('No $userId passed.');
		
		$this->db = $db;
		$this->userId = $userId;
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
}

?>