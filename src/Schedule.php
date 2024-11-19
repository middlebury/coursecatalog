<?php
/**
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */

namespace App;

use Doctrine\DBAL\Connection;

/**
 * A class for working with user-created schedules.
 *
 * @since 8/2/10
 *
 * @copyright Copyright &copy; 2024, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Schedule
{
    /**
     * Constructor.
     *
     * @return void
     *
     * @since 7/29/10
     */
    public function __construct(
        private string $id,
        private Connection $db,
        private string $userId,
        private \osid_course_CourseManager $courseManager,
        private string $name,
        private \osid_id_Id $termId,
    ) {
        if (!strlen($userId)) {
            throw new \InvalidArgumentException('No $userId passed.');
        }
        if (!strlen($id)) {
            throw new \InvalidArgumentException('No $id passed.');
        }
        if (!strlen($name)) {
            throw new \InvalidArgumentException('No $name passed.');
        }
    }

    /**
     * Answer the name of the Schedule.
     *
     * @return string
     *
     * @since 8/2/10
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Update our display name.
     *
     * @param string $name
     *
     * @return void
     *
     * @since 8/2/10
     */
    public function setName($name)
    {
        $name = trim(preg_replace('/\W/', ' ', $name));
        if (!strlen($name)) {
            throw new \InvalidArgumentException('Name is invalid.');
        }

        $stmt = $this->db->prepare('UPDATE user_schedules SET name = ? WHERE id = ? AND user_id = ?;');
        $stmt->bindValue(1, $name);
        $stmt->bindValue(2, $this->id);
        $stmt->bindValue(3, $this->userId);
        $stmt->executeQuery();
        $this->name = $name;
    }

    /**
     * Answer the id of the Schedule.
     *
     * @return string
     *
     * @since 8/2/10
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Answer the Id of the term this schedule is associated with.
     *
     * @return \osid_id_Id
     *
     * @since 8/16/10
     */
    public function getTermId()
    {
        return $this->termId;
    }

    /**
     * Answer the name of the term this schedule is associated with.
     *
     * @return string
     *
     * @since 8/16/10
     */
    public function getTermName()
    {
        try {
            $session = $this->courseManager->getTermLookupSession();
            $session->useFederatedCourseCatalogView();

            return $session->getTerm($this->termId)->getDisplayName();
        } catch (\osid_NotFoundException $e) {
            return $this->termId->getIdentifier();
        }
    }

    /**
     * Add an offering to a schedule.
     *
     * @return void
     *
     * @since 8/3/10
     */
    public function add(\osid_id_Id $offeringId)
    {
        unset($this->offerings);
        $stmt = $this->db->prepare('INSERT INTO user_schedule_offerings (schedule_id, offering_id_keyword, offering_id_authority, offering_id_namespace) VALUES (?, ?, ?, ?);');
        $name = 'Untitled Schedule';
        $stmt->bindValue(1, $this->getId());
        $stmt->bindValue(2, $offeringId->getIdentifier());
        $stmt->bindValue(3, $offeringId->getAuthority());
        $stmt->bindValue(4, $offeringId->getIdentifierNamespace());
        try {
            $stmt->executeQuery();
        } catch (Zend_Db_Statement_Exception $e) {
            if (23000 == $e->getCode()) {
                throw new \Exception('Offering already added.', 23000);
            } else {
                throw $e;
            }
        }
    }

    /**
     * Answer true if the offering Id passed is included in the schedule.
     *
     * @return bool
     *
     * @since 8/3/10
     */
    public function includes(\osid_id_Id $offeringId)
    {
        // If we've already loaded our offerings, look at the object properties
        // rather than doing another query.
        if (isset($this->offerings)) {
            foreach ($this->offerings as $offering) {
                if ($offeringId->isEqual($offering->getId())) {
                    return true;
                }
            }

            return false;
        }

        // Do the lookup in the database.
        $stmt = $this->db->prepare('SELECT * FROM user_schedule_offerings WHERE schedule_id = ? AND offering_id_keyword = ? AND offering_id_authority = ? AND offering_id_namespace = ? LIMIT 1;');
        $stmt->bindValue(1, $this->getId());
        $stmt->bindValue(2, $offeringId->getIdentifier());
        $stmt->bindValue(3, $offeringId->getAuthority());
        $stmt->bindValue(4, $offeringId->getIdentifierNamespace());
        $result = $stmt->executeQuery();

        return false !== $result->fetchAssociative();
    }

    /**
     * Remove an offering from the schedule.
     *
     * @return void
     *
     * @since 8/3/10
     */
    public function remove(\osid_id_Id $offeringId)
    {
        unset($this->offerings);
        $stmt = $this->db->prepare('DELETE FROM user_schedule_offerings WHERE schedule_id = ? AND offering_id_keyword = ? AND offering_id_authority = ? AND offering_id_namespace = ? LIMIT 1;');
        $stmt->bindValue(1, $this->getId());
        $stmt->bindValue(2, $offeringId->getIdentifier());
        $stmt->bindValue(3, $offeringId->getAuthority());
        $stmt->bindValue(4, $offeringId->getIdentifierNamespace());
        $stmt->executeQuery();
    }

    /**
     * Answer all of the offerings added to this schedule.
     *
     * @return array of osid_course_CourseOffering objects
     *
     * @since 8/2/10
     */
    public function getOfferings()
    {
        if (!isset($this->offerings)) {
            $stmt = $this->db->prepare('SELECT * FROM user_schedule_offerings WHERE schedule_id = ?;');
            $stmt->bindValue(1, $this->getId());
            $result = $stmt->executeQuery();

            $lookupSession = $this->courseManager->getCourseOfferingLookupSession();
            $lookupSession->useFederatedCourseCatalogView();

            $offerings = [];
            while (($row = $result->fetchAssociative()) !== false) {
                $offeringId = new \phpkit_id_Id($row['offering_id_authority'], $row['offering_id_namespace'], $row['offering_id_keyword']);
                try {
                    $offering = $lookupSession->getCourseOffering($offeringId);

                    // This existence test (getDisplayName) shouldn't really be needed,
                    // but the apc_course_CourseOffering_Lookup_Session::getCourseOffering($id)
                    // method blindly returns course offerings without checking their
                    // existence. While this is a huge performance boost, it also means
                    // that the CourseOffering returned might not be fully usable.
                    $offering->getDisplayName();

                    $offerings[] = $offering;
                } catch (\osid_NotFoundException $e) {
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
     *
     * @since 8/5/10
     */
    public function getWeeklyEvents()
    {
        if (!isset($this->events)) {
            $this->events = [];
            $scheduleType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:weekly_schedule');

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
     * @param string $crn
     * @param int    $dayOfWeek
     *
     * @return void
     *
     * @since 8/5/10
     */
    private function getDailyEvents($name, $offeringIdString, $location, $crn, $dayOfWeek, array $startTimes, array $endTimes)
    {
        $events = [];
        foreach ($startTimes as $i => $startTime) {
            $events[] = [
                'id' => $name.'-'.$dayOfWeek.'-'.$startTime,
                'offeringId' => $offeringIdString,
                'name' => $name,
                'location' => $location,
                'crn' => $crn,
                'dayOfWeek' => $dayOfWeek,
                'startTime' => $startTime,
                'endTime' => $endTimes[$i],
            ];
        }

        return $events;
    }

    /**
     * Answer an array of events for the offering.
     *
     * @return array
     *
     * @since 8/9/10
     */
    private function getWeeklyOfferingEvents(\osid_course_CourseOffering $offering)
    {
        $scheduleType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:weekly_schedule');
        $events = [];

        $name = $offering->getDisplayName();
        if ($offering->hasLocation()) {
            $location = $offering->getLocation()->getDescription();
        } else {
            $location = '';
        }
        $crn = $offering->getCourseReferenceNumber();
        try {
            $rec = $offering->getCourseOfferingRecord($scheduleType);
        } catch (\osid_UnsupportedException $e) {
            throw new \InvalidArgumentException($e->getMessage(), $e->getCode());
        }

        $idString = $this->idToString($offering->getId());

        if ($rec->meetsOnSunday()) {
            $events = array_merge($events, $this->getDailyEvents($name, $idString, $location, $crn, 0, $rec->getSundayStartTimes(), $rec->getSundayEndTimes()));
        }
        if ($rec->meetsOnMonday()) {
            $events = array_merge($events, $this->getDailyEvents($name, $idString, $location, $crn, 1, $rec->getMondayStartTimes(), $rec->getMondayEndTimes()));
        }
        if ($rec->meetsOnTuesday()) {
            $events = array_merge($events, $this->getDailyEvents($name, $idString, $location, $crn, 2, $rec->getTuesdayStartTimes(), $rec->getTuesdayEndTimes()));
        }
        if ($rec->meetsOnWednesday()) {
            $events = array_merge($events, $this->getDailyEvents($name, $idString, $location, $crn, 3, $rec->getWednesdayStartTimes(), $rec->getWednesdayEndTimes()));
        }
        if ($rec->meetsOnThursday()) {
            $events = array_merge($events, $this->getDailyEvents($name, $idString, $location, $crn, 4, $rec->getThursdayStartTimes(), $rec->getThursdayEndTimes()));
        }
        if ($rec->meetsOnFriday()) {
            $events = array_merge($events, $this->getDailyEvents($name, $idString, $location, $crn, 5, $rec->getFridayStartTimes(), $rec->getFridayEndTimes()));
        }
        if ($rec->meetsOnSaturday()) {
            $events = array_merge($events, $this->getDailyEvents($name, $idString, $location, $crn, 6, $rec->getSaturdayStartTimes(), $rec->getSaturdayEndTimes()));
        }

        return $events;
    }

    /**
     * Answer true if the offering passed conflicts with offerings in the schedule.
     * Will throw an InvalidArgumentException if the offering passed does not
     * support the urn:inet:middlebury.edu:record:weekly_schedule record type.
     *
     * @return bool
     *
     * @since 8/9/10
     */
    public function conflicts(\osid_course_CourseOffering $offering)
    {
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
     * @return bool
     *
     * @since 8/9/10
     */
    public function getConflictingEvents(\osid_course_CourseOffering $offering)
    {
        $myEvents = $this->getWeeklyOfferingEvents($offering);
        $conflictingEvents = [];
        $conflictingEventIds = [];
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
     * @param \osid_id_Id
     *
     * @return bool
     *
     * @since 8/9/10
     */
    public function hasCollisions(\osid_id_Id $id)
    {
        if (!$this->includes($id)) {
            throw new \InvalidArgumentException('The offering Id passed is not in the schedule.');
        }
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
     * @param array $compEvents
     *
     * @return int
     *
     * @since 8/9/10
     */
    private function numCollisions(array $event, $compEvents)
    {
        $collisions = 0;
        // If we have a different event on the same day check its time for collisions.
        foreach ($compEvents as $compEvent) {
            if ($this->eventsCollide($event, $compEvent)) {
                ++$collisions;
            }
        }

        return $collisions;
    }

    /**
     * Answer true if two events collide.
     *
     * @return bool
     *
     * @since 8/9/10
     */
    private function eventsCollide(array $event1, array $event2)
    {
        // Events on different days do not collide
        if ($event1['dayOfWeek'] != $event2['dayOfWeek']) {
            return false;
        }

        // An event won't conflict with itself
        if ($event1['id'] == $event2['id']) {
            return false;
        }

        $timespan1 = \Timespan::startingEnding(
            \DateAndTime::today()->plus(\Duration::withSeconds($event1['startTime'])),
            \DateAndTime::today()->plus(\Duration::withSeconds($event1['endTime'])));
        $timespan2 = \Timespan::startingEnding(
            \DateAndTime::today()->plus(\Duration::withSeconds($event2['startTime'])),
            \DateAndTime::today()->plus(\Duration::withSeconds($event2['endTime'])));

        return null !== $timespan1->intersection($timespan2);
    }

    /**
     * Answer the earliest time seen in this schedule.
     *
     * @return int Seconds in a day
     *
     * @since 8/6/10
     */
    public function getEarliestTime()
    {
        $time = 24 * 60 * 60;
        foreach ($this->getWeeklyEvents() as $event) {
            if ($event['startTime'] < $time) {
                $time = $event['startTime'];
            }
        }
        // If we didn't find any events with a non-zero time, set our start time to 0.
        if ($time == 24 * 60 * 60) {
            return 0;
        }

        return $time;
    }

    /**
     * Answer the latest time seen in this schedule.
     *
     * @return int Seconds in a day
     *
     * @since 8/6/10
     */
    public function getLatestTime()
    {
        $time = 0;
        foreach ($this->getWeeklyEvents() as $event) {
            if ($event['endTime'] > $time) {
                $time = $event['endTime'];
            }
        }

        return $time;
    }

    /**
     * Answer the earliest hour seen in this schedule (ignoring minutes);.
     *
     * @return int 0 to 23
     *
     * @since 8/6/10
     */
    public function getEarliestHour()
    {
        return floor($this->getEarliestTime() / 3600);
    }

    /**
     * Answer the latest hour seen in this schedule (ignoring minutes);.
     *
     * @return int 0 to 23
     *
     * @since 8/6/10
     */
    public function getLatestHour()
    {
        return floor($this->getLatestTime() / 3600);
    }

    /**
     * Answer true if this schedule has any events on Sunday.
     *
     * @return bool
     *
     * @since 8/6/10
     */
    public function hasEventsOnSunday()
    {
        foreach ($this->getWeeklyEvents() as $event) {
            if (0 === $event['dayOfWeek']) {
                return true;
            }
        }

        return false;
    }

    /**
     * Answer true if this schedule has any events on Saturday.
     *
     * @return bool
     *
     * @since 8/6/10
     */
    public function hasEventsOnSaturday()
    {
        foreach ($this->getWeeklyEvents() as $event) {
            if (6 === $event['dayOfWeek']) {
                return true;
            }
        }

        return false;
    }

    /**
     * Sort an array of offerings based on their first meeting time.
     *
     * @param ref array $offerings
     *
     * @return array The sorted array
     *
     * @since 8/4/10
     */
    public function timeSort(array &$offerings)
    {
        // Build a sort-key out of the day of week and time.
        $sortkeys = [];
        $names = [];

        $weeklyScheduleType = new \phpkit_type_URNInetType('urn:inet:middlebury.edu:record:weekly_schedule');

        foreach ($offerings as $offering) {
            $key = 0;
            $rec = $offering->getCourseOfferingRecord($weeklyScheduleType);

            if ($rec->meetsOnSunday()) {
                // Add an integer for the first week day.
                ++$key;
                // Add a fraction for the first start time.
                $times = $rec->getSundayStartTimes();
                $key += $times[0] / 86400;
            } elseif ($rec->meetsOnMonday()) {
                // Add an integer for the first week day.
                $key += 2;
                // Add a fraction for the first start time.
                $times = $rec->getMondayStartTimes();
                $key += $times[0] / 86400;
            } elseif ($rec->meetsOnTuesday()) {
                // Add an integer for the first week day.
                $key += 3;
                // Add a fraction for the first start time.
                $times = $rec->getTuesdayStartTimes();
                $key += $times[0] / 86400;
            } elseif ($rec->meetsOnWednesday()) {
                // Add an integer for the first week day.
                $key += 4;
                // Add a fraction for the first start time.
                $times = $rec->getWednesdayStartTimes();
                $key += $times[0] / 86400;
            } elseif ($rec->meetsOnThursday()) {
                // Add an integer for the first week day.
                $key += 5;
                // Add a fraction for the first start time.
                $times = $rec->getThursdayStartTimes();
                $key += $times[0] / 86400;
            } elseif ($rec->meetsOnFriday()) {
                // Add an integer for the first week day.
                $key += 6;
                // Add a fraction for the first start time.
                $times = $rec->getFridayStartTimes();
                $key += $times[0] / 86400;
            } elseif ($rec->meetsOnSaturday()) {
                // Add an integer for the first week day.
                $key += 7;
                // Add a fraction for the first start time.
                $times = $rec->getSaturdayStartTimes();
                $key += $times[0] / 86400;
            }

            $sortkeys[] = $key;
            $names[] = $offering->getDisplayName();
        }

        array_multisort($sortkeys, \SORT_NUMERIC, \SORT_ASC, $names, \SORT_STRING, \SORT_ASC, array_keys($offerings), $offerings);

        return $offerings;
    }

    /**
     * Sort an array of offerings based on their names.
     *
     * @param ref array $offerings
     *
     * @return array The sorted array
     *
     * @since 8/6/10
     */
    public function nameSort(array &$offerings)
    {
        $names = [];

        foreach ($offerings as $offering) {
            $names[] = $offering->getDisplayName();
        }

        array_multisort($names, \SORT_STRING, \SORT_ASC, array_keys($offerings), $offerings);

        return $offerings;
    }

    /**
     * Convert an Id to a string for hashing purposes.
     *
     * @return string
     *
     * @since 8/9/10
     */
    private function idToString(\osid_id_Id $id)
    {
        return $id->getIdentifierNamespace().':'.$id->getAuthority().':'.$id->getIdentifier();
    }
}
