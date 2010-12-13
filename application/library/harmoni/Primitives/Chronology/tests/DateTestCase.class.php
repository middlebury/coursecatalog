<?php
/** 
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DateTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 * @since 5/3/05
 */

require_once(dirname(__FILE__)."/../Date.class.php");

/**
 * A single unit test case. This class is intended to test one particular
 * class. Replace 'testedclass.php' below with the class you would like to
 * test.
 *
 * @since 5/3/05
 *
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DateTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

class DateTestCase extends UnitTestCase {
	
	/**
	*  Sets up unit test wide variables at the start
	*	 of each test method.
	*	 @access public
	*/
	function setUp() {
		// perhaps, initialize $obj here
	}
	
	/**
	 *	  Clears the data set in the setUp() method call.
	 *	  @access public
	 */
	function tearDown() {
		// perhaps, unset $obj here
	}
	
	/**
	 * Test the creation methods.
	 */ 
	function test_creation() {
		$epoch = Date::epoch();
		
		$this->assertEqual(strtolower(get_class($epoch)), 'date');
		$this->assertEqual($epoch->dayOfMonth(), 1);
		$this->assertEqual($epoch->dayOfYear(), 1);
		$this->assertEqual($epoch->startMonthIndex(), 1);
		$this->assertEqual($epoch->startMonthName(), 'January');
		$this->assertEqual($epoch->startMonthAbbreviation(), 'Jan');
		
		$duration =$epoch->duration();
		$this->assertTrue($duration->isEqualTo(Duration::withDays(1)));
	}
	
	/**
	 * Test instance creation from a string.
	 * 
	 */
	function test_from_string () {
		$date = Date::withYearMonthDay(2005, 8, 20);
		
		$this->assertTrue($date->isEqualTo(Date::fromString('2005-08-20')));
		$this->assertTrue($date->isEqualTo(Date::fromString('2005-08-20T15:25:10')));
		$this->assertTrue($date->isEqualTo(Date::fromString('20050820152510')));
		$this->assertTrue($date->isEqualTo(Date::fromString('08/20/2005')));
		$this->assertTrue($date->isEqualTo(Date::fromString('August 20, 2005')));
		$this->assertTrue($date->isEqualTo(Date::fromString('20aug05')));
	}
	
	/**
	 * Test add/subtract days
	 * 
	 */
	function test_add_subtract_days () {
		$date = Date::withYearMonthDay(2005, 5, 20);
		
		$result =$date->addDays(5);
		$this->assertTrue($result->isEqualTo(Date::withYearMonthDay(2005, 5, 25)));
		
		$result =$date->subtractDays(5);
		$this->assertTrue($result->isEqualTo(Date::withYearMonthDay(2005, 5, 15)));
	}
	
	/**
	 * Test previousDayNamed
	 * 
	 */
	function test_previousDayNamed () {
		// The 20th is a Friday
		$date = Date::withYearMonthDay(2005, 5, 20);
		$this->assertEqual($date->dayOfWeek(), 6);
		
		$result =$date->previousDayNamed('Thursday');
		$this->assertEqual($result->dayOfWeek(), 5);
		$this->assertTrue($result->isEqualTo(Date::withYearMonthDay(2005, 5, 19)));
		
		$result =$date->previousDayNamed('Wednesday');
		$this->assertEqual($result->dayOfWeek(), 4);
		$this->assertTrue($result->isEqualTo(Date::withYearMonthDay(2005, 5, 18)));
		
		$result =$date->previousDayNamed('Tuesday');
		$this->assertEqual($result->dayOfWeek(), 3);
		$this->assertTrue($result->isEqualTo(Date::withYearMonthDay(2005, 5, 17)));
		
		$result =$date->previousDayNamed('Monday');
		$this->assertEqual($result->dayOfWeek(), 2);
		$this->assertTrue($result->isEqualTo(Date::withYearMonthDay(2005, 5, 16)));
		
		$result =$date->previousDayNamed('Sunday');
		$this->assertEqual($result->dayOfWeek(), 1);
		$this->assertTrue($result->isEqualTo(Date::withYearMonthDay(2005, 5, 15)));
		
		$result =$date->previousDayNamed('Saturday');
		$this->assertEqual($result->dayOfWeek(), 7);
		$this->assertTrue($result->isEqualTo(Date::withYearMonthDay(2005, 5, 14)));
		
		$result =$date->previousDayNamed('Friday');
		$this->assertEqual($result->dayOfWeek(), 6);
		$this->assertTrue($result->isEqualTo(Date::withYearMonthDay(2005, 5, 13)));
	}
	
	/**
	 * Test printing
	 * 
	 */
	function test_printing () {
		$date = Date::withYearMonthDay(2005, 8, 20);
		
		$this->assertEqual($date->mmddyyyyString(), '08/20/2005');
		$this->assertEqual($date->yyyymmddString(), '2005-08-20');
		$this->assertEqual($date->printableString(), '20 August 2005');
		$this->assertEqual(
			$date->printableStringWithFormat(array(2, 1, 3, '/', 1, 1, 1)), 
			'8/20/2005');
		$this->assertEqual(
			$date->printableStringWithFormat(array(2, 1, 3, '/', 1, 2, 2)), 
			'08/20/05');
	}

/*********************************************************
 * Tests from parent class, Timespan.
 *********************************************************/
	
	/**
	 * Test comparisons
	 */ 
	function test_comparisons() {
		// Comparisons
		// isEqualTo()
		// isLessThan()
		
		$timespanA = Date::startingDuration(
				DateAndTime::withYearDay(1950, 1),
				Duration::withDays(10));
		$timespanB = Date::startingDuration(
				DateAndTime::withYearDay(1950, 2),
				Duration::withDays(1));
		
		$this->assertFalse($timespanA->isEqualTo($timespanB));
		$this->assertTrue($timespanA->isLessThan($timespanB));
		$this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
		$this->assertFalse($timespanA->isGreaterThan($timespanB));
		$this->assertFalse($timespanA->isGreaterThanOrEqualTo($timespanB));
		
		
		$timespanA = Date::starting(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)));
		$timespanB = Date::starting(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-5)));
		
		$this->assertTrue($timespanA->isEqualTo($timespanB));
		$this->assertFalse($timespanA->isLessThan($timespanB));
		$this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
		$this->assertFalse($timespanA->isGreaterThan($timespanB));
		$this->assertTrue($timespanA->isGreaterThanOrEqualTo($timespanB));
		
		
		$timespanA = Date::starting(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 3, 4, 16, 25, 10, Duration::withHours(-4)));
		$timespanB = Date::starting(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-5)));
		
		$this->assertFalse($timespanA->isEqualTo($timespanB));
		$this->assertTrue($timespanA->isLessThan($timespanB));
		$this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
		$this->assertFalse($timespanA->isGreaterThan($timespanB));
		$this->assertFalse($timespanA->isGreaterThanOrEqualTo($timespanB));
		
		
		$timespanA = Date::starting(DateAndTime::withYearMonthDay(2005, 5, 4));
		$timespanB = Date::starting(DateAndTime::withYearMonthDay(2005, 7, 4));
		$this->assertFalse($timespanA->isEqualTo($timespanB));		
		$this->assertTrue($timespanA->isLessThan($timespanB));
		$this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
		$this->assertFalse($timespanA->isGreaterThan($timespanB));
		$this->assertFalse($timespanA->isGreaterThanOrEqualTo($timespanB));
		
		$timespanA = Date::starting(DateAndTime::withYearMonthDay(2005, 5, 4));
		$timespanB = Date::starting(DateAndTime::withYearMonthDay(2005, 2, 4));
		$this->assertFalse($timespanA->isEqualTo($timespanB));		
		$this->assertFalse($timespanA->isLessThan($timespanB));
		$this->assertFalse($timespanA->isLessThanOrEqualTo($timespanB));
		$this->assertTrue($timespanA->isGreaterThan($timespanB));
		$this->assertTrue($timespanA->isGreaterThanOrEqualTo($timespanB));
		
	}
	
	/**
	 * Test aritmatic operations
	 * 
	 */
	function test_includes () {
		// includes()
		// includesAllOf()
		// includesAnyOf()
		
		
		// 0 0 0 0 0 0 0 0 1 1 1 1 1 1 1 1 1 1 2 2 2 2 2 2 2 2 2
		// 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8
		//
		// A  |- - - - - - - - - -|
		// B              |-|
		// C          |- - - - - - - - -|
		// D                            |- - - -|
		$timespanA = Date::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
				
		$timespanB = Date::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
				Duration::withDays(1));
				
		$timespanC = Date::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 8),
				DateAndTime::withYearMonthDay(
							2005, 5, 17));
		
		$timespanD = Date::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 17),
				DateAndTime::withYearMonthDay(
							2005, 5, 21));		
		$this->assertTrue($timespanA->includes($timespanB->asDateAndTime()));
		$this->assertFalse($timespanA->includes($timespanD->asDateAndTime()));
		$this->assertTrue($timespanA->includesAllOf($arg = array(
			$timespanB->asDateAndTime(), 
			$timespanC->asDateAndTime()
		)));
		$this->assertFalse($timespanA->includesAllOf($arg = array(
			$timespanB->asDateAndTime(), 
			$timespanC->asDateAndTime(),
			$timespanD->asDateAndTime()
		)));
		
		$this->assertTrue($timespanA->includesAnyOf($arg = array(
			$timespanB->asDateAndTime(), 
			$timespanC->asDateAndTime()
		)));
		$this->assertTrue($timespanA->includesAnyOf($arg = array(
			$timespanB->asDateAndTime(), 
			$timespanC->asDateAndTime(),
			$timespanD->asDateAndTime()
		)));
		$this->assertFalse($timespanA->includesAnyOf($arg = array(
			$timespanD->asDateAndTime()
		)));
	}

	/**
	 * Test aritmatic operations
	 * 
	 */
	function test_operations () {
		// Operations
		// plus()
		// minus()
		
		
		// 0 0 0 0 0 0 0 0 1 1 1 1 1 1 1 1 1 1 2 2 2 2 2 2 2 2 2
		// 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8
		//
		// A  |- - - - - - - - - -|
		// B              |-|
		// C          |- - - - - - - - -|
		// D                            |- - - -|
		$timespanA = Date::starting(
				DateAndTime::withYearMonthDay(
							2005, 5, 4));
				
		$timespanB = Date::starting(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)));
				
		$timespanC = Date::startingDuration(
				DateAndTime::withYearMonthDay(
							2005, 5, 8),
				Duration::withDays(9));
		
		$timespanD = Date::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 17),
				DateAndTime::withYearMonthDay(
							2005, 5, 21));
							
		
		// plus()
		$temp = Timespan::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 12),
				DateAndTime::withYearMonthDay(
							2005, 5, 21));
		$this->assertTrue($temp->isEqualTo($timespanC->plus(Duration::withDays(4))));
		$this->assertTrue($temp->isEqualTo($timespanC->plus($timespanD->asDuration())));
		
		
		// minus()
		// Subtracting an object that implemnts asDateAndTime
		$temp = Timespan::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 3),
				DateAndTime::withYearMonthDay(
							2005, 5, 12));
		$this->assertTrue($temp->isEqualTo($timespanC->minus(Duration::withDays(5))));
		
		$tempDuration = Duration::withDays(4);
		$this->assertTrue($tempDuration->isEqualTo($timespanC->minus($timespanA)));
		
		$tempDuration = Duration::withDays(-4);
		$this->assertTrue($tempDuration->isEqualTo($timespanA->minus($timespanC)));
		$tempDuration = Duration::zero();
		$this->assertTrue($tempDuration->isEqualTo($timespanA->minus($timespanA)));
	}
	
	/**
	 * Test aritmatic operations
	 * 
	 */
	function test_operations_next_prev () {
		// Operations
		// next()
		// previous()
		
		
		// 0 0 0 0 0 0 0 0 1 1 1 1 1 1 1 1 1 1 2 2 2 2 2 2 2 2 2
		// 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8
		//
		// A  |- - - - - - - - - -|
		// B              |-|
		// C          |- - - - - - - - -|
		// D                            |- - - -|
		$timespanA = Date::startingDuration(
				DateAndTime::withYearMonthDay(
							2005, 5, 4),
				Duration::withDays(10));
				
		$timespanB = Date::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
				Duration::withDays(1));
				
		$timespanC = Date::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 8),
				DateAndTime::withYearMonthDay(
							2005, 5, 17));
		
		$timespanD = Date::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 17),
				DateAndTime::withYearMonthDay(
							2005, 5, 21));
		
		
		$temp = Date::startingDuration(
				DateAndTime::withYearMonthDay(
							2005, 5, 14),
				Duration::withDays(10));
		$this->assertTrue($temp->isEqualTo($timespanA->next()));
		$temp = Date::startingDuration(
				DateAndTime::withYearMonthDay(
							2005, 4, 24),
				Duration::withDays(10));
		$this->assertTrue($temp->isEqualTo($timespanA->previous()));
	}
	
	/**
	 * Test aritmatic operations
	 * 
	 */
	function test_intersect_union () {
		// intersection()
		// union()
		
		
		// 0 0 0 0 0 0 0 0 1 1 1 1 1 1 1 1 1 1 2 2 2 2 2 2 2 2 2
		// 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8
		//
		// A  |- - - - - - - - - -|
		// B              |-|
		// C          |- - - - - - - - -|
		// D                            |- - - -|
		$timespanA = Date::startingDuration(
				DateAndTime::withYearMonthDay(
							2005, 5, 4),
				Duration::withDays(10));
				
		$timespanB = Date::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
				Duration::withDays(1));
				
		$timespanC = Date::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 8),
				DateAndTime::withYearMonthDay(
							2005, 5, 17));
		
		$timespanD = Date::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 17),
				DateAndTime::withYearMonthDay(
							2005, 5, 21));
		

		// intersection()
		$duration = Duration::withDays(1);
		$temp = Timespan::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
				$duration->minus(DateAndTime::clockPrecision()));
		
		$result =$timespanA->intersection($timespanB);
		
		$this->assertTrue($temp->isEqualTo($result));
		
		$tempEnd = DateAndTime::withYearMonthDay(
							2005, 5, 14);
		$temp = Timespan::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 8),
							$tempEnd->minus(DateAndTime::clockPrecision()
				));
		$this->assertTrue($temp->isEqualTo($timespanA->intersection($timespanC)));
		
		$this->assertEqual($timespanA->intersection($timespanD), NULL);
		
		
		// union()
		$this->assertTrue($timespanA->isEqualTo($timespanA->union($timespanB)));
		
		$temp = Timespan::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 4),
				DateAndTime::withYearMonthDay(
							2005, 5, 17));
		$this->assertTrue($temp->isEqualTo($timespanA->union($timespanC)));
		
		$temp = Timespan::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 4),
				DateAndTime::withYearMonthDay(
							2005, 5, 21));
		$this->assertTrue($temp->isEqualTo($timespanA->union($timespanD)));
	}
	
	/**
	 * Test Accessing Methods
	 * 
	 */
	function test_accessing () {
		$timespan = Date::starting(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)));
		// day()
		$this->assertEqual($timespan->day(), 124);
		
		// dayOfMonth()
		$this->assertEqual($timespan->dayOfMonth(), 4);
		
		// dayOfWeek()
		$this->assertEqual($timespan->dayOfWeek(), 4);
		
		// dayOfWeekName()
		$this->assertEqual($timespan->dayOfWeekName(), 'Wednesday');
		
		// dayOfYear()
		$this->assertEqual($timespan->dayOfYear(), 124);
		
		// daysInMonth()
		$this->assertEqual($timespan->daysInMonth(), 31);
		
		// daysInYear()
		$this->assertEqual($timespan->daysInYear(), 365);
		
		// daysLeftInYear()
		$this->assertEqual($timespan->daysLeftInYear(), 241);
		
		// duration()
		$temp = Duration::withDays(1);
		$this->assertTrue($temp->isEqualTo($timespan->duration()));
		
		// end()
		$temp = DateAndTime::withYearMonthDay(2005, 5, 5);
		$temp =$temp->minus(DateAndTime::clockPrecision());
		$this->assertTrue($temp->isEqualTo($timespan->end()));
		
		// firstDayOfMonth()
		$this->assertEqual($timespan->firstDayOfMonth(), 121);
		
		// isLeapYear()
		$this->assertEqual($timespan->isLeapYear(), FALSE);
		
		// julianDayNumber()
		$this->assertEqual($timespan->julianDayNumber(), 2453495);
		
		// printableString()
		$this->assertEqual($timespan->printableString(), '4 May 2005');
		
		// startMonth()
		$this->assertEqual($timespan->startMonth(), 5);
		
		// startMonthAbbreviation()
		$this->assertEqual($timespan->startMonthAbbreviation(), 'May');
		
		// startMonthIndex()
		$this->assertEqual($timespan->startMonthIndex(), 5);
		
		// startMonthName()
		$this->assertEqual($timespan->startMonthName(), 'May');
		
		// start()
		$temp = DateAndTime::withYearMonthDay(2005, 5, 4);
		$this->assertTrue($temp->isEqualTo($timespan->start()));
		
		// startYear()
		$this->assertEqual($timespan->startYear(), 2005);
	
	}
	
	/**
	 * Test Accessing Methods
	 * 
	 */
	function test_enumeration () {
		$timespan = Date::starting(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)));
		
		$timespanB = Date::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 0, 0, 0, Duration::withHours(-4)),
				Duration::withDays(1000));
		
		
		// every()
		$everyTwo =$timespan->every(Duration::withDays(2));
		$this->assertEqual(count($everyTwo), 1);
		for ($i = 0; $i < 1; $i++)
			$this->assertEqual(strtolower(get_class($everyTwo[$i])), 'dateandtime');
		
		$temp = DateAndTime::withYearMonthDayHourMinuteSecond(
							2005, 5, 4, 0, 0, 0);
		$this->assertTrue($temp->isEqualTo($everyTwo[0]));
		

		// dates()
		$dates =$timespan->dates();
		$this->assertEqual(count($dates), 1);
		for ($i = 0; $i < 1; $i++)
			$this->assertEqual(strtolower(get_class($dates[$i])), 'date');
		
		$temp = Date::withYearMonthDay(2005, 5, 4);
		$this->assertTrue($temp->isEqualTo($dates[0]));
		
		
		// months()
		$months =$timespan->months();
		$this->assertEqual(count($months), 1);
		for ($i = 0; $i < 1; $i++)
			$this->assertEqual(strtolower(get_class($months[$i])), 'month');
		
		$temp = Month::withMonthYear(5, 2005);
		$this->assertTrue($temp->isEqualTo($months[0]));
		
		$months =$timespanB->months();
		$this->assertEqual(count($months), 33);
		
		for ($i = 0; $i < 1; $i++)
			$this->assertEqual(strtolower(get_class($months[$i])), 'month');
		
		$temp = Month::withMonthYear(5, 2005);
		$this->assertTrue($temp->isEqualTo($months[0]));
		
		
		// weeks()
 		$weeks =$timespan->weeks();
 		$this->assertEqual(count($weeks), 1);
 		for ($i = 0; $i < 1; $i++)
 			$this->assertEqual(strtolower(get_class($weeks[$i])), 'week');
 		
 		$temp = Week::starting(Date::withYearMonthDay(2005, 5, 4));
 		$this->assertTrue($temp->isEqualTo($weeks[0]));
		
		// years()
		$years =$timespan->years();
		$this->assertEqual(count($years), 1);
		for ($i = 0; $i < 1; $i++)
			$this->assertEqual(strtolower(get_class($years[$i])), 'year');
		
		$this->assertEqual($years[0]->startYear(), 2005);
	
	}
	
	/**
	 * Test Converting Methods
	 * 
	 */
	function test_converting () {
		// Converting
		$timespan = Date::starting(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)));
		
		// asDate()
		$temp =$timespan->asDate();
		$this->assertTrue($temp->isEqualTo(Date::withYearMonthDay(2005, 5, 4)));
		
		// asDateAndTime()
		$temp =$timespan->asDateAndTime();
		$this->assertTrue($temp->isEqualTo(
			DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 0, 0, 0, Duration::withHours(-4))));
		
		// asDuration()
		$temp =$timespan->asDuration();
		$this->assertTrue($temp->isEqualTo(Duration::withDays(1)));
		
		// asMonth()
		$temp =$timespan->asMonth();
		$this->assertTrue($temp->isEqualTo(Month::withMonthYear(5, 2005)));
		
		// asTime()
		$temp =$timespan->asTime();
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecond(
							2005, 5, 4, 0, 0, 0);
		$this->assertTrue($temp->isEqualTo($dateAndTime->asTime()));
		
		// asTimeStamp()
		$temp =$timespan->asTimeStamp();
		$this->assertTrue($temp->isEqualTo(
			TimeStamp::withYearMonthDayHourMinuteSecond(
							2005, 5, 4, 0, 0, 0)));
		
		// asWeek()
		$temp =$timespan->asWeek();
		$this->assertTrue($temp->isEqualTo(Week::starting(Date::withYearMonthDay(2005, 5, 1))));
		
		// asYear()
		$temp =$timespan->asYear();
		$this->assertTrue($temp->isEqualTo(Year::starting(Date::withYearMonthDay(2005, 5, 4))));
		
		// to()
		$temp =$timespan->to(Date::withYearMonthDay(2005, 10, 1));
		$comparison = Timespan::startingEnding(
				DateAndTime::withYearMonthDayHourMinuteSecond(
							2005, 5, 4, 0, 0, 0),
				Date::withYearMonthDay(2005, 10, 1));
		$this->assertTrue($temp->isEqualTo($comparison));
		
	}

	
}
?>