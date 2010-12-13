<?php
/** 
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: MonthTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 * @since 5/3/05
 */

require_once(dirname(__FILE__)."/../Month.class.php");

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
 * @version $Id: MonthTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

class MonthTestCase extends UnitTestCase {
	
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
		$epochMonth = Month::epoch();
		
		$this->assertEqual(strtolower(get_class($epochMonth)), 'month');
		$this->assertEqual($epochMonth->dayOfMonth(), 1);
		$this->assertEqual($epochMonth->dayOfYear(), 1);
		$this->assertEqual($epochMonth->daysInMonth(), 31);
		$this->assertEqual($epochMonth->startMonthIndex(), 1);
		$this->assertEqual($epochMonth->startMonthName(), 'January');
		$this->assertEqual($epochMonth->startMonthAbbreviation(), 'Jan');
		
		$duration =$epochMonth->duration();
		$this->assertTrue($duration->isEqualTo(Duration::withDays(31)));
	}
	
	/**
	 * Test instance creation from a string.
	 * 
	 */
	function test_from_string () {
		$date = Month::withMonthYear(8, 2005);
		
		$this->assertTrue($date->isEqualTo(Month::fromString('2005-08-20')));
		$this->assertTrue($date->isEqualTo(Month::fromString('2005-08-20T15:25:10')));
		$this->assertTrue($date->isEqualTo(Month::fromString('20050820152510')));
		$this->assertTrue($date->isEqualTo(Month::fromString('08/20/2005')));
		$this->assertTrue($date->isEqualTo(Month::fromString('August 20, 2005')));
		$this->assertTrue($date->isEqualTo(Month::fromString('20aug05')));
		$this->assertTrue($date->isEqualTo(Month::fromString('August 2005')));
		$this->assertTrue($date->isEqualTo(Month::fromString('aug05')));
		$this->assertTrue($date->isEqualTo(Month::fromString('2005-08')));
		$this->assertTrue($date->isEqualTo(Month::fromString('200508')));
	}
	
	/**
	 * Test days in month
	 */ 
	function test_days_In_Month() {
		$this->assertEqual(Month::daysInMonthForYear(1, 1999), 31);
		$this->assertEqual(Month::daysInMonthForYear(2, 1999), 28);
		$this->assertEqual(Month::daysInMonthForYear(3, 1999), 31);
		$this->assertEqual(Month::daysInMonthForYear(4, 1999), 30);
		$this->assertEqual(Month::daysInMonthForYear(5, 1999), 31);
		$this->assertEqual(Month::daysInMonthForYear(6, 1999), 30);
		$this->assertEqual(Month::daysInMonthForYear(7, 1999), 31);
		$this->assertEqual(Month::daysInMonthForYear(8, 1999), 31);
		$this->assertEqual(Month::daysInMonthForYear(9, 1999), 30);
		$this->assertEqual(Month::daysInMonthForYear(10, 1999), 31);
		$this->assertEqual(Month::daysInMonthForYear(11, 1999), 30);
		$this->assertEqual(Month::daysInMonthForYear(12, 1999), 31);
		
		$this->assertEqual(Month::daysInMonthForYear(1, 2000), 31);
		$this->assertEqual(Month::daysInMonthForYear(2, 2000), 29);
		$this->assertEqual(Month::daysInMonthForYear(3, 2000), 31);
		$this->assertEqual(Month::daysInMonthForYear(4, 2000), 30);
		$this->assertEqual(Month::daysInMonthForYear(5, 2000), 31);
		$this->assertEqual(Month::daysInMonthForYear(6, 2000), 30);
		$this->assertEqual(Month::daysInMonthForYear(7, 2000), 31);
		$this->assertEqual(Month::daysInMonthForYear(8, 2000), 31);
		$this->assertEqual(Month::daysInMonthForYear(9, 2000), 30);
		$this->assertEqual(Month::daysInMonthForYear(10, 2000), 31);
		$this->assertEqual(Month::daysInMonthForYear(11, 2000), 30);
		$this->assertEqual(Month::daysInMonthForYear(12, 2000), 31);
	}
	
	/**
	 * Test name and index
	 */
	function test_name_index () {
		$month = Month::withMonthYear(5, 2005);
		
		$this->assertEqual($month->index(), 5);
		$this->assertEqual($month->name(), 'May');
	}
	
	/**
	 * Test printing
	 */
	function test_printing () {
		$month = Month::withMonthYear(8, 2005);
		
		$this->assertEqual($month->printableString(), 'August 2005');
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
		
		$timespanA = Month::startingDuration(
				DateAndTime::withYearDay(1950, 1),
				Duration::withDays(10));
		$timespanB = Month::startingDuration(
				DateAndTime::withYearDay(1950, 2),
				Duration::withDays(1));
		
		$this->assertTrue($timespanA->isEqualTo($timespanB));
		$this->assertFalse($timespanA->isLessThan($timespanB));
		$this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
		$this->assertFalse($timespanA->isGreaterThan($timespanB));
		$this->assertTrue($timespanA->isGreaterThanOrEqualTo($timespanB));
		
		
		$timespanA = Month::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
		$timespanB = Month::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-5)),
				Duration::withDays(1));
		
		$this->assertTrue($timespanA->isEqualTo($timespanB));
		$this->assertFalse($timespanA->isLessThan($timespanB));
		$this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
		$this->assertFalse($timespanA->isGreaterThan($timespanB));
		$this->assertTrue($timespanA->isGreaterThanOrEqualTo($timespanB));
		
		
		$timespanA = Month::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 3, 4, 16, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
		$timespanB = Month::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-5)),
				Duration::withDays(10));
		
		$this->assertFalse($timespanA->isEqualTo($timespanB));
		$this->assertTrue($timespanA->isLessThan($timespanB));
		$this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
		$this->assertFalse($timespanA->isGreaterThan($timespanB));
		$this->assertFalse($timespanA->isGreaterThanOrEqualTo($timespanB));
		
		
		$timespanA = Month::starting(DateAndTime::withYearMonthDay(2005, 5, 4));
		$timespanB = Month::starting(DateAndTime::withYearMonthDay(2005, 7, 4));
		$this->assertFalse($timespanA->isEqualTo($timespanB));		
		$this->assertTrue($timespanA->isLessThan($timespanB));
		$this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
		$this->assertFalse($timespanA->isGreaterThan($timespanB));
		$this->assertFalse($timespanA->isGreaterThanOrEqualTo($timespanB));
		
		$timespanA = Month::starting(DateAndTime::withYearMonthDay(2005, 5, 4));
		$timespanB = Month::starting(DateAndTime::withYearMonthDay(2005, 2, 4));
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
		
		
		// 0 0 0 0 0 0 0 0 1 1 1 1 1 1 1 1 1 1 2 2 2 2 2 2 2 2 2 2 3 3 0 0 0
		// 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4
		//
		// A  |- - - - - - - - - -|
		// B              |-|
		// C                                  |- - - - - - - - -|
		// D                                                          |- - - -|
		$timespanA = Month::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
				
		$timespanB = Month::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
				Duration::withDays(1));
				
		$timespanC = Month::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 20),
				DateAndTime::withYearMonthDay(
							2005, 5, 29));
		
		$timespanD = Month::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 6, 1),
				DateAndTime::withYearMonthDay(
							2005, 6, 6));		
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
		
		
		// 0 0 0 0 0 0 0 0 1 1 1 1 1 1 1 1 1 1 2 2 2 2 2 2 2 2 2 2 3 3 0 0 0
		// 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4
		//
		// A  |- - - - - - - - - -|
		// B              |-|
		// C                                  |- - - - - - - - -|
		// D                                                          |- - - -|
		$timespanA = Month::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
				
		$timespanB = Month::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
				Duration::withDays(1));
				
		$timespanC = Month::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 20),
				DateAndTime::withYearMonthDay(
							2005, 5, 29));
		
		$timespanD = Month::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 6, 1),
				DateAndTime::withYearMonthDay(
							2005, 6, 6));		
							
		
		// plus()
		$temp = Month::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 8),
				DateAndTime::withYearMonthDay(
							2005, 5, 21));
		$this->assertTrue($temp->isEqualTo($timespanC->plus(Duration::withDays(4))));
		$this->assertTrue($temp->isEqualTo($timespanC->plus($timespanD->asDuration())));
		
		
		// minus()
		// Subtracting an object that implemnts asDateAndTime
		$temp = Month::starting(
				DateAndTime::withYearMonthDay(
							2005, 4, 1));
		$result =$timespanC->minus(Duration::withDays(5));
		$this->assertTrue($temp->isEqualTo($result));
		
		$tempDuration = Duration::withDays(-31);
		$this->assertTrue($tempDuration->isEqualTo($timespanC->minus($timespanD)));
		
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
		
		
		// 0 0 0 0 0 0 0 0 1 1 1 1 1 1 1 1 1 1 2 2 2 2 2 2 2 2 2 2 3 3 0 0 0
		// 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4
		//
		// A  |- - - - - - - - - -|
		// B              |-|
		// C                                  |- - - - - - - - -|
		// D                                                          |- - - -|
		$timespanA = Month::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
				
		$timespanB = Month::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
				Duration::withDays(1));
				
		$timespanC = Month::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 20),
				DateAndTime::withYearMonthDay(
							2005, 5, 29));
		
		$timespanD = Month::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 6, 1),
				DateAndTime::withYearMonthDay(
							2005, 6, 6));		
		
		
		$temp = Month::starting(DateAndTime::withYearMonthDay(2005, 6, 1));
		$this->assertTrue($temp->isEqualTo($timespanA->next()));
		$temp = Month::starting(DateAndTime::withYearMonthDay(2005, 4, 1));
		$this->assertTrue($temp->isEqualTo($timespanA->previous()));
	}
	
	/**
	 * Test aritmatic operations
	 * 
	 */
	function test_intersect_union () {
		// intersection()
		// union()
		
		
		// 0 0 0 0 0 0 0 0 1 1 1 1 1 1 1 1 1 1 2 2 2 2 2 2 2 2 2 2 3 3 0 0 0
		// 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4
		//
		// A  |- - - - - - - - - -|
		// B              |-|
		// C                                  |- - - - - - - - -|
		// D                                                          |- - - -|
		$timespanA = Month::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
				
		$timespanB = Month::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
				Duration::withDays(1));
				
		$timespanC = Month::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 20),
				DateAndTime::withYearMonthDay(
							2005, 5, 29));
		
		$timespanD = Month::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 6, 1),
				DateAndTime::withYearMonthDay(
							2005, 6, 6));		
		

		// intersection()
		$this->assertTrue($timespanA->isEqualTo($timespanA->intersection($timespanB)));
		
		$this->assertTrue($timespanA->isEqualTo($timespanA->intersection($timespanC)));
		
		$this->assertEqual($timespanA->intersection($timespanD), NULL);
		
		
		// union()
		$temp = Timespan::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 1),
				DateAndTime::withYearMonthDay(
							2005, 6, 1));
		
		$this->assertTrue($temp->isEqualTo($timespanA->union($timespanB)));
		
		$temp = Timespan::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 1),
				DateAndTime::withYearMonthDay(
							2005, 6, 1));
		$this->assertTrue($temp->isEqualTo($timespanA->union($timespanC)));
		
		$temp = Timespan::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 1),
				DateAndTime::withYearMonthDay(
							2005, 7, 1));
		$this->assertTrue($temp->isEqualTo($timespanA->union($timespanD)));
	}
	
	/**
	 * Test Accessing Methods
	 * 
	 */
	function test_accessing () {
		$timespan = Month::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
		// day()
		$this->assertEqual($timespan->day(), 121);
		
		// dayOfMonth()
		$this->assertEqual($timespan->dayOfMonth(), 1);
		
		// dayOfWeek()
		$this->assertEqual($timespan->dayOfWeek(), 1);
		
		// dayOfWeekName()
		$this->assertEqual($timespan->dayOfWeekName(), 'Sunday');
		
		// dayOfYear()
		$this->assertEqual($timespan->dayOfYear(), 121);
		
		// daysInMonth()
		$this->assertEqual($timespan->daysInMonth(), 31);
		
		// daysInYear()
		$this->assertEqual($timespan->daysInYear(), 365);
		
		// daysLeftInYear()
		$this->assertEqual($timespan->daysLeftInYear(), 244);
		
		// duration()
		$temp = Duration::withDays(31);
		$this->assertTrue($temp->isEqualTo($timespan->duration()));
		
		// end()
		$temp = DateAndTime::withYearMonthDay(2005, 6, 1);
		$temp =$temp->minus(DateAndTime::clockPrecision());
		$this->assertTrue($temp->isEqualTo($timespan->end()));
		
		// firstDayOfMonth()
		$this->assertEqual($timespan->firstDayOfMonth(), 121);
		
		// isLeapYear()
		$this->assertEqual($timespan->isLeapYear(), FALSE);
		
		// julianDayNumber()
		$this->assertEqual($timespan->julianDayNumber(), 2453492);
		
		// printableString()
		$this->assertEqual($timespan->printableString(), 'May 2005');
		
		// startMonth()
		$this->assertEqual($timespan->startMonth(), 5);
		
		// startMonthAbbreviation()
		$this->assertEqual($timespan->startMonthAbbreviation(), 'May');
		
		// startMonthIndex()
		$this->assertEqual($timespan->startMonthIndex(), 5);
		
		// startMonthName()
		$this->assertEqual($timespan->startMonthName(), 'May');
		
		// start()
		$temp = DateAndTime::withYearMonthDay(2005, 5, 1);
		$this->assertTrue($temp->isEqualTo($timespan->start()));
		
		// startYear()
		$this->assertEqual($timespan->startYear(), 2005);
	
	}
	
	/**
	 * Test Accessing Methods
	 * 
	 */
	function test_enumeration () {
		$timespan = Month::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
		
		$timespanB = Month::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(1000));
		
		
		// every()
		$everyTwo =$timespan->every(Duration::withDays(2));
		$this->assertEqual(count($everyTwo), 16);
		for ($i = 0; $i < 16; $i++)
			$this->assertEqual(strtolower(get_class($everyTwo[$i])), 'dateandtime');
		
		$temp = DateAndTime::withYearMonthDayHourMinuteSecond(
							2005, 5, 3, 0, 0, 0);
		$this->assertTrue($temp->isEqualTo($everyTwo[1]));
		

		// dates()
		$dates =$timespan->dates();
		$this->assertEqual(count($dates), 31);
		for ($i = 0; $i < 7; $i++)
			$this->assertEqual(strtolower(get_class($dates[$i])), 'date');
		
		$temp = Date::withYearMonthDay(2005, 5, 1);
		$this->assertTrue($temp->isEqualTo($dates[0]));
		$temp = Date::withYearMonthDay(2005, 5, 2);
		$this->assertTrue($temp->isEqualTo($dates[1]));
		$temp = Date::withYearMonthDay(2005, 5, 3);
		$this->assertTrue($temp->isEqualTo($dates[2]));
		$temp = Date::withYearMonthDay(2005, 5, 7);
		$this->assertTrue($temp->isEqualTo($dates[6]));
		
		
		// months()
		$months =$timespan->months();
		$this->assertEqual(count($months), 1);
		for ($i = 0; $i < 1; $i++)
			$this->assertEqual(strtolower(get_class($months[$i])), 'month');
		
		$temp = Month::withMonthYear(5, 2005);
		$this->assertTrue($temp->isEqualTo($months[0]));
		
		$months =$timespanB->months();
		$this->assertEqual(count($months), 1);
		
		for ($i = 0; $i < 1; $i++)
			$this->assertEqual(strtolower(get_class($months[$i])), 'month');
		
		$temp = Month::withMonthYear(5, 2005);
		$this->assertTrue($temp->isEqualTo($months[0]));
		
		
		// weeks()
 		$weeks =$timespan->weeks();
 		$this->assertEqual(count($weeks), 5);
 		for ($i = 0; $i < 5; $i++)
 			$this->assertEqual(strtolower(get_class($weeks[$i])), 'week');
 		
 		$temp = Week::starting(Date::withYearMonthDay(2005, 5, 1));
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
		$timespan = Month::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
		
		// asDate()
		$temp =$timespan->asDate();
		$this->assertTrue($temp->isEqualTo(Date::withYearMonthDay(2005, 5, 1)));
		
		// asDateAndTime()
		$temp =$timespan->asDateAndTime();
		$this->assertTrue($temp->isEqualTo(
			DateAndTime::withYearMonthDayHourMinuteSecond(
							2005, 5, 1, 00, 00, 00)));
		
		// asDuration()
		$temp =$timespan->asDuration();
		$this->assertTrue($temp->isEqualTo(Duration::withDays(31)));
		
		// asMonth()
		$temp =$timespan->asMonth();
		$this->assertTrue($temp->isEqualTo(Month::withMonthYear(5, 2005)));
		
		// asTime()
		$temp =$timespan->asTime();
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecond(
							2005, 5, 1, 0, 0, 0);
		$this->assertTrue($temp->isEqualTo($dateAndTime->asTime()));
		
		// asTimeStamp()
		$temp =$timespan->asTimeStamp();
		$this->assertTrue($temp->isEqualTo(
			TimeStamp::withYearMonthDayHourMinuteSecond(
							2005, 5, 1, 0, 0, 0)));
		
		// asWeek()
		$temp =$timespan->asWeek();
		$this->assertTrue($temp->isEqualTo(Week::starting(Date::withYearMonthDay(2005, 5, 1))));
		
		// asYear()
		$temp =$timespan->asYear();
		$this->assertTrue($temp->isEqualTo(Year::starting(Date::withYearMonthDay(2005, 5, 1))));
		
		// to()
		$temp =$timespan->to(Date::withYearMonthDay(2005, 10, 1));
		$comparison = Timespan::startingEnding(
				DateAndTime::withYearMonthDayHourMinuteSecond(
							2005, 5, 1, 0, 0, 0),
				Date::withYearMonthDay(2005, 10, 1));
		$this->assertTrue($temp->isEqualTo($comparison));
		
	}
}
?>