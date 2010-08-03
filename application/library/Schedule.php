<?php
/**
 * @since 8/2/10
 * @package catalog.schedules
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * A class for working with user-created schedules
 * 
 * @since 8/2/10
 * @package catalog.schedules
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Schedule {
		
	/**
	 * Constructor
	 * 
	 * @param string $id
	 * @param Zend_Db_Adapter_Abstract $db
	 * @param string $userId
	 * @param osid_course_CourseManager $courseManager
	 * @param string $name
	 * @return void
	 * @access public
	 * @since 7/29/10
	 */
	public function __construct ($id, Zend_Db_Adapter_Abstract $db, $userId, osid_course_CourseManager $courseManager, $name) {
		if (!strlen($userId))
			throw new InvalidArgumentException('No $userId passed.');
		if (!strlen($id))
			throw new InvalidArgumentException('No $id passed.');
		if (!strlen($name))
			throw new InvalidArgumentException('No $name passed.');
		
		$this->db = $db;
		$this->userId = $userId;
		$this->courseManager = $courseManager;
		$this->id = $id;
		$this->name = $name;
	}
	
	private $db;
	private $userId;
	private $courseManager;
	private $name;
	private $id;
	
	/**
	 * Answer the name of the Schedule
	 * 
	 * @return string
	 * @access public
	 * @since 8/2/10
	 */
	public function getName () {
		return $this->name;
	}
	
	/**
	 * Update our display name
	 * 
	 * @param string $name
	 * @return void
	 * @access public
	 * @since 8/2/10
	 */
	public function setName ($name) {
		$name = trim(preg_replace('/\W/', ' ', $name));
		if (!strlen($name))
			throw new InvalidArgumentException('Name is invalid.');
		
		$stmt = $this->db->prepare("UPDATE user_schedules SET name = ? WHERE id = ? AND user_id = ?;");
		$stmt->execute(array(
			$name,
			$this->id,
			$this->userId
		));
		$this->name = $name;
	}
	
	/**
	 * Answer the id of the Schedule
	 * 
	 * @return string
	 * @access public
	 * @since 8/2/10
	 */
	public function getId () {
		return $this->id;
	}
	
	/**
	 * Add an offering to a schedule.
	 * 
	 * @param osid_id_Id $offeringId
	 * @return void
	 * @access public
	 * @since 8/3/10
	 */
	public function add (osid_id_Id $offeringId) {
		$stmt = $this->db->prepare("INSERT INTO user_schedule_offerings (schedule_id, offering_id_keyword, offering_id_authority, offering_id_namespace) VALUES (?, ?, ?, ?);");
		$name = 'Untitled Schedule';
		try {
			$stmt->execute(array(
				$this->getId(),
				$offeringId->getIdentifier(),
				$offeringId->getAuthority(),
				$offeringId->getIdentifierNamespace(),
			));
		} catch (Zend_Db_Statement_Exception $e) {
			if ($e->getCode() == 23000)
				throw new Exception('Offering already added.', 23000);
			else
				throw $e;
		}
	}
	
	/**
	 * Remove an offering from the schedule
	 * 
	 * @param osid_id_Id $offeringId
	 * @return void
	 * @access public
	 * @since 8/3/10
	 */
	public function remove (osid_id_Id $offeringId) {
		$stmt = $this->db->prepare("DELETE FROM user_schedule_offerings WHERE schedule_id = ? AND offering_id_keyword = ? AND offering_id_authority = ? AND offering_id_namespace = ? LIMIT 1;");
		$stmt->execute(array(
			$this->getId(),
			$offeringId->getIdentifier(),
			$offeringId->getAuthority(),
			$offeringId->getIdentifierNamespace(),
		));
	}
	
	/**
	 * Answer all of the offerings added to this schedule.
	 * 
	 * @return array of osid_course_CourseOffering objects
	 * @access public
	 * @since 8/2/10
	 */
	public function getOfferings () {
		$stmt = $this->db->prepare("SELECT * FROM user_schedule_offerings WHERE schedule_id = ?;");
		$stmt->execute(array(
			$this->getId()
		));
		
		$lookupSession = $this->courseManager->getCourseOfferingLookupSession();
		$lookupSession->useFederatedView();
		
		$offerings = array();
		foreach ($stmt->fetchAll() as $row) {
			$offeringId = new phpkit_id_Id($row['offering_id_authority'], $row['offering_id_namespace'], $row['offering_id_keyword']);
			$offerings[] = $lookupSession->getCourseOffering($offeringId);
		}
		return $offerings;
	}
}

?>