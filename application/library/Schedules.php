<?php
/**
 * @since 7/29/10
 * @package catalog.schedules
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * The Schedules class provides access to a list of user-created schedules.
 * 
 * @since 7/29/10
 * @package catalog.schedules
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Schedules {
		
	/**
	 * Constructor
	 * 
	 * @param Zend_Db_Adapter_Abstract $db
	 * @param string $userId
	 * @param osid_course_CourseManager $courseManager
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
	private $courseManager;
	
	/**
	 * Create a schedule
	 * 
	 * @param osid_id_Id $termId
	 * @return Schedule
	 * @access public
	 * @since 8/2/10
	 */
	public function createSchedule (osid_id_Id $termId) {
		$stmt = $this->db->prepare("INSERT INTO user_schedules (user_id, term_id_keyword, term_id_authority, term_id_namespace, name) VALUES (?, ?, ?, ?, ?);");
		$name = 'Untitled Schedule';
		$stmt->execute(array(
			$this->userId,
			$termId->getIdentifier(),
			$termId->getAuthority(),
			$termId->getIdentifierNamespace(),
			$name,
		));
		$id = $this->db->lastInsertId();
		return new Schedule($id, $this->db, $this->userId, $this->courseManager, $name, $termId);
	}
	
	/**
	 * Delete a schedule.
	 * 
	 * @param string $scheduleId
	 * @return void
	 * @access public
	 * @since 7/29/10
	 */
	public function deleteSchedule ($scheduleId) {
		$stmt = $this->db->prepare("DELETE FROM user_schedules WHERE id = ? AND user_id = ? LIMIT 1;");
		$stmt->execute(array(
			$scheduleId,
			$this->userId
		));
	}
	
	/**
	 * Answer a schedule by Id
	 * 
	 * @param string $scheduleId
	 * @return Schedule
	 * @access public
	 * @since 8/2/10
	 */
	public function getSchedule ($scheduleId) {
		$stmt = $this->db->prepare("SELECT * FROM user_schedules WHERE id = ? AND user_id = ? LIMIT 1;");
		$stmt->execute(array(
			$scheduleId,
			$this->userId
		));
		$rows = $stmt->fetchAll();
		if (!count($rows))
			throw new InvalidArgumentException('Schedule was not found.');
		return new Schedule($rows[0]['id'], $this->db, $this->userId, $this->courseManager, $rows[0]['name'], new phpkit_id_Id($rows[0]['term_id_authority'], $rows[0]['term_id_namespace'], $rows[0]['term_id_keyword']));

	}
	
	/**
	 * Answer all schedules for the current user
	 * 
	 * @return array of Schedule objects
	 * @access public
	 * @since 8/2/10
	 */
	public function getSchedules () {
		$stmt = $this->db->prepare("SELECT * FROM user_schedules WHERE user_id = ?;");
		$stmt->execute(array(
			$this->userId
		));
		
		$schedules = array();
		foreach ($stmt->fetchAll() as $row) {
			$schedules[] = new Schedule($row['id'], $this->db, $this->userId, $this->courseManager, $row['name'], new phpkit_id_Id($row['term_id_authority'], $row['term_id_namespace'], $row['term_id_keyword']));
		}
		return $schedules;
	}
	
	/**
	 * Answer schedules for the current user for a given term.
	 * 
	 * @param osid_id_Id $termId
	 * @return array of Schedule objects
	 * @access public
	 * @since 8/2/10
	 */
	public function getSchedulesByTerm (osid_id_Id $termId) {
		$stmt = $this->db->prepare("SELECT * FROM user_schedules WHERE user_id = ? AND term_id_keyword = ? AND term_id_authority = ? AND term_id_namespace = ?;");
		$stmt->execute(array(
			$this->userId,
			$termId->getIdentifier(),
			$termId->getAuthority(),
			$termId->getIdentifierNamespace()
		));
		
		$schedules = array();
		foreach ($stmt->fetchAll() as $row) {
			$schedules[] = new Schedule($row['id'], $this->db, $this->userId, $this->courseManager, $row['name'], new phpkit_id_Id($row['term_id_authority'], $row['term_id_namespace'], $row['term_id_keyword']));
		}
		return $schedules;
	}
}

?>