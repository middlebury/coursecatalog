<?php
/** 
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ScheduleTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 * @since 5/25/05
 */

require_once(dirname(__FILE__)."/../Schedule.class.php");

/**
 * A single unit test case. This class is intended to test one particular
 * class. Replace 'testedclass.php' below with the class you would like to
 * test.
 *
 * @since 5/25/05
 *
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ScheduleTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

class ScheduleTestCase extends UnitTestCase {
	
	/**
	*  Sets up unit test wide variables at the start
	*	 of each test method.
	*	 @access public
	*/
	function setUp() {
		$this->currentYear = date('Y');
	}
	
	/**
	 *	  Clears the data set in the setUp() method call.
	 *	  @access public
	 */
	function tearDown() {
		// perhaps, unset $obj here
	}
	
	
	/**
	 * Test the enumeration methods.
	 */ 
	function test_singleduration_enumeration() {
		$schedule = Schedule::startingDuration(
			DateAndTime::withYearMonthDay(2005, 5, 15),
			Duration::withDays(7));
		$durations = array();
		$durations[] = Duration::withDays(1);
		$schedule->setSchedule($durations);
		
		$this->assertEqual($schedule->getSchedule(), $durations);
		
		$datesAndTimes = array();
		$datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 15);
		$datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 16);
		$datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 17);
		$datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 18);
		$datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 19);
		$datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 20);
		$datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 21);
		
		$this->assertEqual($schedule->dateAndTimes(), $datesAndTimes);
		
		$datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 22);
		$this->assertNotEqual($schedule->dateAndTimes(), $datesAndTimes);
		
		
		$datesAndTimes = array();
		$datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 17);
		$datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 18);
		$datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 19);
		
		$this->assertEqual($schedule->between(
				DateAndTime::withYearMonthDay(2005, 5, 17),
				DateAndTime::withYearMonthDay(2005, 5, 19)), 
			$datesAndTimes);
	}
	
	/**
	 * Test the enumeration methods.
	 */ 
	function test_multipleduration_enumeration() {
		$schedule = Schedule::startingDuration(
			DateAndTime::withYearMonthDay(2005, 5, 15),
			Duration::withDays(7));
		$durations = array();
		$durations[] = Duration::withDays(1);
		$durations[] = Duration::withHours(1);
		$schedule->setSchedule($durations);
		
		$this->assertEqual($schedule->getSchedule(), $durations);
		
		$datesAndTimes = array();
		$datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 15, 0, 0, 0);
		$datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 16, 0, 0, 0);
		$datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 16, 1, 0, 0);
		$datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 17, 1, 0, 0);
		$datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 17, 2, 0, 0);
		$datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 18, 2, 0, 0);
		$datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 18, 3, 0, 0);
		$datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 19, 3, 0, 0);
		$datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 19, 4, 0, 0);
		$datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 20, 4, 0, 0);
		$datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 20, 5, 0, 0);
		$datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 21, 5, 0, 0);
		$datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 21, 6, 0, 0);
		
		$this->assertEqual($schedule->dateAndTimes(), $datesAndTimes);
		
		$datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 21, 5, 0, 0);
		$this->assertNotEqual($schedule->dateAndTimes(), $datesAndTimes);
		
		
		$datesAndTimes = array();
		$datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 17, 1, 0, 0);
		$datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 18, 1, 0, 0);
		$datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 18, 2, 0, 0);
		
		$this->assertEqual($schedule->between(
				DateAndTime::withYearMonthDay(2005, 5, 17),
				DateAndTime::withYearMonthDay(2005, 5, 19)), 
			$datesAndTimes);
	}
	
	/**
	 * Test the creation methods.
	 */ 
	function test_creation() {
		// class methods - instance creation
		// current()
		// epoch()
		// starting()
		// startingDuration()
		// startingEnding()

		$timespan = Schedule::current();
		$this->assertEqual($timespan->startYear(), intval(date('Y')));
		$this->assertEqual($timespan->startMonth(), intval(date('n')));
		$this->assertEqual($timespan->dayOfMonth(), intval(date('j')));
		$duration =$timespan->duration();
		$this->assertTrue($duration->isEqualTo(Duration::zero()));
		$this->assertEqual(strtolower(get_class($timespan)), 'schedule');
		
		$timespan = Schedule::epoch();
		$this->assertEqual($timespan->startYear(), 1901);
		$this->assertEqual($timespan->startMonth(), 1);
		$this->assertEqual($timespan->dayOfMonth(), 1);
		$duration =$timespan->duration();
		$this->assertTrue($duration->isEqualTo(Duration::zero()));
		$this->assertEqual(strtolower(get_class($timespan)), 'schedule');
	}
	
	/**
	 * Test some leap years.
	 * 
	 */
	function test_end() {
		$datA = DateAndTime::withYearDay(2005, 125);
		$datB = DateAndTime::withYearDay(2006, 125);
		
		$timespan = Schedule::startingDuration(
				DateAndTime::withYearDay(2005, 125),
				Duration::withDays(365)
			);
		
		$this->assertEqual($timespan->startYear(), 2005);
		$this->assertEqual($timespan->dayOfYear(), 125);
		$duration =$timespan->duration();
		$this->assertTrue($duration->isEqualTo(Duration::withDays(365)));
		$end =$timespan->end();
		$this->assertEqual($end->julianDayNumber(), 2453860);
		$this->assertEqual(($end->julianDayNumber() - $datA->julianDayNumber()), 364);
		$this->assertEqual($end->year(), 2006);
		$this->assertEqual($end->dayOfYear(), 124);
		$this->assertTrue($end->isEqualTo(DateAndTime::withYearDayHourMinuteSecond(
			2006, 124, 23, 59, 59)));
	}
}
?>