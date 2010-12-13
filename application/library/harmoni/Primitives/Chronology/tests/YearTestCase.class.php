<?php
/** 
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: YearTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 * @since 5/3/05
 */

require_once(dirname(__FILE__)."/../Year.class.php");

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
 * @version $Id: YearTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

class YearTestCase extends UnitTestCase {
	
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
		$epochYear = Year::epoch();
		
		$this->assertEqual(strtolower(get_class($epochYear)), 'year');
		$this->assertEqual($epochYear->dayOfYear(), 1);
		$this->assertEqual($epochYear->daysInYear(), 365);
		
		$duration =$epochYear->duration();
		$this->assertTrue($duration->isEqualTo(Duration::withDays(365)));
		$this->assertEqual($epochYear->startYear(), 1901);
		
		$current = Year::current();
		$this->assertEqual($current->startYear(), $this->currentYear);
		
		$aYear = Year::withYear(1999);
		$this->assertEqual($aYear->startYear(), 1999);
		$aYear = Year::withYear(2005);
		$this->assertEqual($aYear->startYear(), 2005);
		
		$aYear = Year::starting(DateAndTime::withYearDay(1982, 25));
		$this->assertEqual($aYear->startYear(), 1982);
		$this->assertEqual($aYear->dayOfYear(), 25);
		$this->assertEqual($aYear->daysInYear(), 365);
	}
	
	/**
	 * Test some leap years.
	 * 
	 */
	function test_leap_years() {
		// recent leap years
		$this->assertTrue(Year::isYearLeapYear(1980));
		$this->assertTrue(Year::isYearLeapYear(1984));
		$this->assertTrue(Year::isYearLeapYear(1988));
		$this->assertTrue(Year::isYearLeapYear(1992));
		$this->assertTrue(Year::isYearLeapYear(1996));
		$this->assertTrue(Year::isYearLeapYear(2000));
		$this->assertTrue(Year::isYearLeapYear(2004));
		$this->assertTrue(Year::isYearLeapYear(2008));
		
		// divisible-by 100 years
		$this->assertTrue(Year::isYearLeapYear(1600));
		$this->assertFalse(Year::isYearLeapYear(1700));
		$this->assertFalse(Year::isYearLeapYear(1800));
		$this->assertFalse(Year::isYearLeapYear(1900));
		$this->assertTrue(Year::isYearLeapYear(2000));
		$this->assertFalse(Year::isYearLeapYear(2100));
		$this->assertFalse(Year::isYearLeapYear(2200));
		$this->assertFalse(Year::isYearLeapYear(2300));
		$this->assertTrue(Year::isYearLeapYear(2400));
		
		// Non-leap years
		$this->assertFalse(Year::isYearLeapYear(1981));
		$this->assertFalse(Year::isYearLeapYear(1979));
		$this->assertFalse(Year::isYearLeapYear(1999));
		$this->assertFalse(Year::isYearLeapYear(2003));
		$this->assertFalse(Year::isYearLeapYear(2001));
		$this->assertFalse(Year::isYearLeapYear(1789));
		$this->assertFalse(Year::isYearLeapYear(2002));
		$this->assertFalse(Year::isYearLeapYear(1998));
		$this->assertFalse(Year::isYearLeapYear(2005));
	
		$aYear = Year::starting(DateAndTime::withYearDay(1980, 55));
		$this->assertEqual($aYear->startYear(), 1980);
		$this->assertEqual($aYear->dayOfYear(), 55);
		$this->assertEqual($aYear->daysInYear(), 366);
		
		$aYear = Year::withYear(1980);
		$this->assertEqual($aYear->startYear(), 1980);
		$this->assertEqual($aYear->dayOfYear(), 1);
		$this->assertEqual($aYear->daysInYear(), 366);
		
		$aYear = Year::withYear(2000);
		$this->assertEqual($aYear->startYear(), 2000);
		$this->assertEqual($aYear->dayOfYear(), 1);
		$this->assertEqual($aYear->daysInYear(), 366);
	}	
	
	/**
	 * Test printing
	 */
	function test_printing () {
		$year = Year::withYear(2005);
		
		$this->assertEqual($year->printableString(), '2005');
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
		
		$timespanA = Year::startingDuration(
				DateAndTime::withYearDay(1950, 1),
				Duration::withDays(10));
		$timespanB = Year::startingDuration(
				DateAndTime::withYearDay(1950, 2),
				Duration::withDays(1));
		
		$this->assertFalse($timespanA->isEqualTo($timespanB));
		$this->assertTrue($timespanA->isLessThan($timespanB));
		$this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
		$this->assertFalse($timespanA->isGreaterThan($timespanB));
		$this->assertFalse($timespanA->isGreaterThanOrEqualTo($timespanB));
		
		
		$timespanA = Year::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
		$timespanB = Year::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-5)),
				Duration::withDays(1));
		
		$this->assertTrue($timespanA->isEqualTo($timespanB));
		$this->assertFalse($timespanA->isLessThan($timespanB));
		$this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
		$this->assertFalse($timespanA->isGreaterThan($timespanB));
		$this->assertTrue($timespanA->isGreaterThanOrEqualTo($timespanB));
		
		$timespanA = Year::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 16, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
		$timespanB = Year::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-5)),
				Duration::withDays(10));
		
		$this->assertTrue($timespanA->isEqualTo($timespanB));
		$this->assertFalse($timespanA->isLessThan($timespanB));
		$this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
		$this->assertFalse($timespanA->isGreaterThan($timespanB));
		$this->assertTrue($timespanA->isGreaterThanOrEqualTo($timespanB));
		
		$timespanA = Year::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 20, 25, 10, Duration::withHours(5)),
				Duration::withDays(10));
		$timespanB = Year::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 10, 25, 10, Duration::withHours(-5)),
				Duration::withDays(1));
		
		$this->assertTrue($timespanA->isEqualTo($timespanB));
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
		
		
		// 0 0 0 0 0 0 0 0 1 1 1 1 1 1 1 1 1 1 2 2 2 2 2 2 2 2 2 2 3 ... 0 0 0 0
		// 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 ... 1 2 3 4
		//
		// A  |- - - - - - - - - -|
		// B              |-|
		// C                                  |- - - - - - - - -|
		// D                                                         ...|- - - -|
		$timespanA = Year::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
				
		$timespanB = Year::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
				Duration::withDays(1));
				
		$timespanC = Year::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 20),
				DateAndTime::withYearMonthDay(
							2005, 5, 29));
		
		$timespanD = Year::startingEnding(
				DateAndTime::withYearMonthDay(
							2006, 6, 1),
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
		$timespanA = Year::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
				
		$timespanB = Year::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
				Duration::withDays(1));
				
		$timespanC = Year::starting(
				DateAndTime::withYearMonthDay(
							2005, 5, 20));
		
		$timespanD = Year::starting(
				DateAndTime::withYearMonthDay(
							2005, 6, 1));		
							
		
		// plus()
		$temp = Year::starting(
				DateAndTime::withYearMonthDay(
							2005, 5, 24));
		$result =$timespanC->plus(Duration::withDays(4));
		$this->assertEqual(strtolower(get_class($result)), "year");
		$this->assertTrue($temp->isEqualTo($result));
		
		// minus()
		// Subtracting an object that implemnts asDateAndTime
		$temp = Year::starting(
				DateAndTime::withYearMonthDay(
							2005, 5, 15));
			$result =$timespanC->minus(Duration::withDays(5));
		$this->assertEqual(strtolower(get_class($result)), "year");
		$this->assertTrue($temp->isEqualTo($result));
		
		$tempDuration = Duration::withDays(-12);
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
		$timespanA = Year::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
				
		$timespanB = Year::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
				Duration::withDays(1));
				
		$timespanC = Year::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 20),
				DateAndTime::withYearMonthDay(
							2005, 5, 29));
		
		$timespanD = Year::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 6, 1),
				DateAndTime::withYearMonthDay(
							2005, 6, 6));		
		
		
		$temp = Year::starting(DateAndTime::withYearMonthDay(2006, 5, 4));
		$this->assertTrue($temp->isEqualTo($timespanA->next()));
		$temp = Year::starting(DateAndTime::withYearMonthDay(2004, 5, 4));
		$this->assertTrue($temp->isEqualTo($timespanA->previous()));
	}
	
	/**
	 * Test aritmatic operations
	 * 
	 */
	function test_intersect_union () {
		// intersection()
		// union()
		
		
		// 0 0 0 0 0 0 0 0 1 1 1 1 1 1 1 1 1 1 2 2 2 2 2 2 2 2 2 2 3 ... 0 0 0 0
		// 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 1 2 3 4 5 6 7 8 9 0 ... 1 2 3 4
		//
		// A  |- - - - - - - - - -|
		// B              |-|
		// C                                  |- - - - - - - - -|
		// D                                                         ...|- - - -|
		$timespanA = Year::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
				
		$timespanB = Year::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
				Duration::withDays(1));
				
		$timespanC = Year::startingEnding(
				DateAndTime::withYearMonthDay(
							2005, 5, 20),
				DateAndTime::withYearMonthDay(
							2005, 5, 29));
		
		$timespanD = Year::startingEnding(
				DateAndTime::withYearMonthDay(
							2006, 6, 1),
				DateAndTime::withYearMonthDay(
							2005, 6, 6));		
		

		// intersection()
		$this->assertTrue($timespanB->isEqualTo($timespanA->intersection($timespanB)));
		
		$this->assertTrue($timespanC->isEqualTo($timespanA->intersection($timespanC)));
		
		$this->assertEqual($timespanA->intersection($timespanD), NULL);
		
		
		// union()
		$temp = Timespan::startingDuration(
				DateAndTime::withYearMonthDay(
							2005, 5, 4),
				Duration::withDays(371));
		$union =$timespanA->union($timespanB);
		$this->assertTrue($temp->isEqualTo($union));
		$unionDuration =$union->duration();
		$this->assertEqual($unionDuration->days(), 371);
		
		
		$temp = Timespan::startingDuration(
				DateAndTime::withYearMonthDay(
							2005, 5, 4),
				Duration::withDays(381));
		$this->assertTrue($temp->isEqualTo($timespanA->union($timespanC)));
		
		$temp = Timespan::startingDuration(
				DateAndTime::withYearMonthDay(
							2005, 5, 4),
				Duration::withDays(758));
		$this->assertTrue($temp->isEqualTo($timespanA->union($timespanD)));
	}
	
	/**
	 * Test Accessing Methods
	 * 
	 */
	function test_accessing () {
		$timespan = Year::startingDuration(
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
		$temp = Duration::withDays(365);
		$this->assertTrue($temp->isEqualTo($timespan->duration()));
		
		// end()
		$temp = DateAndTime::withYearMonthDay(2006, 5, 4);
		$temp =$temp->minus(DateAndTime::clockPrecision());
		$this->assertTrue($temp->isEqualTo($timespan->end()));
		
		// firstDayOfMonth()
		$this->assertEqual($timespan->firstDayOfMonth(), 121);
		
		// isLeapYear()
		$this->assertEqual($timespan->isLeapYear(), FALSE);
		
		// julianDayNumber()
		$this->assertEqual($timespan->julianDayNumber(), 2453495);
		
		// printableString()
		$this->assertEqual($timespan->printableString(), '2005-05-04T00:00:00-04:00D365:00:00:00');
		
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
		$timespan = Year::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
		
		$timespanB = Year::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(1000));
		
		
		// every()
		$everyTwo =$timespan->every(Duration::withDays(2));
		$this->assertEqual(count($everyTwo), 183);
		for ($i = 0; $i < 16; $i++)
			$this->assertEqual(strtolower(get_class($everyTwo[$i])), 'dateandtime');
		
		$temp = DateAndTime::withYearMonthDayHourMinuteSecond(
							2005, 5, 6, 0, 0, 0);
		$this->assertTrue($temp->isEqualTo($everyTwo[1]));
		

		// dates()
		$dates =$timespan->dates();
		$this->assertEqual(count($dates), 365);
		for ($i = 0; $i < 7; $i++)
			$this->assertEqual(strtolower(get_class($dates[$i])), 'date');
		
		$temp = Date::withYearMonthDay(2005, 5, 4);
		$this->assertTrue($temp->isEqualTo($dates[0]));
		$temp = Date::withYearMonthDay(2005, 5, 5);
		$this->assertTrue($temp->isEqualTo($dates[1]));
		$temp = Date::withYearMonthDay(2005, 5, 6);
		$this->assertTrue($temp->isEqualTo($dates[2]));
		
		
		// months()
		$months =$timespan->months();
		$this->assertEqual(count($months), 13);
		for ($i = 0; $i < 13; $i++)
			$this->assertEqual(strtolower(get_class($months[$i])), 'month');
		
		$temp = Month::withMonthYear(5, 2005);
		$this->assertTrue($temp->isEqualTo($months[0]));
		
		$months =$timespanB->months();
		$this->assertEqual(count($months), 13);
		
		for ($i = 0; $i < 13; $i++)
			$this->assertEqual(strtolower(get_class($months[$i])), 'month');
		
		$temp = Month::withMonthYear(5, 2005);
		$this->assertTrue($temp->isEqualTo($months[0]));
		
		$temp = Month::withMonthYear(5, 2006);
		$this->assertTrue($temp->isEqualTo($months[12]));
		
		
		// weeks()
 		$weeks =$timespan->weeks();
 		$this->assertEqual(count($weeks), 53);
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
		$timespan = Year::startingDuration(
				DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
				Duration::withDays(10));
		
		// asDate()
		$temp =$timespan->asDate();
		$this->assertTrue($temp->isEqualTo(Date::withYearMonthDay(2005, 5, 4)));
		
		// asDateAndTime()
		$temp =$timespan->asDateAndTime();
		$this->assertTrue($temp->isEqualTo(
			DateAndTime::withYearMonthDayHourMinuteSecond(
							2005, 5, 4, 00, 00, 00)));
		
		// asDuration()
		$temp =$timespan->asDuration();
		$this->assertTrue($temp->isEqualTo(Duration::withDays(365)));
		
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