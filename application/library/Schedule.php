<?php
/**
 * @since 8/2/10
 * @package catalog.schedules
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

require_once('harmoni/Primitives/Chronology/Week.class.php');

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
	public function __construct ($id, Zend_Db_Adapter_Abstract $db, $userId, osid_course_CourseManager $courseManager, $name, osid_id_Id $termId) {
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
		$this->termId = $termId;
	}
	
	private $db;
	private $userId;
	private $courseManager;
	private $name;
	private $id;
	private $termId;
	
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
	 * Answer the Id of the term this schedule is associated with.
	 * 
	 * @return osid_id_Id
	 * @access public
	 * @since 8/16/10
	 */
	public function getTermId () {
		return $this->termId;
	}
	
	/**
	 * Answer the name of the term this schedule is associated with.
	 * 
	 * @return string
	 * @access public
	 * @since 8/16/10
	 */
	public function getTermName () {
		try {
			$session = $this->courseManager->getTermLookupSession();
			$session->useFederatedCourseCatalogView();
			return $session->getTerm($this->termId)->getDisplayName();
		} catch (osid_NotFoundException $e) {
			return $this->termId->getIdentifier();
		}
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
		unset($this->offerings);
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
		// If we've already loaded our offerings, look at the object properties
		// rather than doing another query.
		if (isset($this->offerings)) {
			foreach ($this->offerings as $offering) {
				if ($offeringId->isEqual($offering->getId()))
					return true;
			}
			return false;
		}
		
		// Do the lookup in the database.
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
		unset($this->offerings);
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
		if (!isset($this->offerings)) {
			$stmt = $this->db->prepare("SELECT * FROM user_schedule_offerings WHERE schedule_id = ?;");
			$stmt->execute(array(
				$this->getId()
			));
			
			$lookupSession = $this->courseManager->getCourseOfferingLookupSession();
			$lookupSession->useFederatedCourseCatalogView();
			
			$offerings = array();
			foreach ($stmt->fetchAll() as $row) {
				$offeringId = new phpkit_id_Id($row['offering_id_authority'], $row['offering_id_namespace'], $row['offering_id_keyword']);
				try {
					$offering = $lookupSession->getCourseOffering($offeringId);
					
					// This existence test (getDisplayName) shouldn't really be needed,
					// but the apc_course_CourseOffering_Lookup_Session::getCourseOffering($id)
					// method blindly returns course offerings without checking their
					// existence. While this is a huge performance boost, it also means
					// that the CourseOffering returned might not be fully usable.
					$offering->getDisplayName();
					
					$offerings[] = $offering;
				} catch (osid_NotFoundException $e) {
					// Ignore offerings that are no longer being offered.
				}
			}
			
			$this->offerings = $this->nameSort($offerings);
		}
		return $this->offerings;
	}
	private $offerings;
	
	/**
	 * Answer an array of information about all of the events in a week.
	 * The dayOfWeek field is a zero-based day-of-week. 0 for Sunday, 1 for Monday, etc.
	 * 
	 * @return array
	 * @access public
	 * @since 8/5/10
	 */
	public function getWeeklyEvents () {
		if (!isset($this->events)) {
			$this->events = array();
			$scheduleType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:weekly_schedule');
			
			foreach ($this->getOfferings() as $offering) {
				$this->events = array_merge($this->events, $this->getWeeklyOfferingEvents($offering));
			}
			
			// Check for collisions
			foreach ($this->events as $i => $event) {
				$this->events[$i]['collisions'] = $this->numCollisions($event, $this->events);
			}
		}
		return $this->events;
	}
	private $events;
	
	/**
     * Add events to an array.
     * 
     * @param string $name
     * @param string $offeringIdString
     * @param string $location
     * @param int $dayOfWeek
     * @param array $startTimes
     * @param array $endTimes
     * @return void
     * @access private
     * @since 8/5/10
     */
    private function getDailyEvents ($name, $offeringIdString, $location, $dayOfWeek, array $startTimes, array $endTimes) {
    	$events = array();
    	foreach ($startTimes as $i => $startTime) {
			$events[] = array(
				'id'		=> $name.'-'.$dayOfWeek.'-'.$startTime,
				'offeringId'	=> $offeringIdString,
				'name'		=> $name,
				'location'	=> $location,
				'dayOfWeek'	=> $dayOfWeek,
				'startTime' => $startTime,
				'endTime'	=> $endTimes[$i],
			);
		}
		return $events;
    }
    
    /**
     * Answer an array of events for the offering
     * 
     * @param osid_course_CourseOffering $offering
     * @return array
     * @access private
     * @since 8/9/10
     */
    private function getWeeklyOfferingEvents (osid_course_CourseOffering $offering) {
		$scheduleType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:weekly_schedule');
		$events = array();

    	$name = $offering->getDisplayName();
		if ($offering->hasLocation()) {
			$location = $offering->getLocation()->getDescription();
		} else {
			$location = '';
		}
		try {
			$rec = $offering->getCourseOfferingRecord($scheduleType);
		} catch (osid_UnsupportedException $e) {
			throw new InvalidArgumentException($e->getMessage(), $e->getCode());
		}
		
		$idString = $this->idToString($offering->getId());
		
		if ($rec->meetsOnSunday()) {
			$events = array_merge($events, $this->getDailyEvents($name, $idString, $location, 0, $rec->getSundayStartTimes(), $rec->getSundayEndTimes()));
		}
		if ($rec->meetsOnMonday()) {
			$events = array_merge($events, $this->getDailyEvents($name, $idString, $location, 1, $rec->getMondayStartTimes(), $rec->getMondayEndTimes()));
		}
		if ($rec->meetsOnTuesday()) {
			$events = array_merge($events, $this->getDailyEvents($name, $idString, $location, 2, $rec->getTuesdayStartTimes(), $rec->getTuesdayEndTimes()));
		}
		if ($rec->meetsOnWednesday()) {
			$events = array_merge($events, $this->getDailyEvents($name, $idString, $location, 3, $rec->getWednesdayStartTimes(), $rec->getWednesdayEndTimes()));
		}
		if ($rec->meetsOnThursday()) {
			$events = array_merge($events, $this->getDailyEvents($name, $idString, $location, 4, $rec->getThursdayStartTimes(), $rec->getThursdayEndTimes()));
		}
		if ($rec->meetsOnFriday()) {
			$events = array_merge($events, $this->getDailyEvents($name, $idString, $location, 5, $rec->getFridayStartTimes(), $rec->getFridayEndTimes()));
		}
		if ($rec->meetsOnSaturday()) {
			$events = array_merge($events, $this->getDailyEvents($name, $idString, $location, 6, $rec->getSaturdayStartTimes(), $rec->getSaturdayEndTimes()));
		}
		
		return $events;
    }
    
    /**
     * Answer true if the offering passed conflicts with offerings in the schedule.
     * Will throw an InvalidArgumentException if the offering passed does not
     * support the urn:inet:middlebury.edu:record:weekly_schedule record type.
     * 
     * @param osid_course_CourseOffering $offering
     * @return boolean
     * @access public
     * @since 8/9/10
     */
    public function conflicts (osid_course_CourseOffering $offering) {
    	$events = $this->getWeeklyOfferingEvents($offering);
    	foreach ($events as $event) {
    		if ($this->numCollisions($event, $this->getWeeklyEvents())) {
    			return true;
    		}
    	}
    	return false;
    }
    
    /**
     * Answer an array of events that conflict with the events in the offering.
     * 
     * @param osid_course_CourseOffering $offering
     * @return boolean
     * @access public
     * @since 8/9/10
     */
    public function getConflictingEvents (osid_course_CourseOffering $offering) {
    	$myEvents = $this->getWeeklyOfferingEvents($offering);
    	$conflictingEvents = array();
    	$conflictingEventIds = array();
    	foreach ($myEvents as $myEvent) {
    		foreach ($this->getWeeklyEvents() as $event) {
	    		if ($this->eventsCollide($event, $myEvent) && !in_array($event['id'], $conflictingEventIds)) {
	    			$conflictingEventIds[] = $event['id'];
    				$conflictingEvents[] = $event;
    			}
    		}
    	}
    	return $conflictingEvents;
    }
    
    /**
     * Answer true if the offering id passed exists in the schedule and has collisions. 
     * The offering must exist in the schedule to obtain a result. 
     * An InvalidArgumentException will be thrown if the offering is not in the schedule.
     * 
     * @param osid_id_Id
     * @return boolean
     * @access public
     * @since 8/9/10
     */
    public function hasCollisions (osid_id_Id $id) {
    	if (!$this->includes($id))
    		throw new InvalidArgumentException('The offering Id passed is not in the schedule.');
    	$idString = $this->idToString($id);
    	foreach ($this->getWeeklyEvents() as $event) {
    		if ($event['offeringId'] == $idString && $event['collisions'] > 0) {
    			return true;
    		}
    	}
    	return false;
    }
    
    /**
     * Answer the number of collisions between an event and an array of events.
     * Events that have the same 'id' element will not be counted.
     * 
     * 
     * @param array $event
     * @param array $compEvents
     * @return int
     * @access private
     * @since 8/9/10
     */
    private function numCollisions (array $event, $compEvents) {
    	$collisions = 0;
		// If we have a different event on the same day check its time for collisions.
		foreach ($compEvents as $compEvent) {
			if ($this->eventsCollide($event, $compEvent)) {
				$collisions++;
			}
		}
		
		return $collisions;
    }
    
    /**
     * Answer true if two events collide
     * 
     * @param array $event1
     * @param array $event2
     * @return boolean
     * @access private
     * @since 8/9/10
     */
    private function eventsCollide (array $event1, array $event2) {
    	// Events on different days do not collide
    	if ($event1['dayOfWeek'] != $event2['dayOfWeek'])
    		return false;
    	
    	// An event won't conflict with itself
    	if ($event1['id'] == $event2['id'])
    		return false;
    	
    	$timespan1 = Timespan::startingEnding(
			DateAndTime::today()->plus(Duration::withSeconds($event1['startTime'])),
			DateAndTime::today()->plus(Duration::withSeconds($event1['endTime'])));
		$timespan2 = Timespan::startingEnding(
			DateAndTime::today()->plus(Duration::withSeconds($event2['startTime'])),
			DateAndTime::today()->plus(Duration::withSeconds($event2['endTime'])));
		
		return !is_null($timespan1->intersection($timespan2));
    }
    
    /**
     * Answer the earliest time seen in this schedule
     * 
     * @return int Seconds in a day
     * @access public
     * @since 8/6/10
     */
    public function getEarliestTime () {
    	$time = 24 * 60 * 60;
    	foreach ($this->getWeeklyEvents() as $event) {
			if ($event['startTime'] < $time) {
				$time = $event['startTime'];
			}
		}
		// If we didn't find any events with a non-zero time, set our start time to 0.
		if ($time == 24 * 60 * 60)
			return 0;
		
		return $time;
    }
    
     /**
     * Answer the latest time seen in this schedule
     * 
     * @return int Seconds in a day
     * @access public
     * @since 8/6/10
     */
    public function getLatestTime () {
    	$time = 0;
    	foreach ($this->getWeeklyEvents() as $event) {
			if ($event['endTime'] > $time) {
				$time = $event['endTime'];
			}
		}
		return $time;
    }
    
    /**
     * Answer the earliest hour seen in this schedule (ignoring minutes);
     * 
     * @return int 0 to 23
     * @access public
     * @since 8/6/10
     */
    public function getEarliestHour () {
    	return floor($this->getEarliestTime() / 3600);
    }
    
    /**
     * Answer the latest hour seen in this schedule (ignoring minutes);
     * 
     * @return int 0 to 23
     * @access public
     * @since 8/6/10
     */
    public function getLatestHour () {
    	return floor($this->getLatestTime() / 3600);
    }
    
    /**
     * Answer true if this schedule has any events on Sunday
     * 
     * @return boolean
     * @access public
     * @since 8/6/10
     */
    public function hasEventsOnSunday () {
    	foreach ($this->getWeeklyEvents() as $event) {
			if ($event['dayOfWeek'] === 0)
				return true;
		}
		return false;
    }
    
    /**
     * Answer true if this schedule has any events on Saturday
     * 
     * @return boolean
     * @access public
     * @since 8/6/10
     */
    public function hasEventsOnSaturday () {
    	foreach ($this->getWeeklyEvents() as $event) {
			if ($event['dayOfWeek'] === 6)
				return true;
		}
		return false;
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
	
	/**
	 * Sort an array of offerings based on their names.
	 * 
	 * @param ref array $offerings
	 * @return array The sorted array
	 * @access public
	 * @since 8/6/10
	 */
	public function nameSort (array &$offerings) {
		$names = array();
		
		foreach ($offerings as $offering) {
			$names[] = $offering->getDisplayName();
		}
		
		array_multisort($names, SORT_STRING, SORT_ASC, array_keys($offerings), $offerings);
		return $offerings;
	}
	
	/**
	 * Convert an Id to a string for hashing purposes.
	 * 
	 * @param osid_id_Id $id
	 * @return string
	 * @access private
	 * @since 8/9/10
	 */
	private function idToString (osid_id_Id $id) {
		return $id->getIdentifierNamespace().':'.$id->getAuthority().':'.$id->getIdentifier();
	}
}

?>