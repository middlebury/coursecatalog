<?php

/**
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DateAndTimeTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 *
 * @since 5/3/05
 */

use PHPUnit\Framework\TestCase;

/**
 * A single unit test case. This class is intended to test one particular
 * class. Replace 'testedclass.php' below with the class you would like to
 * test.
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DateAndTimeTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 *
 * @since 5/3/05
 */
class DateAndTimeTest extends TestCase
{
    /**
     *  Sets up unit test wide variables at the start
     *	 of each test method.
     */
    protected function setUp(): void
    {
        // perhaps, initialize $obj here
    }

    /**
     *	  Clears the data set in the setUp() method call.
     */
    protected function tearDown(): void
    {
        // perhaps, unset $obj here
    }

    /**
     * Test the DateAndTime representing the Squeak epoch: 1 January 1901.
     */
    public function testEpoch()
    {
        $dateAndTime = DateAndTime::epoch();
        $this->assertEquals(1901, $dateAndTime->year());
        $this->assertEquals(1, $dateAndTime->month());
        $this->assertEquals(1, $dateAndTime->day());

        $dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
            1901, 1, 1, 0, 0, 0, $null = null);
        $this->assertEquals(1901, $dateAndTime->year());
        $this->assertEquals(1, $dateAndTime->month());
        $this->assertEquals(1, $dateAndTime->day());

        $dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
            1901, 'jan', 1, 0, 0, 0, $null = null);
        $this->assertEquals(1901, $dateAndTime->year());
        $this->assertEquals(1, $dateAndTime->month());
        $this->assertEquals(1, $dateAndTime->day());

        $dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
            1901, 'January', 1, 0, 0, 0, $null = null);
        $this->assertEquals(1901, $dateAndTime->year());
        $this->assertEquals(1, $dateAndTime->month());
        $this->assertEquals(1, $dateAndTime->day());
    }

    /**
     * Test alterate static creations.
     */
    public function testCreationMethods()
    {
        $dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
            2005, 5, 4, 15, 25, 10, $null = null);
        $this->assertEquals(2005, $dateAndTime->year());
        $this->assertEquals(5, $dateAndTime->month());
        $this->assertEquals(4, $dateAndTime->dayOfMonth());
        $this->assertEquals(15, $dateAndTime->hour());
        $this->assertEquals(3, $dateAndTime->hour12());
        $this->assertEquals(25, $dateAndTime->minute());
        $this->assertEquals(10, $dateAndTime->second());

        $dateAndTime = DateAndTime::withYearMonthDayHourMinute(2005, 5, 4, 15, 25);
        $this->assertEquals(2005, $dateAndTime->year());
        $this->assertEquals(5, $dateAndTime->month());
        $this->assertEquals(4, $dateAndTime->dayOfMonth());
        $this->assertEquals(15, $dateAndTime->hour());
        $this->assertEquals(3, $dateAndTime->hour12());
        $this->assertEquals(25, $dateAndTime->minute());
        $this->assertEquals(0, $dateAndTime->second());

        $dateAndTime = DateAndTime::withYearDay(1950, 1);
        $this->assertEquals(1950, $dateAndTime->year());
        $this->assertEquals(1, $dateAndTime->month());
        $this->assertEquals(1, $dateAndTime->dayOfMonth());
        $this->assertEquals(0, $dateAndTime->hour());
        $this->assertEquals(12, $dateAndTime->hour12());
        $this->assertEquals(0, $dateAndTime->minute());
        $this->assertEquals(0, $dateAndTime->second());

        $dateAndTime = DateAndTime::withYearMonthDay(2005, 1, 1);
        $this->assertEquals(2005, $dateAndTime->year());
        $this->assertEquals(1, $dateAndTime->month());
        $this->assertEquals(1, $dateAndTime->dayOfMonth());
        $this->assertEquals(0, $dateAndTime->hour());
        $this->assertEquals(12, $dateAndTime->hour12());
        $this->assertEquals(0, $dateAndTime->minute());
        $this->assertEquals(0, $dateAndTime->second());

        $date = Date::withYearMonthDay(2005, 5, 4);
        $time = Time::withHourMinuteSecond(15, 25, 10);
        $dateAndTime = DateAndTime::withDateAndTime($date, $time);
        $this->assertEquals(2005, $dateAndTime->year());
        $this->assertEquals(5, $dateAndTime->month());
        $this->assertEquals(4, $dateAndTime->dayOfMonth());
        $this->assertEquals(15, $dateAndTime->hour());
        $this->assertEquals(3, $dateAndTime->hour12());
        $this->assertEquals(25, $dateAndTime->minute());
        $this->assertEquals(10, $dateAndTime->second());
    }

    /**
     * Test instance creation from a string.
     */
    public function testFromString()
    {
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
    public function testYear()
    {
        $dateAndTimeA = DateAndTime::withYearDay(2005, 0);
        $dateAndTimeB = DateAndTime::withYearDay(2005, 1);
        $dateAndTimeC = DateAndTime::fromString('2005');
        $tz = DateAndTime::localTimeZone();

        $this->assertTrue($dateAndTimeA->isEqual($dateAndTimeB));
        $this->assertTrue($dateAndTimeA->isEqual($dateAndTimeC));

        $this->assertEquals('2005-01-01T00:00:00'.$tz->asString(), $dateAndTimeA->asString());
        $this->assertEquals('2005-01-01T00:00:00'.$tz->asString(), $dateAndTimeB->asString());
        $this->assertEquals('2005-01-01T00:00:00'.$tz->asString(), $dateAndTimeC->asString());
    }

    /**
     * Test comparisons.
     */
    public function testComparisons()
    {
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
     * Test accessing.
     */
    public function testAccessing()
    {
        $dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
            2005, 6, 4, 15, 25, 10, Duration::withHours(-5));

        // Methods not in the test are in comments.

        // asDate() +
        $temp = $dateAndTime->asDateAndTime();
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
        $this->assertEquals(155, $dateAndTime->day());
        $this->assertEquals(4, $dateAndTime->dayOfMonth());
        $this->assertEquals(7, $dateAndTime->dayOfWeek());
        $this->assertEquals('Sat', $dateAndTime->dayOfWeekAbbreviation());
        $this->assertEquals('Saturday', $dateAndTime->dayOfWeekName());
        $this->assertEquals(155, $dateAndTime->dayOfYear());
        $this->assertEquals(30, $dateAndTime->daysInMonth());
        $this->assertEquals(365, $dateAndTime->daysInYear());
        $this->assertEquals(210, $dateAndTime->daysLeftInYear());
        $duration = $dateAndTime->duration();
        $this->assertEquals(0, $duration->asSeconds());
        $this->assertEquals(152, $dateAndTime->firstDayOfMonth());
        $this->assertEquals(15, $dateAndTime->hour());
        $this->assertEquals(15, $dateAndTime->hour24());
        $this->assertEquals(3, $dateAndTime->hour12());
        $this->assertEquals(15, $dateAndTime->hour());
        // isEqualTo() +
        $this->assertFalse($dateAndTime->isLeapYear());
        // isLessThan() +
        $this->assertEquals(2453526, $dateAndTime->julianDayNumber());
        $this->assertEquals('PM', $dateAndTime->meridianAbbreviation());
        // middleOf($aDuration) +
        // midnight() +
        // minus() +
        $this->assertEquals(25, $dateAndTime->minute());
        $this->assertEquals(6, $dateAndTime->month());
        $this->assertEquals(6, $dateAndTime->monthIndex());
        $this->assertEquals('June', $dateAndTime->monthName());
        $this->assertEquals('Jun', $dateAndTime->monthAbbreviation());
        // noon()
        $offset = $dateAndTime->offset();
        $this->assertTrue($offset->isEqualTo(Duration::withHours(-5)));
        // plus() +
        $this->assertEquals('15:25:10', $dateAndTime->hmsString());
        $this->assertEquals('2005-06-04', $dateAndTime->ymdString());
        $this->assertEquals('2005-06-04T15:25:10-05:00', $dateAndTime->printableString());
        $this->assertEquals(10, $dateAndTime->second());
        // ticks()
        // ticksOffset()
        $this->assertEquals('EST', $dateAndTime->timeZoneAbbreviation());
        $this->assertEquals('Eastern Standard Time', $dateAndTime->timeZoneName());
        // to() +
        // toBy()
        // toByDo()
        // utcOffset() +
        // withOffset() +
        $this->assertEquals(2005, $dateAndTime->year());

        // 		$this->assertEquals("Yes", "All tests have been uncommented and run?");
    }

    /**
     * Test converting.
     */
    public function testConverting()
    {
        $dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
            2005, 6, 4, 15, 25, 10, Duration::withHours(-5));

        // asDate()
        $temp = $dateAndTime->asDate();
        $this->assertTrue($temp->isEqualTo(Date::withYearMonthDay(2005, 6, 4)));

        // asDuration()
        $temp = $dateAndTime->asDuration();
        $this->assertTrue($temp->isEqualTo(Duration::withSeconds(55510)));

        // asDateAndTime()
        $temp = $dateAndTime->asDateAndTime();
        $this->assertTrue($temp->isEqualTo(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 6, 4, 15, 25, 10, Duration::withHours(-5))));

        // asLocal()
        $startDuration = Duration::withHours(-5);
        $localOffset = DateAndTime::localOffset();
        $difference = $localOffset->minus($startDuration);
        $temp = $dateAndTime->asLocal();
        $local = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
            2005, 6, 4, 15 + $difference->hours(), 25, 10, $localOffset);

        $this->assertTrue($temp->isEqualTo($local));

        // asMonth()
        $temp = $dateAndTime->asMonth();
        $this->assertTrue($temp->isEqualTo(Month::withMonthYear(6, 2005)));

        // asSeconds()
        $localOffset = DateAndTime::localOffset();
        $this->assertEquals(3295369510 + $localOffset->asSeconds(), $dateAndTime->asSeconds());

        // asTime()
        $temp = $dateAndTime->asTime();
        $this->assertTrue($temp->isEqualTo(Time::withHourMinuteSecond(15, 25, 10)));
        $this->assertTrue($temp->isEqualTo(Time::withSeconds(55510)));

        // asTimeStamp()
        $temp = $dateAndTime->asTimeStamp();
        $this->assertTrue($temp->isEqualTo(
            TimeStamp::withYearMonthDayHourMinuteSecondOffset(
                2005, 6, 4, 15, 25, 10, Duration::withHours(-5))));

        // asUTC()
        $temp = $dateAndTime->asUTC();
        $this->assertTrue($temp->isEqualTo(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 6, 4, 20, 25, 10, Duration::withHours(0))));

        // asWeek()
        $temp = $dateAndTime->asWeek();
        $this->assertTrue($temp->isEqualTo(Week::starting($dateAndTime)));

        // asYear()
        $temp = $dateAndTime->asYear();
        $this->assertTrue($temp->isEqualTo(Year::starting($dateAndTime)));

        // midnight();
        $temp = $dateAndTime->atMidnight();
        $this->assertTrue($temp->isEqualTo(
            DateAndTime::withYearMonthDayHourMinuteSecond(2005, 6, 4, 0, 0, 0)));

        // middleOf()
        $dat = DateAndTime::withYearDay(2005, 100);
        $timespan = $dat->middleOf(Duration::withDays(100));
        $start = $timespan->start();
        $duration = $timespan->duration();
        $end = $timespan->end();
        $this->assertEquals(50, $start->dayOfYear());
        $this->assertTrue($start->isEqualTo(DateAndTime::withYearDay(2005, 50)));
        $this->assertEquals(100, $duration->days());
        $this->assertEquals(149, $end->dayOfYear());

        // to()
        $datA = DateAndTime::withYearDay(2005, 125);
        $datB = DateAndTime::withYearDay(2006, 125);

        $timespan = $datA->to($datB);
        $this->assertEquals(2005, $timespan->startYear());
        $this->assertEquals(125, $timespan->dayOfYear());
        $duration = $timespan->duration();
        $this->assertTrue($duration->isEqualTo(Duration::withDays(365)));
        $end = $timespan->end();
        $this->assertEquals(2453860, $end->julianDayNumber());
        $this->assertEquals(364, $end->julianDayNumber() - $datA->julianDayNumber());
        $this->assertEquals(2006, $end->year());
        $this->assertEquals(124, $end->dayOfYear());
        $this->assertTrue($end->isEqualTo(DateAndTime::withYearDayHourMinuteSecond(
            2006, 124, 23, 59, 59)));

        // withOffset()
        $temp = $dateAndTime->withOffset(Duration::withHours(-7));
        $this->assertTrue($temp->isEqualTo(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 6, 4, 15, 25, 10, Duration::withHours(-7))));
    }

    /**
     * Test utcOffset.
     */
    public function testUtcOffset()
    {
        $dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
            2005, 6, 4, 15, 25, 10, Duration::withHours(-5));

        $atUTC = $dateAndTime->utcOffset(Duration::withHours(0));

        $this->assertEquals(2453526, $dateAndTime->julianDayNumber());
        $this->assertEquals(2453526, $atUTC->julianDayNumber());
        $this->assertEquals(55510, $dateAndTime->seconds);
        $this->assertEquals(73510, $atUTC->seconds);
        $this->assertEquals(-18000, $dateAndTime->offset->asSeconds());
        $this->assertEquals(0, $atUTC->offset->asSeconds());

        $this->assertEquals('2005-06-04T15:25:10-05:00', $dateAndTime->printableString());
        $this->assertEquals('2005-06-04T20:25:10+00:00', $atUTC->printableString());
    }

    /**
     * Test localOffset.
     */
    public function testLocalOffset()
    {
        $localOffset = DateAndTime::localOffset();

        $this->assertTrue($localOffset->isLessThanOrEqualTo(Duration::withHours(12)));
        $this->assertTrue($localOffset->isGreaterThanOrEqualTo(Duration::withHours(-12)));

        $secondsOffset = date('Z');
        $this->assertTrue($localOffset->isEqualTo(Duration::withSeconds($secondsOffset)));
    }

    /**
     * Magnitude operations.
     */
    public function testMagnitudeOps()
    {
        // Plus a Duration
        $dateAndTime = DateAndTime::withYearDayHourMinuteSecond(2005, 100, 0, 0, 0);
        $result = $dateAndTime->plus(Duration::withSeconds(1));

        $this->assertEquals('dateandtime', strtolower($result::class));
        $this->assertTrue($result->isEqualTo(DateAndTime::withYearDayHourMinuteSecond(
            2005, 100, 0, 0, 1)));

        // minus a Duration
        $dateAndTime = DateAndTime::withYearDayHourMinuteSecond(2005, 100, 0, 0, 0);
        $result = $dateAndTime->minus(Duration::withSeconds(1));

        $this->assertEquals('dateandtime', strtolower($result::class));
        $this->assertEquals(2005, $result->year());
        $this->assertEquals(99, $result->dayOfYear());
        $this->assertEquals(23, $result->hour());
        $this->assertEquals(59, $result->minute());
        $this->assertEquals(59, $result->second());
        $this->assertTrue($result->isEqualTo(DateAndTime::withYearDayHourMinuteSecond(
            2005, 99, 23, 59, 59)));

        // Minus a DateAndTime
        $dateAndTime = DateAndTime::withYearDayHourMinuteSecond(2006, 100, 0, 0, 0);
        $result = $dateAndTime->minus(DateAndTime::withYearDayHourMinuteSecond(2005, 100, 0, 0, 0));

        $this->assertEquals('duration', strtolower($result::class));
        $this->assertTrue($result->isEqualTo(Duration::withDays(365)));

        // Minus a DateAndTime over a leap year
        $dateAndTime = DateAndTime::withYearDayHourMinuteSecond(2005, 10, 0, 0, 0);
        $result = $dateAndTime->minus(DateAndTime::withYearDayHourMinuteSecond(2004, 10, 0, 0, 0));

        $this->assertEquals('duration', strtolower($result::class));
        $this->assertTrue($result->isEqualTo(Duration::withDays(366)));

        // Plus a DateAndTime
        $dateAndTime = DateAndTime::withYearDayHourMinuteSecond(2000, 100, 5, 15, 30);
        $result = $dateAndTime->plus(DateAndTime::withYearDayHourMinuteSecond(
            2000, 100, 5, 30, 15));

        $this->assertEquals('dateandtime', strtolower($result::class));
        $this->assertEquals(2000, $result->year());
        $this->assertEquals(100, $result->dayOfYear());
        $this->assertEquals(10, $result->hour());
        $this->assertEquals(45, $result->minute());
        $this->assertEquals(45, $result->second());
    }

    /**
     * Test conversion to the PHP built-in DateTime.
     *
     * @return void
     *
     * @since 11/21/08
     */
    public function testPhpDatetime()
    {
        echo '<h3>conversion to PHP DateTime</h3>';

        $ref = new ReflectionClass('DateTimeZone');

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
     * Check the equality of a DateAndTime against a PHP DateTime object.
     *
     * @param object DateAndTime $dateAndTime
     * @param object DateTime $dateTime
     *
     * @return void
     *
     * @since 11/21/08
     */
    protected function checkEquality(DateAndTime $dateAndTime, DateTime $dateTime)
    {
        echo '<h4>'.$dateAndTime->asString().'</h4>';
        echo 'Year: ';
        $this->assertEquals((int) $dateTime->format('Y'), $dateAndTime->year());
        echo 'Month: ';
        $this->assertEquals((int) $dateTime->format('n'), $dateAndTime->month());
        echo 'Day of Month: ';
        $this->assertEquals((int) $dateTime->format('j'), $dateAndTime->dayOfMonth());
        echo 'Day of Year: ';
        $this->assertEquals((int) $dateTime->format('z'), $dateAndTime->dayOfYear() - 1);

        echo 'Hour: ';
        $this->assertEquals((int) $dateTime->format('G'), $dateAndTime->hour());
        echo 'Minute: ';
        $this->assertEquals((int) $dateTime->format('i'), $dateAndTime->minute());
        echo 'Second: ';
        $this->assertEquals((int) $dateTime->format('s'), $dateAndTime->second());

        // 		print "TZ abbriviation: ";
        // 		$this->assertEquals($dateTime->format('T'), $dateAndTime->timeZoneAbbreviation());

        $datTZone = $dateAndTime->timeZone();
        $dtTZone = $dateTime->getTimezone();

        echo 'TZ seconds: ';
        $this->assertEquals((int) $dateTime->format('Z'), $datTZone->offset()->asSeconds());
    }
}

// 		print "<pre>";
// 		print_r($duration);
// 		print "</pre>";
