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
	 * Answer true if the offering Id passed is included in the schedule.
	 * 
	 * @param osid_id_Id $offeringId
	 * @return boolean
	 * @access public
	 * @since 8/3/10
	 */
	public function includes (osid_id_Id $offeringId) {
		$stmt = $this->db->prepare("SELECT * FROM user_schedule_offerings WHERE schedule_id = ? AND offering_id_keyword = ? AND offering_id_authority = ? AND offering_id_namespace = ? LIMIT 1;");
		$stmt->execute(array(
			$this->getId(),
			$offeringId->getIdentifier(),
			$offeringId->getAuthority(),
			$offeringId->getIdentifierNamespace(),
		));
		return (count($stmt->fetchAll()) > 0);
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
		
		return $this->timeSort($offerings);
	}
	
	/**
	 * Answer an array of information about all of the events in a week.
	 * The dayOfWeek field is a zero-based day-of-week. 0 for Sunday, 1 for Monday, etc.
	 * 
	 * @return array
	 * @access public
	 * @since 8/5/10
	 */
	public function getWeeklyEvents () {
		$events = array();
		$scheduleType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:weekly_schedule');
		
		foreach ($this->getOfferings() as $offering) {
			$name = $offering->getDisplayName();
			if ($offering->hasLocation()) {
				$location = $offering->getLocation()->getDescription();
			} else {
				$location = '';
			}
			$rec = $offering->getCourseOfferingRecord($scheduleType);
			
			if ($rec->meetsOnSunday()) {
				$this->addEvents($events, $name, $location, 0, $rec->getSundayStartTimes(), $rec->getSundayEndTimes());
			}
			if ($rec->meetsOnMonday()) {
				$this->addEvents($events, $name, $location, 1, $rec->getMondayStartTimes(), $rec->getMondayEndTimes());
			}
			if ($rec->meetsOnTuesday()) {
				$this->addEvents($events, $name, $location, 2, $rec->getTuesdayStartTimes(), $rec->getTuesdayEndTimes());
			}
			if ($rec->meetsOnWednesday()) {
				$this->addEvents($events, $name, $location, 3, $rec->getWednesdayStartTimes(), $rec->getWednesdayEndTimes());
			}
			if ($rec->meetsOnThursday()) {
				$this->addEvents($events, $name, $location, 4, $rec->getThursdayStartTimes(), $rec->getThursdayEndTimes());
			}
			if ($rec->meetsOnFriday()) {
				$this->addEvents($events, $name, $location, 5, $rec->getFridayStartTimes(), $rec->getFridayEndTimes());
			}
			if ($rec->meetsOnSaturday()) {
				$this->addEvents($events, $name, $location, 6, $rec->getSaturdayStartTimes(), $rec->getSaturdayEndTimes());
			}
		}
		
		return $events;
	}
	
	/**
     * Add events to an array.
     * 
     * @param ref array $events
     * @param string $name
     * @param string $location
     * @param int $dayOfWeek
     * @param array $startTimes
     * @param array $endTimes
     * @return <##>
     * @access private
     * @since 8/5/10
     */
    private function addEvents (array &$events, $name, $location, $dayOfWeek, array $startTimes, array $endTimes) {
    	foreach ($startTimes as $i => $startTime) {
			$events[] = array(
				'id'		=> $name.'-'.$dayOfWeek.'-'.$startTime,
				'name'		=> $name,
				'location'	=> $location,
				'dayOfWeek'	=> $dayOfWeek,
				'startTime' => $startTime,
				'endTime'	=> $endTimes[$i],
			);
		}
    }
	
	/**
	 * Sort an array of offerings based on their first meeting time.
	 * 
	 * @param ref array $offerings
	 * @return array The sorted array
	 * @access public
	 * @since 8/4/10
	 */
	public function timeSort (array &$offerings) {
		// Build a sort-key out of the day of week and time.
		$sortkeys = array();
		$names = array();
		
		$weeklyScheduleType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:weekly_schedule');
		
		foreach ($offerings as $offering) {
			$key = 0;
			$rec = $offering->getCourseOfferingRecord($weeklyScheduleType);
			
			if ($rec->meetsOnSunday()) {
				// Add an integer for the first week day.
				$key += 1;
				// Add a fraction for the first start time.
				$times = $rec->getSundayStartTimes();
				$key += $times[0] / 86400;
			} else if ($rec->meetsOnMonday()) {
				// Add an integer for the first week day.
				$key += 2;
				// Add a fraction for the first start time.
				$times = $rec->getMondayStartTimes();
				$key += $times[0] / 86400;
			} else if ($rec->meetsOnTuesday()) {
				// Add an integer for the first week day.
				$key += 3;
				// Add a fraction for the first start time.
				$times = $rec->getTuesdayStartTimes();
				$key += $times[0] / 86400;
			} else if ($rec->meetsOnWednesday()) {
				// Add an integer for the first week day.
				$key += 4;
				// Add a fraction for the first start time.
				$times = $rec->getWednesdayStartTimes();
				$key += $times[0] / 86400;
			} else if ($rec->meetsOnThursday()) {
				// Add an integer for the first week day.
				$key += 5;
				// Add a fraction for the first start time.
				$times = $rec->getThursdayStartTimes();
				$key += $times[0] / 86400;
			} else if ($rec->meetsOnFriday()) {
				// Add an integer for the first week day.
				$key += 6;
				// Add a fraction for the first start time.
				$times = $rec->getFridayStartTimes();
				$key += $times[0] / 86400;
			} else if ($rec->meetsOnSaturday()) {
				// Add an integer for the first week day.
				$key += 7;
				// Add a fraction for the first start time.
				$times = $rec->getSaturdayStartTimes();
				$key += $times[0] / 86400;
			}
			
			$sortkeys[] = $key;
			$names[] = $offering->getDisplayName();
		}
		
		array_multisort($sortkeys, SORT_NUMERIC, SORT_ASC, $names, SORT_STRING, SORT_ASC, array_keys($offerings), $offerings);
		return $offerings;
	}
}

?>