<?php
/** 
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: TimespanTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 * @since 5/3/05
 */

require_once(dirname(__FILE__)."/../Timespan.class.php");

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
 * @version $Id: TimespanTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

class TimespanTestCase extends UnitTestCase {
	
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
	 * Test the creation methods.
	 */ 
	function test_creation() {
		// class methods - instance creation
		// current()
		// epoch()
		// starting()
		// startingDuration()
		// startingEnding()

		$timespan = Timespan::current();
		$this->assertEqual($timespan->startYear(), intval(date('Y')));
		$this->assertEqual($timespan->startMonth(), intval(date('n')));
		$this->assertEqual($timespan->dayOfMonth(), intval(date('j')));
		$duration =$timespan->duration();
		$this->assertTrue($duration->isEqualTo(Duration::zero()));
		$this->assertEqual(strtolower(get_class($timespan)), 'timespan');
		
		$timespan = Timespan::epoch();
		$this->assertEqual($timespan->startYear(), 1901);
		$this->assertEqual($timespan->startMonth(), 1);
		$this->assertEqual($timespan->dayOfMonth(), 1);
		$duration =$timespan->duration();
		$this->assertTrue($duration->isEqualTo(Duration::zero()));
		$this->assertEqual(strtolower(get_class($timespan)), 'timespan');
	}
	
	/**
	 * Test some leap years.
	 * 
	 */
	function test_end() {
		$datA = DateAndTime::withYearDay(2005, 125);
		$datB = DateAndTime::withYearDay(2006, 125);
		
		$timespan = Timespan::startingDuration(
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
	
	/**
	 * Test comparisons
	 */ 
	function test_comparisons() {
		// Comparisons
		// isEqualTo()
		// isLessThan()
		
		$timespanA = Timespan::startingDuration(
				DateAndTime::withYearDay(1950, 1),
				Duration::withDays(10));
		$timespanB = Timespan::startingDuration(
				DateAndTime::withYearDay(1950, 2),
				Duration::withDays(1));
		
		$this->assertFalse($timespanA->isEqualTo($timespanB));
		$this->assertTrue($timespanA->isLessThan($timespanB));
		$this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
		$this->assertFalse($timespanA->isGreaterThan($timespanB));
		$this->assertFalse($timespanA->isGreaterThanOrEqualTo($timespanB));
		
		
		$timespanA = Timespan::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
		$timespanB = Timespan::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-5)),
				Duration::withDays(1));
		
		$this->assertFalse($timespanA->isEqualTo($timespanB));
		$this->assertTrue($timespanA->isLessThan($timespanB));
		$this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
		$this->assertFalse($timespanA->isGreaterThan($timespanB));
		$this->assertFalse($timespanA->isGreaterThanOrEqualTo($timespanB));
		
		$timespanA = Timespan::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 16, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
		$timespanB = Timespan::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-5)),
				Duration::withDays(10));
		
		$this->assertTrue($timespanA->isEqualTo($timespanB));
		$this->assertFalse($timespanA->isLessThan($timespanB));
		$this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
		$this->assertFalse($timespanA->isGreaterThan($timespanB));
		$this->assertTrue($timespanA->isGreaterThanOrEqualTo($timespanB));
		
		$timespanA = Timespan::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 20, 25, 10, Duration::withHours(5)),
				Duration::withDays(10));
		$timespanB = Timespan::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 10, 25, 10, Duration::withHours(-5)),
				Duration::withDays(1));
		
		$this->assertFalse($timespanA->isEqualTo($timespanB));
		$this->assertFalse($timespanA->isLessThan($timespanB));
		$this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
		$this->assertFalse($timespanA->isGreaterThan($timespanB));
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
		$timespanA = Timespan::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
				
		$timespanB = Timespan::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
				Duration::withDays(1));
				
		$timespanC = Timespan::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 8),
				DateAndTime::withYearMonthDay(
							2005, 5, 17));
		
		$timespanD = Timespan::startingEnding(
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
		$timespanA = Timespan::startingDuration(
				DateAndTime::withYearMonthDay(
							2005, 5, 4),
				Duration::withDays(10));
				
		$timespanB = Timespan::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
				Duration::withDays(1));
				
		$timespanC = Timespan::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 8),
				DateAndTime::withYearMonthDay(
							2005, 5, 17));
		
		$timespanD = Timespan::startingEnding(
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
		$timespanA = Timespan::startingDuration(
				DateAndTime::withYearMonthDay(
							2005, 5, 4),
				Duration::withDays(10));
				
		$timespanB = Timespan::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
				Duration::withDays(1));
				
		$timespanC = Timespan::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 8),
				DateAndTime::withYearMonthDay(
							2005, 5, 17));
		
		$timespanD = Timespan::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 17),
				DateAndTime::withYearMonthDay(
							2005, 5, 21));
		
		
		$temp = Timespan::startingDuration(
				DateAndTime::withYearMonthDay(
							2005, 5, 14),
				Duration::withDays(10));
		$this->assertTrue($temp->isEqualTo($timespanA->next()));
		$temp = Timespan::startingDuration(
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
		$timespanA = Timespan::startingDuration(
				DateAndTime::withYearMonthDay(
							2005, 5, 4),
				Duration::withDays(10));
				
		$timespanB = Timespan::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
				Duration::withDays(1));
				
		$timespanC = Timespan::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 8),
				DateAndTime::withYearMonthDay(
							2005, 5, 17));
		
		$timespanD = Timespan::startingEnding(
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
		$timespan = Timespan::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
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
		$temp = Duration::withDays(10);
		$this->assertTrue($temp->isEqualTo($timespan->duration()));
		
		// end()
		$temp = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 14, 15, 25, 10, Duration::withHours(-4));
		$temp =$temp->minus(DateAndTime::clockPrecision());
		$this->assertTrue($temp->isEqualTo($timespan->end()));
		
		// firstDayOfMonth()
		$this->assertEqual($timespan->firstDayOfMonth(), 121);
		
		// isLeapYear()
		$this->assertEqual($timespan->isLeapYear(), FALSE);
		
		// julianDayNumber()
		$this->assertEqual($timespan->julianDayNumber(), 2453495);
		
		// printableString()
		$this->assertEqual($timespan->printableString(), '2005-05-04T15:25:10-04:00D10:00:00:00');
		
		// startMonth()
		$this->assertEqual($timespan->startMonth(), 5);
		
		// startMonthAbbreviation()
		$this->assertEqual($timespan->startMonthAbbreviation(), 'May');
		
		// startMonthIndex()
		$this->assertEqual($timespan->startMonthIndex(), 5);
		
		// startMonthName()
		$this->assertEqual($timespan->startMonthName(), 'May');
		
		// start()
		$temp = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4));
		$this->assertTrue($temp->isEqualTo($timespan->start()));
		
		// startYear()
		$this->assertEqual($timespan->startYear(), 2005);
	
	}
	
	/**
	 * Test Accessing Methods
	 * 
	 */
	function test_enumeration () {
		$timespan = Timespan::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
		
		$timespanB = Timespan::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(1000));
		
		
		// every()
		$everyTwo =$timespan->every(Duration::withDays(2));
		$this->assertEqual(count($everyTwo), 5);
		for ($i = 0; $i < 5; $i++)
			$this->assertEqual(strtolower(get_class($everyTwo[$i])), 'dateandtime');
		
		$temp = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 6, 15, 25, 10, Duration::withHours(-4));
		$this->assertTrue($temp->isEqualTo($everyTwo[1]));
		
		
		// dates()
		$dates =$timespan->dates();
		$this->assertEqual(count($dates), 11);
		for ($i = 0; $i < 11; $i++)
			$this->assertEqual(strtolower(get_class($dates[$i])), 'date');
		
		$temp = Date::withYearMonthDay(2005, 5, 4);
		$this->assertTrue($temp->isEqualTo($dates[0]));
		$temp = Date::withYearMonthDay(2005, 5, 5);
		$this->assertTrue($temp->isEqualTo($dates[1]));
		$temp = Date::withYearMonthDay(2005, 5, 6);
		$this->assertTrue($temp->isEqualTo($dates[2]));
		$temp = Date::withYearMonthDay(2005, 5, 14);
		$this->assertTrue($temp->isEqualTo($dates[10]));
		
		
		// months()
		$months =$timespan->months();
		$this->assertEqual(count($months), 1);
		for ($i = 0; $i < 1; $i++)
			$this->assertEqual(strtolower(get_class($months[$i])), 'month');
		
		$temp = Month::withMonthYear(5, 2005);
		$this->assertTrue($temp->isEqualTo($months[0]));
		
		$months =$timespanB->months();
		$this->assertEqual(count($months), 33);
		
		for ($i = 0; $i < 3; $i++)
			$this->assertEqual(strtolower(get_class($months[$i])), 'month');
		
		$temp = Month::withMonthYear(5, 2005);
		$this->assertTrue($temp->isEqualTo($months[0]));
		
		$temp = Month::withMonthYear(6, 2005);
		$this->assertTrue($temp->isEqualTo($months[1]));
		
		$temp = Month::withMonthYear(1, 2008);
		$this->assertTrue($temp->isEqualTo($months[32]));
		
		
		// weeks()
 		$weeks =$timespan->weeks();
 		$this->assertEqual(count($weeks), 2);
 		for ($i = 0; $i < 2; $i++)
 			$this->assertEqual(strtolower(get_class($weeks[$i])), 'week');
 		
 		$temp = Week::starting(Date::withYearMonthDay(2005, 5, 4));
 		$this->assertTrue($temp->isEqualTo($weeks[0]));
 		
 		$temp = Week::starting(Date::withYearMonthDay(2005, 5, 14));
 		$this->assertTrue($temp->isEqualTo($weeks[1]));
		
		// years()
		$years =$timespan->years();
		$this->assertEqual(count($years), 1);
		for ($i = 0; $i < 1; $i++)
			$this->assertEqual(strtolower(get_class($years[$i])), 'year');
		
		$this->assertEqual($years[0]->startYear(), 2005);
		
		$years =$timespanB->years();
		$this->assertEqual(count($years), 3);
		
		for ($i = 0; $i < 3; $i++)
			$this->assertEqual(strtolower(get_class($years[$i])), 'year');
		
		$this->assertEqual($years[0]->startYear(), 2005);
		
		$this->assertEqual($years[1]->startYear(), 2006);
		
		$this->assertEqual($years[2]->startYear(), 2007);
	
	}
	
	/**
	 * Test Converting Methods
	 * 
	 */
	function test_converting () {
		// Converting
		$timespan = Timespan::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
		
		// asDate()
		$temp =$timespan->asDate();
		$this->assertTrue($temp->isEqualTo(Date::withYearMonthDay(2005, 5, 4)));
		
		// asDateAndTime()
		$temp =$timespan->asDateAndTime();
		$this->assertTrue($temp->isEqualTo(
			DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4))));
		
		// asDuration()
		$temp =$timespan->asDuration();
		$this->assertTrue($temp->isEqualTo(Duration::withDays(10)));
		
		// asMonth()
		$temp =$timespan->asMonth();
		$this->assertTrue($temp->isEqualTo(Month::withMonthYear(5, 2005)));
		
		// asTime()
		$temp =$timespan->asTime();
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4));
		$this->assertTrue($temp->isEqualTo($dateAndTime->asTime()));
		
		// asTimeStamp()
		$temp =$timespan->asTimeStamp();
		$this->assertTrue($temp->isEqualTo(
			DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4))));
		
		// asWeek()
		$temp =$timespan->asWeek();
		$this->assertTrue($temp->isEqualTo(Week::starting(Date::withYearMonthDay(2005, 5, 4))));
		
		// asYear()
		$temp =$timespan->asYear();
		$this->assertTrue($temp->isEqualTo(Year::starting(Date::withYearMonthDay(2005, 5, 4))));
		
		// to()
		$temp =$timespan->to(Date::withYearMonthDay(2005, 10, 1));
		$comparison = Timespan::startingEnding(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Date::withYearMonthDay(2005, 10, 1));
		$this->assertTrue($temp->isEqualTo($comparison));
		
	}
}
?>