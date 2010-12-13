<?php
/** 
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: WeekTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
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
 * @version $Id: WeekTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

class WeekTestCase extends UnitTestCase {
	
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
		$epoch = Week::epoch();
		
		$this->assertEqual(strtolower(get_class($epoch)), 'week');
		$this->assertEqual($epoch->startYear(), 1900);
		$this->assertEqual($epoch->startMonth(), 12);
		$this->assertEqual($epoch->dayOfMonth(), 30);
		$this->assertEqual($epoch->startMonthName(), 'December');
		$start =$epoch->start();
		$this->assertEqual($start->hour(), 0);
		$this->assertEqual($start->minute(), 0);
		$this->assertEqual($start->second(), 0);
		
		$duration =$epoch->duration();
		$this->assertTrue($duration->isEqualTo(Duration::withDays(7)));
		
		
		$week = Week::starting(DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)));
		
		$this->assertEqual(strtolower(get_class($week)), 'week');
		$this->assertEqual($week->startYear(), 2005);
		$this->assertEqual($week->startMonth(), 5);
		$this->assertEqual($week->dayOfMonth(), 1);
		$start =$week->start();
		$this->assertEqual($start->hour(), 0);
		$this->assertEqual($start->minute(), 0);
		$this->assertEqual($start->second(), 0);
		$this->assertEqual($week->startMonthName(), 'May');
		$duration =$week->duration();
		$this->assertEqual($duration->days(), 7);
		$this->assertTrue($week->isEqualTo(
			Week::starting(DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 3, 15, 25, 10, Duration::withHours(-4)))));
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
		
		$timespanA = Week::startingDuration(
				DateAndTime::withYearDay(1950, 1),
				Duration::withDays(10));
		$timespanB = Week::startingDuration(
				DateAndTime::withYearDay(1950, 2),
				Duration::withDays(1));
		
		$this->assertTrue($timespanA->isEqualTo($timespanB));
		$this->assertFalse($timespanA->isLessThan($timespanB));
		$this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
		$this->assertFalse($timespanA->isGreaterThan($timespanB));
		$this->assertTrue($timespanA->isGreaterThanOrEqualTo($timespanB));
		
		
		$timespanA = Week::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
		$timespanB = Week::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-5)),
				Duration::withDays(1));
		
		$this->assertTrue($timespanA->isEqualTo($timespanB));
		$this->assertFalse($timespanA->isLessThan($timespanB));
		$this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
		$this->assertFalse($timespanA->isGreaterThan($timespanB));
		$this->assertTrue($timespanA->isGreaterThanOrEqualTo($timespanB));
		
		
		$timespanA = Week::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 3, 4, 16, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
		$timespanB = Week::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-5)),
				Duration::withDays(10));
		
		$this->assertFalse($timespanA->isEqualTo($timespanB));
		$this->assertTrue($timespanA->isLessThan($timespanB));
		$this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
		$this->assertFalse($timespanA->isGreaterThan($timespanB));
		$this->assertFalse($timespanA->isGreaterThanOrEqualTo($timespanB));
		
		
		$timespanA = Week::starting(DateAndTime::withYearMonthDay(2005, 5, 4));
		$timespanB = Week::starting(DateAndTime::withYearMonthDay(2005, 7, 4));
		$this->assertFalse($timespanA->isEqualTo($timespanB));		
		$this->assertTrue($timespanA->isLessThan($timespanB));
		$this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
		$this->assertFalse($timespanA->isGreaterThan($timespanB));
		$this->assertFalse($timespanA->isGreaterThanOrEqualTo($timespanB));
		
		$timespanA = Week::starting(DateAndTime::withYearMonthDay(2005, 5, 4));
		$timespanB = Week::starting(DateAndTime::withYearMonthDay(2005, 2, 4));
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
		$timespanA = Week::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
				
		$timespanB = Week::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
				Duration::withDays(1));
				
		$timespanC = Week::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 8),
				DateAndTime::withYearMonthDay(
							2005, 5, 17));
		
		$timespanD = Week::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 17),
				DateAndTime::withYearMonthDay(
							2005, 5, 21));		
		$this->assertFalse($timespanA->includes($timespanB->asDateAndTime()));
		$this->assertFalse($timespanA->includes($timespanD->asDateAndTime()));
		$this->assertFalse($timespanA->includesAllOf($arg = array(
			$timespanB->asDateAndTime(), 
			$timespanC->asDateAndTime()
		)));
		$this->assertFalse($timespanA->includesAllOf($arg = array(
			$timespanB->asDateAndTime(), 
			$timespanC->asDateAndTime(),
			$timespanD->asDateAndTime()
		)));
		
		$this->assertFalse($timespanA->includesAnyOf($arg = array(
			$timespanB->asDateAndTime(), 
			$timespanC->asDateAndTime()
		)));
		$this->assertFalse($timespanA->includesAnyOf($arg = array(
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
		$timespanA = Week::startingDuration(
				DateAndTime::withYearMonthDay(
							2005, 5, 4),
				Duration::withDays(10));
				
		$timespanB = Week::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
				Duration::withDays(1));
				
		$timespanC = Week::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 8),
				DateAndTime::withYearMonthDay(
							2005, 5, 17));
		
		$timespanD = Week::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 17),
				DateAndTime::withYearMonthDay(
							2005, 5, 21));
							
		
		// plus()
		$temp = Week::starting(
				DateAndTime::withYearMonthDay(
							2005, 5, 15));
							
		$result =$timespanC->plus(Duration::withDays(6));
		$this->assertFalse($temp->isEqualTo($result));
		
		$result =$timespanC->plus(Duration::withDays(8));
		$this->assertTrue($temp->isEqualTo($result));
		
		$result =$timespanC->plus($timespanD->asDuration());
		$this->assertTrue($temp->isEqualTo($result));
		
		
		// minus()
		// Subtracting an object that implemnts asDateAndTime
		$temp = Week::starting(
				DateAndTime::withYearMonthDay(
							2005, 5, 1));
		$result =$timespanC->minus(Duration::withDays(5));
		$this->assertTrue($temp->isEqualTo($result));
		
		$tempDuration = Duration::withDays(7);
		$this->assertTrue($tempDuration->isEqualTo($timespanC->minus($timespanA)));
		
		$tempDuration = Duration::withDays(-7);
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
		$timespanA = Week::startingDuration(
				DateAndTime::withYearMonthDay(
							2005, 5, 4),
				Duration::withDays(10));
				
		$timespanB = Week::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
				Duration::withDays(1));
				
		$timespanC = Week::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 8),
				DateAndTime::withYearMonthDay(
							2005, 5, 17));
		
		$timespanD = Week::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 17),
				DateAndTime::withYearMonthDay(
							2005, 5, 21));
		
		
		$temp = Week::startingDuration(
				DateAndTime::withYearMonthDay(
							2005, 5, 14),
				Duration::withDays(10));
		$this->assertTrue($temp->isEqualTo($timespanA->next()));
		$temp = Week::startingDuration(
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
		$timespanA = Week::startingDuration(
				DateAndTime::withYearMonthDay(
							2005, 5, 4),
				Duration::withDays(10));
				
		$timespanB = Week::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
				Duration::withDays(1));
				
		$timespanC = Week::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 8),
				DateAndTime::withYearMonthDay(
							2005, 5, 17));
		
		$timespanD = Week::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 17),
				DateAndTime::withYearMonthDay(
							2005, 5, 21));
		

		// intersection()
		$this->assertEqual($timespanA->intersection($timespanB), NULL);
		
		$this->assertEqual($timespanA->intersection($timespanC), NULL);
		
		$this->assertEqual($timespanA->intersection($timespanD), NULL);
		
		
		// union()
		$temp = Timespan::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 1),
				DateAndTime::withYearMonthDay(
							2005, 5, 15));
		
		$this->assertTrue($temp->isEqualTo($timespanA->union($timespanB)));
		
		$temp = Timespan::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 1),
				DateAndTime::withYearMonthDay(
							2005, 5, 15));
		$this->assertTrue($temp->isEqualTo($timespanA->union($timespanC)));
		
		$temp = Timespan::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 1),
				DateAndTime::withYearMonthDay(
							2005, 5, 22));
		$this->assertTrue($temp->isEqualTo($timespanA->union($timespanD)));
	}
	
	/**
	 * Test Accessing Methods
	 * 
	 */
	function test_accessing () {
		$timespan = Week::startingDuration(
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
		$temp = Duration::withDays(7);
		$this->assertTrue($temp->isEqualTo($timespan->duration()));
		
		// end()
		$temp = DateAndTime::withYearMonthDay(2005, 5, 8);
		$temp =$temp->minus(DateAndTime::clockPrecision());
		$this->assertTrue($temp->isEqualTo($timespan->end()));
		
		// firstDayOfMonth()
		$this->assertEqual($timespan->firstDayOfMonth(), 121);
		
		// isLeapYear()
		$this->assertEqual($timespan->isLeapYear(), FALSE);
		
		// julianDayNumber()
		$this->assertEqual($timespan->julianDayNumber(), 2453492);
		
		// printableString()
		$this->assertEqual($timespan->printableString(), '2005-05-01T00:00:00-04:00D7:00:00:00');
		
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
		$timespan = Week::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
		
		$timespanB = Week::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(1000));
		
		
		// every()
		$everyTwo =$timespan->every(Duration::withDays(2));
		$this->assertEqual(count($everyTwo), 4);
		for ($i = 0; $i < 4; $i++)
			$this->assertEqual(strtolower(get_class($everyTwo[$i])), 'dateandtime');
		
		$temp = DateAndTime::withYearMonthDayHourMinuteSecond(
							2005, 5, 3, 0, 0, 0);
		$this->assertTrue($temp->isEqualTo($everyTwo[1]));
		

		// dates()
		$dates =$timespan->dates();
		$this->assertEqual(count($dates), 7);
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
		$timespan = Week::startingDuration(
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
		$this->assertTrue($temp->isEqualTo(Duration::withDays(7)));
		
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