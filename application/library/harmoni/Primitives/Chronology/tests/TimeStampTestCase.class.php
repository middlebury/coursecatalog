<?php
/** 
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: TimeStampTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 * @since 5/3/05
 */

require_once(dirname(__FILE__)."/../TimeStamp.class.php");

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
 * @version $Id: TimeStampTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

class TimeStampTestCase extends UnitTestCase {
	
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
		$epoch = TimeStamp::epoch();
		$this->assertEqual(strtolower(get_class($epoch)), 'timestamp');
		
		$timestamp = TimeStamp::current();
		$this->assertEqual(strtolower(get_class($timestamp)), 'timestamp');
	}
	
	/**
	 * Test the timestamp conversion methods.
	 */ 
	function test_plus_minus_days() {
		$timestamp = TimeStamp::withYearMonthDayHourMinuteSecond(2005, 5, 4, 15, 25, 32);
		$this->assertEqual(strtolower(get_class($timestamp)), 'timestamp');
		
		$temp =$timestamp->minusDays(3);
		$this->assertEqual(strtolower(get_class($temp)), 'timestamp');
		$this->assertEqual($temp->year(), 2005);
		$this->assertEqual($temp->month(), 5);
		$this->assertEqual($temp->dayOfMonth(), 1);
		$this->assertEqual($temp->hour(), 15);
		$this->assertEqual($temp->minute(), 25);
		$this->assertEqual($temp->second(), 32);
		$this->assertTrue($temp->isEqualTo(
			TimeStamp::withYearMonthDayHourMinuteSecond(2005, 5, 1, 15, 25, 32)));
		
		$temp =$timestamp->plusDays(3);
		$this->assertEqual(strtolower(get_class($temp)), 'timestamp');
		$this->assertEqual($temp->year(), 2005);
		$this->assertEqual($temp->month(), 5);
		$this->assertEqual($temp->dayOfMonth(), 7);
		$this->assertEqual($temp->hour(), 15);
		$this->assertEqual($temp->minute(), 25);
		$this->assertEqual($temp->second(), 32);
		$this->assertTrue($temp->isEqualTo(
			TimeStamp::withYearMonthDayHourMinuteSecond(2005, 5, 7, 15, 25, 32)));
		
		$temp =$timestamp->minusSeconds(7);
		$this->assertEqual(strtolower(get_class($temp)), 'timestamp');
		$this->assertEqual($temp->year(), 2005);
		$this->assertEqual($temp->month(), 5);
		$this->assertEqual($temp->dayOfMonth(), 4);
		$this->assertEqual($temp->hour(), 15);
		$this->assertEqual($temp->minute(), 25);
		$this->assertEqual($temp->second(), 25);
		$this->assertTrue($temp->isEqualTo(
			TimeStamp::withYearMonthDayHourMinuteSecond(2005, 5, 4, 15, 25, 25)));
		
		$temp =$timestamp->plusSeconds(7);
		$this->assertEqual(strtolower(get_class($temp)), 'timestamp');
		$this->assertEqual($temp->year(), 2005);
		$this->assertEqual($temp->month(), 5);
		$this->assertEqual($temp->dayOfMonth(), 4);
		$this->assertEqual($temp->hour(), 15);
		$this->assertEqual($temp->minute(), 25);
		$this->assertEqual($temp->second(), 39);
		$this->assertTrue($temp->isEqualTo(
			TimeStamp::withYearMonthDayHourMinuteSecond(2005, 5, 4, 15, 25, 39)));
	}
	
	/**
	 * Test the plus/minus days/seconds conversion methods.
	 */ 
	function test_timestamp_converion() {
		$timestamp = TimeStamp::withYearMonthDayHourMinute(2005, 5, 4, 15, 25);
		$this->assertEqual(strtolower(get_class($timestamp)), 'timestamp');
		
		$temp =$timestamp->date();
		$this->assertEqual(strtolower(get_class($temp)), 'date');
		$this->assertTrue($temp->isEqualTo(Date::withYearMonthDay(2005, 5, 4)));
		
		$temp =$timestamp->time();
		$this->assertEqual(strtolower(get_class($temp)), 'time');
		$this->assertTrue($temp->isEqualTo(Time::withHourMinuteSecond(15, 25, 0)));
		
		$temp = $timestamp->dateAndTimeArray();
		$this->assertEqual(strtolower(get_class($temp[0])), 'date');
		$this->assertEqual(strtolower(get_class($temp[1])), 'time');
		$this->assertTrue($temp[0]->isEqualTo(Date::withYearMonthDay(2005, 5, 4)));
		$this->assertTrue($temp[1]->isEqualTo(Time::withHourMinuteSecond(15, 25, 0)));
		$this->assertEqual(count($temp), 2);
	}
	
	/**
	 * Test conversion from Unix timestamps
	 * 
	 */
	function test_from_unix_timestamp () {
		
		$timestamp = TimeStamp::fromUnixTimeStamp(0);
		$unixEpoch = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
						1970, 1, 1, 0, 0, 0, Duration::zero());
		
		$this->assertTrue($timestamp->isEqualTo($unixEpoch));
		$this->assertEqual($timestamp, $unixEpoch);
		
		$this->assertEqual($timestamp->year(), 1970);
		$this->assertEqual($timestamp->month(), 1);
		$this->assertEqual($timestamp->dayOfMonth(), 1);
		$this->assertEqual($timestamp->hour(), 0);
		$this->assertEqual($timestamp->minute(), 0);
		$this->assertEqual($timestamp->second(), 0);
		
		$this->assertEqual($timestamp->asUnixTimeStamp(), 0);
		
		$unixTimeStamp = time();
		$timestamp = TimeStamp::fromUnixTimeStamp($unixTimeStamp);
		
		$this->assertEqual($timestamp->year(), date('Y', $unixTimeStamp));
		$this->assertEqual($timestamp->month(), date('m', $unixTimeStamp));
		$this->assertEqual($timestamp->dayOfMonth(), date('j', $unixTimeStamp));
		$this->assertEqual($timestamp->hour(), 
			(date('H', $unixTimeStamp) - (date('Z', $unixTimeStamp)/3600)));
		$this->assertEqual($timestamp->minute(), date('i', $unixTimeStamp));
		$this->assertEqual($timestamp->second(), date('s', $unixTimeStamp));
		$this->assertEqual($timestamp->asUnixTimeStamp(), $unixTimeStamp);
	}
	
/*********************************************************
 * Methods from the date and time test case. These should
 * mostly work since Timestamp extends DateAndTime.
 *********************************************************/
	
	/**
	 * Test the DateAndTime representing the Squeak epoch: 1 January 1901.
	 */ 
	function test_epoch() {
	print "test_epoch";
	
		$dateAndTime = TimeStamp::epoch();
		$this->assertEqual($dateAndTime->year(), 1901);
		$this->assertEqual($dateAndTime->month(), 1);
		$this->assertEqual($dateAndTime->day(), 1);
		
		$dateAndTime = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
							1901, 1, 1, 0, 0, 0, $null = NULL);
		$this->assertEqual($dateAndTime->year(), 1901);
		$this->assertEqual($dateAndTime->month(), 1);
		$this->assertEqual($dateAndTime->day(), 1);
		
		$dateAndTime = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
							1901, 'jan', 1, 0, 0, 0, $null = NULL);
		$this->assertEqual($dateAndTime->year(), 1901);
		$this->assertEqual($dateAndTime->month(), 1);
		$this->assertEqual($dateAndTime->day(), 1);
		
		$dateAndTime = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
							1901, 'January', 1, 0, 0, 0, $null = NULL);
		$this->assertEqual($dateAndTime->year(), 1901);
		$this->assertEqual($dateAndTime->month(), 1);
		$this->assertEqual($dateAndTime->day(), 1);
		
	}
	
	/**
	 * Test alterate static creations
	 */ 
	function test_creation_methods() {
	
		$dateAndTime = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, $null = NULL);
		$this->assertEqual($dateAndTime->year(), 2005);
		$this->assertEqual($dateAndTime->month(), 5);
		$this->assertEqual($dateAndTime->dayOfMonth(), 4);
		$this->assertEqual($dateAndTime->hour(), 15);
		$this->assertEqual($dateAndTime->hour12(), 3);
		$this->assertEqual($dateAndTime->minute(), 25);
		$this->assertEqual($dateAndTime->second(), 10);
		$this->assertEqual(strtolower(get_class($dateAndTime)), 'timestamp');
		
		$dateAndTime = TimeStamp::withYearMonthDayHourMinute(2005, 5, 4, 15, 25);
		$this->assertEqual($dateAndTime->year(), 2005);
		$this->assertEqual($dateAndTime->month(), 5);
		$this->assertEqual($dateAndTime->dayOfMonth(), 4);
		$this->assertEqual($dateAndTime->hour(), 15);
		$this->assertEqual($dateAndTime->hour12(), 3);
		$this->assertEqual($dateAndTime->minute(), 25);
		$this->assertEqual($dateAndTime->second(), 0);
		$this->assertEqual(strtolower(get_class($dateAndTime)), 'timestamp');
	
		$dateAndTime = TimeStamp::withYearDay(1950, 1);
		$this->assertEqual($dateAndTime->year(), 1950);
		$this->assertEqual($dateAndTime->month(), 1);
		$this->assertEqual($dateAndTime->dayOfMonth(), 1);
		$this->assertEqual($dateAndTime->hour(), 0);
		$this->assertEqual($dateAndTime->hour12(), 12);
		$this->assertEqual($dateAndTime->minute(), 0);
		$this->assertEqual($dateAndTime->second(), 0);
		$this->assertEqual(strtolower(get_class($dateAndTime)), 'timestamp');
		
		$dateAndTime = TimeStamp::withYearMonthDay(2005, 1, 1);
		$this->assertEqual($dateAndTime->year(), 2005);
		$this->assertEqual($dateAndTime->month(), 1);
		$this->assertEqual($dateAndTime->dayOfMonth(), 1);
		$this->assertEqual($dateAndTime->hour(), 0);
		$this->assertEqual($dateAndTime->hour12(), 12);
		$this->assertEqual($dateAndTime->minute(), 0);
		$this->assertEqual($dateAndTime->second(), 0);
		$this->assertEqual(strtolower(get_class($dateAndTime)), 'timestamp');

		$date = Date::withYearMonthDay(2005, 5, 4);
		$time = Time::withHourMinuteSecond(15, 25, 10);
		$dateAndTime = TimeStamp::withDateAndTime($date, $time);
		$this->assertEqual($dateAndTime->year(), 2005);
		$this->assertEqual($dateAndTime->month(), 5);
		$this->assertEqual($dateAndTime->dayOfMonth(), 4);
		$this->assertEqual($dateAndTime->hour(), 15);
		$this->assertEqual($dateAndTime->hour12(), 3);
		$this->assertEqual($dateAndTime->minute(), 25);
		$this->assertEqual($dateAndTime->second(), 10);
		$this->assertEqual(strtolower(get_class($dateAndTime)), 'timestamp');
		
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
	
	/**
	 * Test comparisons
	 */ 
	function test_comparisons() {
		$dateAndTimeA = TimeStamp::withYearDay(1950, 1);
		$dateAndTimeB = TimeStamp::withYearDay(1950, 2);
		
		$this->assertFalse($dateAndTimeA->isEqualTo($dateAndTimeB));
		$this->assertTrue($dateAndTimeA->isLessThan($dateAndTimeB));
		$this->assertTrue($dateAndTimeA->isLessThanOrEqualTo($dateAndTimeB));
		$this->assertFalse($dateAndTimeA->isGreaterThan($dateAndTimeB));
		$this->assertFalse($dateAndTimeA->isGreaterThanOrEqualTo($dateAndTimeB));
		
		
		$dateAndTimeA = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-4));
		$dateAndTimeB = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-5));
		
		$this->assertFalse($dateAndTimeA->isEqualTo($dateAndTimeB));
		$this->assertTrue($dateAndTimeA->isLessThan($dateAndTimeB));
		$this->assertTrue($dateAndTimeA->isLessThanOrEqualTo($dateAndTimeB));
		$this->assertFalse($dateAndTimeA->isGreaterThan($dateAndTimeB));
		$this->assertFalse($dateAndTimeA->isGreaterThanOrEqualTo($dateAndTimeB));
		
		$dateAndTimeA = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 16, 25, 10, Duration::withHours(-4));
		$dateAndTimeB = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 15, 25, 10, Duration::withHours(-5));
		
		$this->assertTrue($dateAndTimeA->isEqualTo($dateAndTimeB));
		$this->assertFalse($dateAndTimeA->isLessThan($dateAndTimeB));
		$this->assertTrue($dateAndTimeA->isLessThanOrEqualTo($dateAndTimeB));
		$this->assertFalse($dateAndTimeA->isGreaterThan($dateAndTimeB));
		$this->assertTrue($dateAndTimeA->isGreaterThanOrEqualTo($dateAndTimeB));
		
		$dateAndTimeA = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
							2005, 5, 4, 20, 25, 10, Duration::withHours(5));
		$dateAndTimeB = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
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
		$dateAndTime = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
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
 		$this->assertEqual($dateAndTime->printableString(), '4 June 2005 3:25:10 pm');
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
	}
	
	/**
	 * Test converting
	 */ 
	function test_converting() {
		$dateAndTime = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
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
		$local = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
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
		$dat = TimeStamp::withYearDay(2005, 100);
		$timespan =$dat->middleOf(Duration::withDays(100));
		$start =$timespan->start();
		$duration =$timespan->duration();
		$end =$timespan->end();
		$this->assertEqual($start->dayOfYear(), 50);
		$this->assertTrue($start->isEqualTo(DateAndTime::withYearDay(2005, 50)));
		$this->assertEqual($duration->days(), 100);
		$this->assertEqual($end->dayOfYear(), 149);
		
		// to()
		$datA = TimeStamp::withYearDay(2005, 125);
		$datB = TimeStamp::withYearDay(2006, 125);
		
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
		$dateAndTime = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
							2005, 6, 4, 15, 25, 10, Duration::withHours(-5));
		
		
		$atUTC =$dateAndTime->utcOffset(Duration::withHours(0));
		
		$this->assertEqual($dateAndTime->julianDayNumber(), 2453526);
		$this->assertEqual($atUTC->julianDayNumber(), 2453526);
		$this->assertEqual($dateAndTime->seconds, 55510);
		$this->assertEqual($atUTC->seconds, 73510);
		$this->assertEqual($dateAndTime->offset->seconds, -18000);
		$this->assertEqual($atUTC->offset->seconds, 0);
		
		$this->assertEqual($dateAndTime->printableString(), '4 June 2005 3:25:10 pm');
		$this->assertEqual($atUTC->printableString(), '4 June 2005 8:25:10 pm');
	}
	
	/**
	 * Magnitude operations
	 * 
	 */
	function test_magnitude_ops () {
		// Plus a Duration
		$dateAndTime = TimeStamp::withYearDayHourMinuteSecond(2005, 100, 0, 0, 0);
		$this->assertEqual(strtolower(get_class($dateAndTime)), 'timestamp');
		$result =$dateAndTime->plus(Duration::withSeconds(1));
		
		$this->assertEqual(strtolower(get_class($result)), 'timestamp');
		$this->assertTrue($result->isEqualTo(TimeStamp::withYearDayHourMinuteSecond(
			2005, 100, 0, 0, 1)));
		
		// minus a Duration
		$dateAndTime = TimeStamp::withYearDayHourMinuteSecond(2005, 100, 0, 0, 0);
		$result =$dateAndTime->minus(Duration::withSeconds(1));
		
		$this->assertEqual(strtolower(get_class($result)), 'timestamp');
		$this->assertEqual($result->year(), 2005);
		$this->assertEqual($result->dayOfYear(), 99);
		$this->assertEqual($result->hour(), 23);
		$this->assertEqual($result->minute(), 59);
		$this->assertEqual($result->second(), 59);
		$this->assertTrue($result->isEqualTo(TimeStamp::withYearDayHourMinuteSecond(
			2005, 99, 23, 59, 59)));
			
		
		// Minus a DateAndTime
		$dateAndTime = TimeStamp::withYearDayHourMinuteSecond(2006, 100, 0, 0, 0);
		$result =$dateAndTime->minus(TimeStamp::withYearDayHourMinuteSecond(2005, 100, 0, 0, 0));
		
		$this->assertEqual(strtolower(get_class($result)), 'duration');
		$this->assertTrue($result->isEqualTo(Duration::withDays(365)));
		
		// Minus a DateAndTime over a leap year
		$dateAndTime = TimeStamp::withYearDayHourMinuteSecond(2005, 10, 0, 0, 0);
		$result =$dateAndTime->minus(TimeStamp::withYearDayHourMinuteSecond(2004, 10, 0, 0, 0));
		
		$this->assertEqual(strtolower(get_class($result)), 'duration');
		$this->assertTrue($result->isEqualTo(Duration::withDays(366)));
		
		// Plus a DateAndTime
		$dateAndTime = TimeStamp::withYearDayHourMinuteSecond(2000, 100, 5, 15, 30);
		$result =$dateAndTime->plus(TimeStamp::withYearDayHourMinuteSecond(
			2000, 100, 5, 30, 15));
		
		$this->assertEqual(strtolower(get_class($result)), 'timestamp');
		$this->assertEqual($result->year(), 2000);
		$this->assertEqual($result->dayOfYear(), 100);
		$this->assertEqual($result->hour(), 10);
		$this->assertEqual($result->minute(), 45);
		$this->assertEqual($result->second(), 45);
			
	}
}

// 		print "<pre>";
// 		print_r($duration);
// 		print "</pre>";

?>