<?php
/** 
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DateAndTimeTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 * @since 5/3/05
 */

require_once(dirname(__FILE__)."/../DateAndTime.class.php");

/**
 * A single unit test case. This class is intended to test one particular
 * class. Replace 'testedclass.php' below with the class you would like to
 * test.
 *
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DateAndTimeTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 * @since 5/3/05
 */

class DateAndTimeTestCase extends UnitTestCase {

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
	 * Test the DateAndTime representing the Squeak epoch: 1 January 1901.
	 */ 
	function test_epoch() {
	
		$dateAndTime = DateAndTime::epoch();
		$this->assertEqual($dateAndTime->year(), 1901);
		$this->assertEqual($dateAndTime->month(), 1);
		$this->assertEqual($dateAndTime->day(), 1);
		
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							1901, 1, 1, 0, 0, 0, $null = NULL);
		$this->assertEqual($dateAndTime->year(), 1901);
		$this->assertEqual($dateAndTime->month(), 1);
		$this->assertEqual($dateAndTime->day(), 1);
		
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							1901, 'jan', 1, 0, 0, 0, $null = NULL);
		$this->assertEqual($dateAndTime->year(), 1901);
		$this->assertEqual($dateAndTime->month(), 1);
		$this->assertEqual($dateAndTime->day(), 1);
		
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							1901, 'January', 1, 0, 0, 0, $null = NULL);
		$this->assertEqual($dateAndTime->year(), 1901);
		$this->assertEqual($dateAndTime->month(), 1);
		$this->assertEqual($dateAndTime->day(), 1);
		
	}
	
	/**
	 * Test alterate static creations
	 */ 
	function test_creation_methods() {
	
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, $null = NULL);
		$this->assertEqual($dateAndTime->year(), 2005);
		$this->assertEqual($dateAndTime->month(), 5);
		$this->assertEqual($dateAndTime->dayOfMonth(), 4);
		$this->assertEqual($dateAndTime->hour(), 15);
		$this->assertEqual($dateAndTime->hour12(), 3);
		$this->assertEqual($dateAndTime->minute(), 25);
		$this->assertEqual($dateAndTime->second(), 10);
		
		$dateAndTime = DateAndTime::withYearMonthDayHourMinute(2005, 5, 4, 15, 25);
		$this->assertEqual($dateAndTime->year(), 2005);
		$this->assertEqual($dateAndTime->month(), 5);
		$this->assertEqual($dateAndTime->dayOfMonth(), 4);
		$this->assertEqual($dateAndTime->hour(), 15);
		$this->assertEqual($dateAndTime->hour12(), 3);
		$this->assertEqual($dateAndTime->minute(), 25);
		$this->assertEqual($dateAndTime->second(), 0);
	
		$dateAndTime = DateAndTime::withYearDay(1950, 1);
		$this->assertEqual($dateAndTime->year(), 1950);
		$this->assertEqual($dateAndTime->month(), 1);
		$this->assertEqual($dateAndTime->dayOfMonth(), 1);
		$this->assertEqual($dateAndTime->hour(), 0);
		$this->assertEqual($dateAndTime->hour12(), 12);
		$this->assertEqual($dateAndTime->minute(), 0);
		$this->assertEqual($dateAndTime->second(), 0);
		
		$dateAndTime = DateAndTime::withYearMonthDay(2005, 1, 1);
		$this->assertEqual($dateAndTime->year(), 2005);
		$this->assertEqual($dateAndTime->month(), 1);
		$this->assertEqual($dateAndTime->dayOfMonth(), 1);
		$this->assertEqual($dateAndTime->hour(), 0);
		$this->assertEqual($dateAndTime->hour12(), 12);
		$this->assertEqual($dateAndTime->minute(), 0);
		$this->assertEqual($dateAndTime->second(), 0);
		
		$date = Date::withYearMonthDay(2005, 5, 4);
		$time = Time::withHourMinuteSecond(15, 25, 10);
		$dateAndTime = DateAndTime::withDateAndTime($date, $time);
		$this->assertEqual($dateAndTime->year(), 2005);
		$this->assertEqual($dateAndTime->month(), 5);
		$this->assertEqual($dateAndTime->dayOfMonth(), 4);
		$this->assertEqual($dateAndTime->hour(), 15);
		$this->assertEqual($dateAndTime->hour12(), 3);
		$this->assertEqual($dateAndTime->minute(), 25);
		$this->assertEqual($dateAndTime->second(), 10);
		
	}
	
	/**
	 * Test instance creation from a string.
	 * 
	 */
	function test_from_string () {
		$dateAndTime = DateAndTime::withYearMonthDay(2005, 8, 20);
		
		$this->assertTrue($dateAndTime->isEqualTo(
			DateAndTime::fromString('2005-08-20')));
		$this->assertTrue($dateAndTime->isEqualTo(
			DateAndTime::fromString('08/20/2005')));
		$this->assertTrue($dateAndTime->isEqualTo(
			DateAndTime::fromString('August 20, 2005')));
		$this->assertTrue($dateAndTime->isEqualTo(
			DateAndTime::fromString('20aug05')));
		
		
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecond(
							2005, 8, 20, 15, 25, 10);
							
		$this->assertTrue($dateAndTime->isEqualTo(
			DateAndTime::fromString('2005-08-20T15:25:10')));
		$this->assertTrue($dateAndTime->isEqualTo(
			DateAndTime::fromString('20050820152510')));
		$this->assertTrue($dateAndTime->isEqualTo(
			DateAndTime::fromString('2005-08-20 3:25:10 pm')));
		$this->assertTrue($dateAndTime->isEqualTo(
			DateAndTime::fromString('08/20/2005 3:25:10 pm')));
		$this->assertTrue($dateAndTime->isEqualTo(
			DateAndTime::fromString('August 20, 2005 3:25:10 pm')));
			
		$this->assertTrue($dateAndTime->isEqualTo(
			DateAndTime::fromString('2005/08/20 15:25:10')));
			
		
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecond(
							2005, 8, 20, 15, 25, 0);
							
		$this->assertTrue($dateAndTime->isEqualTo(
			DateAndTime::fromString('2005-08-20T15:25')));
		
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecond(
							2005, 8, 20, 15, 0, 0);
							
		$this->assertTrue($dateAndTime->isEqualTo(
			DateAndTime::fromString('2005-08-20T15')));
		
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 8, 20, 15, 25, 10, Duration::withHours(-7));
			
		$this->assertTrue($dateAndTime->isEqualTo(
			DateAndTime::fromString('2005-08-20T15:25:10-07:00')));
		$this->assertTrue($dateAndTime->isEqualTo(
			DateAndTime::fromString('20050820152510-07')));
			
		
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 8, 20, 15, 25, 10, Duration::zero());
		
		$this->assertTrue($dateAndTime->isEqualTo(
			DateAndTime::fromString('2005-08-20T15:25:10Z')));
		$this->assertTrue($dateAndTime->isEqualTo(
			DateAndTime::fromString('20050820152510Z')));
	}
	
	/*********************************************************
	 * Test Year only edge cases
	 *********************************************************/
	function test_year () {
		$dateAndTimeA = DateAndTime::withYearDay(2005, 0);
		$dateAndTimeB = DateAndTime::withYearDay(2005, 1);
		$dateAndTimeC = DateAndTime::fromString('2005');
		$tz = DateAndTime::localTimeZone();
		
		$this->assertTrue($dateAndTimeA->isEqual($dateAndTimeB));
		$this->assertTrue($dateAndTimeA->isEqual($dateAndTimeC));
		
		$this->assertEqual($dateAndTimeA->asString(), '2005-01-01T00:00:00'.$tz->asString());
		$this->assertEqual($dateAndTimeB->asString(), '2005-01-01T00:00:00'.$tz->asString());
		$this->assertEqual($dateAndTimeC->asString(), '2005-01-01T00:00:00'.$tz->asString());
	}
	
	/**
	 * Test comparisons
	 */ 
	function test_comparisons() {
		$dateAndTimeA = DateAndTime::withYearDay(1950, 1);
		$dateAndTimeB = DateAndTime::withYearDay(1950, 2);
		
		$this->assertFalse($dateAndTimeA->isEqualTo($dateAndTimeB));
		$this->assertTrue($dateAndTimeA->isLessThan($dateAndTimeB));
		$this->assertTrue($dateAndTimeA->isLessThanOrEqualTo($dateAndTimeB));
		$this->assertFalse($dateAndTimeA->isGreaterThan($dateAndTimeB));
		$this->assertFalse($dateAndTimeA->isGreaterThanOrEqualTo($dateAndTimeB));
		
		
		$dateAndTimeA = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4));
		$dateAndTimeB = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-5));
		
		$this->assertFalse($dateAndTimeA->isEqualTo($dateAndTimeB));
		$this->assertTrue($dateAndTimeA->isLessThan($dateAndTimeB));
		$this->assertTrue($dateAndTimeA->isLessThanOrEqualTo($dateAndTimeB));
		$this->assertFalse($dateAndTimeA->isGreaterThan($dateAndTimeB));
		$this->assertFalse($dateAndTimeA->isGreaterThanOrEqualTo($dateAndTimeB));
		
		$dateAndTimeA = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 16, 25, 10, Duration::withHours(-4));
		$dateAndTimeB = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-5));
		
		$this->assertTrue($dateAndTimeA->isEqualTo($dateAndTimeB));
		$this->assertFalse($dateAndTimeA->isLessThan($dateAndTimeB));
		$this->assertTrue($dateAndTimeA->isLessThanOrEqualTo($dateAndTimeB));
		$this->assertFalse($dateAndTimeA->isGreaterThan($dateAndTimeB));
		$this->assertTrue($dateAndTimeA->isGreaterThanOrEqualTo($dateAndTimeB));
		
		$dateAndTimeA = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 20, 25, 10, Duration::withHours(5));
		$dateAndTimeB = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 10, 25, 10, Duration::withHours(-5));
		
		$this->assertTrue($dateAndTimeA->isEqualTo($dateAndTimeB));
		$this->assertFalse($dateAndTimeA->isLessThan($dateAndTimeB));
		$this->assertTrue($dateAndTimeA->isLessThanOrEqualTo($dateAndTimeB));
		$this->assertFalse($dateAndTimeA->isGreaterThan($dateAndTimeB));
		$this->assertTrue($dateAndTimeA->isGreaterThanOrEqualTo($dateAndTimeB));
	}
	
	/**
	 * Test accessing
	 */ 
	function test_accessing() {
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 6, 4, 15, 25, 10, Duration::withHours(-5));
		
		// Methods not in the test are in comments.
		
		// asDate() +
		$temp =$dateAndTime->asDateAndTime();
		$this->assertTrue($temp->isEqualTo(
			DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 6, 4, 15, 25, 10, Duration::withHours(-5))));
		// asDuration() +
		// asLocal() +
		// asMonth() +
		// asSeconds() +
		// asTime() +
		// asTimestamp() +
		// asUTC() +
		// asWeek() +
		// asYear()	+
		$this->assertEqual($dateAndTime->day(), 155);
		$this->assertEqual($dateAndTime->dayOfMonth(), 4);
		$this->assertEqual($dateAndTime->dayOfWeek(), 7);
		$this->assertEqual($dateAndTime->dayOfWeekAbbreviation(), 'Sat');
		$this->assertEqual($dateAndTime->dayOfWeekName(), 'Saturday');
		$this->assertEqual($dateAndTime->dayOfYear(), 155);
 		$this->assertEqual($dateAndTime->daysInMonth(), 30);
 		$this->assertEqual($dateAndTime->daysInYear(), 365);
 		$this->assertEqual($dateAndTime->daysLeftInYear(), 210);
 		$duration =$dateAndTime->duration();
 		$this->assertEqual($duration->asSeconds(), 0);
 		$this->assertEqual($dateAndTime->firstDayOfMonth(), 152);
		$this->assertEqual($dateAndTime->hour(), 15);
		$this->assertEqual($dateAndTime->hour24(), 15);
		$this->assertEqual($dateAndTime->hour12(), 3);
		$this->assertEqual($dateAndTime->hour(), 15);
		// isEqualTo() +
		$this->assertFalse($dateAndTime->isLeapYear());
		// isLessThan() +
		$this->assertEqual($dateAndTime->julianDayNumber(), 2453526);
		$this->assertEqual($dateAndTime->meridianAbbreviation(), 'PM');
		// middleOf($aDuration) +
		// midnight() +
		// minus() +
		$this->assertEqual($dateAndTime->minute(), 25);
		$this->assertEqual($dateAndTime->month(), 6);
		$this->assertEqual($dateAndTime->monthIndex(), 6);
		$this->assertEqual($dateAndTime->monthName(), 'June');
		$this->assertEqual($dateAndTime->monthAbbreviation(), 'Jun');
		// noon()
		$offset =$dateAndTime->offset();
		$this->assertTrue($offset->isEqualTo(Duration::withHours(-5)));
		// plus() +
 		$this->assertEqual($dateAndTime->hmsString(), '15:25:10');
 		$this->assertEqual($dateAndTime->ymdString(), '2005-06-04');
 		$this->assertEqual($dateAndTime->printableString(), '2005-06-04T15:25:10-05:00');
		$this->assertEqual($dateAndTime->second(), 10);
		// ticks()
		// ticksOffset()
 		$this->assertEqual($dateAndTime->timeZoneAbbreviation(), 'EST');
 		$this->assertEqual($dateAndTime->timeZoneName(), 'Eastern Standard Time');
		// to() +
		// toBy()
		// toByDo()
		// utcOffset() +
		// withOffset() +
		$this->assertEqual($dateAndTime->year(), 2005);
		
// 		$this->assertEqual("All tests have been uncommented and run?", "Yes");
	}
	
	/**
	 * Test converting
	 */ 
	function test_converting() {
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 6, 4, 15, 25, 10, Duration::withHours(-5));
		
		
		// asDate()
		$temp =$dateAndTime->asDate();
		$this->assertTrue($temp->isEqualTo(Date::withYearMonthDay(2005, 6, 4)));
		
		// asDuration()
		$temp =$dateAndTime->asDuration();
		$this->assertTrue($temp->isEqualTo(Duration::withSeconds(55510)));
		
		// asDateAndTime()
		$temp =$dateAndTime->asDateAndTime();
		$this->assertTrue($temp->isEqualTo(
			DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 6, 4, 15, 25, 10, Duration::withHours(-5))));
		
		// asLocal()
		$startDuration = Duration::withHours(-5);
		$localOffset = DateAndTime::localOffset();
		$difference =$localOffset->minus($startDuration);
		$temp =$dateAndTime->asLocal();
		$local = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 6, 4, (15 + $difference->hours()), 25, 10, $localOffset);
		
		$this->assertTrue($temp->isEqualTo($local));
		
		// asMonth()
		$temp =$dateAndTime->asMonth();
		$this->assertTrue($temp->isEqualTo(Month::withMonthYear(6, 2005)));
		
		// asSeconds()
		$localOffset = DateAndTime::localOffset();
		$this->assertEqual($dateAndTime->asSeconds(), (3295369510 + $localOffset->asSeconds()));
		
		// asTime()
		$temp =$dateAndTime->asTime();
		$this->assertTrue($temp->isEqualTo(Time::withHourMinuteSecond(15, 25, 10)));
		$this->assertTrue($temp->isEqualTo(Time::withSeconds(55510)));
		
		// asTimeStamp()
 		$temp =$dateAndTime->asTimeStamp();
 		$this->assertTrue($temp->isEqualTo(
 				TimeStamp::withYearMonthDayHourMinuteSecondOffset(
							2005, 6, 4, 15, 25, 10, Duration::withHours(-5))));
		
		// asUTC()
		$temp =$dateAndTime->asUTC();
		$this->assertTrue($temp->isEqualTo(
			DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 6, 4, 20, 25, 10, Duration::withHours(0))));
		
		// asWeek()
		$temp =$dateAndTime->asWeek();
		$this->assertTrue($temp->isEqualTo(Week::starting($dateAndTime)));
		
		// asYear()
		$temp =$dateAndTime->asYear();
		$this->assertTrue($temp->isEqualTo(Year::starting($dateAndTime)));
		
		// midnight();
		$temp =$dateAndTime->atMidnight();
		$this->assertTrue($temp->isEqualTo(
			DateAndTime::withYearMonthDayHourMinuteSecond(2005, 6, 4, 0, 0, 0)));
		
		// middleOf()
		$dat = DateAndTime::withYearDay(2005, 100);
		$timespan =$dat->middleOf(Duration::withDays(100));
		$start =$timespan->start();
		$duration =$timespan->duration();
		$end =$timespan->end();
		$this->assertEqual($start->dayOfYear(), 50);
		$this->assertTrue($start->isEqualTo(DateAndTime::withYearDay(2005, 50)));
		$this->assertEqual($duration->days(), 100);
		$this->assertEqual($end->dayOfYear(), 149);
		
		// to()
		$datA = DateAndTime::withYearDay(2005, 125);
		$datB = DateAndTime::withYearDay(2006, 125);
		
		$timespan =$datA->to($datB);
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
			
		// withOffset()
		$temp =$dateAndTime->withOffset(Duration::withHours(-7));
		$this->assertTrue($temp->isEqualTo(
			DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 6, 4, 15, 25, 10, Duration::withHours(-7))));
	}
	
	/**
	 * Test utcOffset
	 * 
	 */
	function test_utcOffset() {
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 6, 4, 15, 25, 10, Duration::withHours(-5));
		
		
		$atUTC =$dateAndTime->utcOffset(Duration::withHours(0));
		
		$this->assertEqual($dateAndTime->julianDayNumber(), 2453526);
		$this->assertEqual($atUTC->julianDayNumber(), 2453526);
		$this->assertEqual($dateAndTime->seconds, 55510);
		$this->assertEqual($atUTC->seconds, 73510);
		$this->assertEqual($dateAndTime->offset->seconds, -18000);
		$this->assertEqual($atUTC->offset->seconds, 0);
		
		$this->assertEqual($dateAndTime->printableString(), '2005-06-04T15:25:10-05:00');
		$this->assertEqual($atUTC->printableString(), '2005-06-04T20:25:10+00:00');
	}
	
	/**
	 * Test localOffset
	 * 
	 */
	function test_localOffset() {
		$localOffset = DateAndTime::localOffset();
		
		$this->assertTrue($localOffset->isLessThanOrEqualTo(Duration::withHours(12)));
		$this->assertTrue($localOffset->isGreaterThanOrEqualTo(Duration::withHours(-12)));
		
		$secondsOffset = date('Z');
		$this->assertTrue($localOffset->isEqualTo(Duration::withSeconds($secondsOffset)));
	}
	
	/**
	 * Magnitude operations
	 * 
	 */
	function test_magnitude_ops () {
		// Plus a Duration
		$dateAndTime = DateAndTime::withYearDayHourMinuteSecond(2005, 100, 0, 0, 0);
		$result =$dateAndTime->plus(Duration::withSeconds(1));
		
		$this->assertEqual(strtolower(get_class($result)), 'dateandtime');
		$this->assertTrue($result->isEqualTo(DateAndTime::withYearDayHourMinuteSecond(
			2005, 100, 0, 0, 1)));
		
		// minus a Duration
		$dateAndTime = DateAndTime::withYearDayHourMinuteSecond(2005, 100, 0, 0, 0);
		$result =$dateAndTime->minus(Duration::withSeconds(1));
		
		$this->assertEqual(strtolower(get_class($result)), 'dateandtime');
		$this->assertEqual($result->year(), 2005);
		$this->assertEqual($result->dayOfYear(), 99);
		$this->assertEqual($result->hour(), 23);
		$this->assertEqual($result->minute(), 59);
		$this->assertEqual($result->second(), 59);
		$this->assertTrue($result->isEqualTo(DateAndTime::withYearDayHourMinuteSecond(
			2005, 99, 23, 59, 59)));
			
		
		// Minus a DateAndTime
		$dateAndTime = DateAndTime::withYearDayHourMinuteSecond(2006, 100, 0, 0, 0);
		$result =$dateAndTime->minus(DateAndTime::withYearDayHourMinuteSecond(2005, 100, 0, 0, 0));
		
		$this->assertEqual(strtolower(get_class($result)), 'duration');
		$this->assertTrue($result->isEqualTo(Duration::withDays(365)));
		
		// Minus a DateAndTime over a leap year
		$dateAndTime = DateAndTime::withYearDayHourMinuteSecond(2005, 10, 0, 0, 0);
		$result =$dateAndTime->minus(DateAndTime::withYearDayHourMinuteSecond(2004, 10, 0, 0, 0));
		
		$this->assertEqual(strtolower(get_class($result)), 'duration');
		$this->assertTrue($result->isEqualTo(Duration::withDays(366)));
		
		// Plus a DateAndTime
		$dateAndTime = DateAndTime::withYearDayHourMinuteSecond(2000, 100, 5, 15, 30);
		$result =$dateAndTime->plus(DateAndTime::withYearDayHourMinuteSecond(
			2000, 100, 5, 30, 15));
		
		$this->assertEqual(strtolower(get_class($result)), 'dateandtime');
		$this->assertEqual($result->year(), 2000);
		$this->assertEqual($result->dayOfYear(), 100);
		$this->assertEqual($result->hour(), 10);
		$this->assertEqual($result->minute(), 45);
		$this->assertEqual($result->second(), 45);
			
	}
	
	/**
	 * Test conversion to the PHP built-in DateTime
	 * 
	 * @return void
	 * @access public
	 * @since 11/21/08
	 */
	public function test_php_datetime () {
		print "<h3>conversion to PHP DateTime</h3>";
		
		$ref = new ReflectionClass('DateTimeZone');
		printpre($ref->getMethods());
		
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 6, 4, 15, 25, 10, Duration::withHours(-5));
		$this->checkEquality($dateAndTime, $dateAndTime->asDateTime());
		
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2005, 2, 4, 15, 25, 10, Duration::withHours(-4));
		$this->checkEquality($dateAndTime, $dateAndTime->asDateTime());
		
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							1423, 2, 4, 15, 25, 10, Duration::withHours(0));
		$this->checkEquality($dateAndTime, $dateAndTime->asDateTime());
		
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							732, 6, 3, 8, 0, 0, Duration::withHours(0));
		$this->checkEquality($dateAndTime, $dateAndTime->asDateTime());
		
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							2, 6, 3, 8, 0, 0, Duration::withHours(0));
		$this->checkEquality($dateAndTime, $dateAndTime->asDateTime());
		
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							0, 6, 3, 8, 0, 0, Duration::withHours(0));
		$this->checkEquality($dateAndTime, $dateAndTime->asDateTime());
		
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							-460, 6, 3, 8, 0, 0, Duration::withHours(0));
		$this->checkEquality($dateAndTime, $dateAndTime->asDateTime());
		
		$dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
							-8460, 6, 3, 8, 0, 0, Duration::withHours(0));
		$this->checkEquality($dateAndTime, $dateAndTime->asDateTime());
		
		$dateAndTime = DateAndTime::now();
		$this->checkEquality($dateAndTime, $dateAndTime->asDateTime());
	}
	
	/**
	 * Check the equality of a DateAndTime against a PHP DateTime object
	 * 
	 * @param object DateAndTime $dateAndTime
	 * @param object DateTime $dateTime
	 * @return void
	 * @access protected
	 * @since 11/21/08
	 */
	protected function checkEquality (DateAndTime $dateAndTime, DateTime $dateTime) {
		print "<h4>".$dateAndTime->asString()."</h4>";
		print "Year: ";
		$this->assertEqual($dateAndTime->year(), intval($dateTime->format('Y')));
		print "Month: ";
		$this->assertEqual($dateAndTime->month(), intval($dateTime->format('n')));
		print "Day of Month: ";
		$this->assertEqual($dateAndTime->dayOfMonth(), intval($dateTime->format('j')));
		print "Day of Year: ";
		$this->assertEqual($dateAndTime->dayOfYear() - 1, intval($dateTime->format('z')));
		
		print "Hour: ";
		$this->assertEqual($dateAndTime->hour(), intval($dateTime->format('G')));
		print "Minute: ";
		$this->assertEqual($dateAndTime->minute(), intval($dateTime->format('i')));
		print "Second: ";
		$this->assertEqual($dateAndTime->second(), intval($dateTime->format('s')));
		
// 		print "TZ abbriviation: ";
// 		$this->assertEqual($dateAndTime->timeZoneAbbreviation(), $dateTime->format('T'));
		
		$datTZone = $dateAndTime->timeZone();
		$dtTZone = $dateTime->getTimezone();
		
		print "TZ seconds: ";
		$this->assertEqual($datTZone->offset()->asSeconds(), intval($dateTime->format('Z')));
	}

}

// 		print "<pre>";
// 		print_r($duration);
// 		print "</pre>";

?>