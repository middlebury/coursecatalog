<?php

/**
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: TimeStampTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
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
 * @since 5/3/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: TimeStampTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class TimeStampTest extends TestCase
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
     * Test the creation methods.
     */
    public function testCreation()
    {
        $epoch = TimeStamp::epoch();
        $this->assertEquals('timestamp', strtolower($epoch::class));

        $timestamp = TimeStamp::current();
        $this->assertEquals('timestamp', strtolower($timestamp::class));
    }

    /**
     * Test the timestamp conversion methods.
     */
    public function testPlusMinusDays()
    {
        $timestamp = TimeStamp::withYearMonthDayHourMinuteSecond(2005, 5, 4, 15, 25, 32);
        $this->assertEquals('timestamp', strtolower($timestamp::class));

        $temp = $timestamp->minusDays(3);
        $this->assertEquals('timestamp', strtolower($temp::class));
        $this->assertEquals(2005, $temp->year());
        $this->assertEquals(5, $temp->month());
        $this->assertEquals(1, $temp->dayOfMonth());
        $this->assertEquals(15, $temp->hour());
        $this->assertEquals(25, $temp->minute());
        $this->assertEquals(32, $temp->second());
        $this->assertTrue($temp->isEqualTo(
            TimeStamp::withYearMonthDayHourMinuteSecond(2005, 5, 1, 15, 25, 32)));

        $temp = $timestamp->plusDays(3);
        $this->assertEquals('timestamp', strtolower($temp::class));
        $this->assertEquals(2005, $temp->year());
        $this->assertEquals(5, $temp->month());
        $this->assertEquals(7, $temp->dayOfMonth());
        $this->assertEquals(15, $temp->hour());
        $this->assertEquals(25, $temp->minute());
        $this->assertEquals(32, $temp->second());
        $this->assertTrue($temp->isEqualTo(
            TimeStamp::withYearMonthDayHourMinuteSecond(2005, 5, 7, 15, 25, 32)));

        $temp = $timestamp->minusSeconds(7);
        $this->assertEquals('timestamp', strtolower($temp::class));
        $this->assertEquals(2005, $temp->year());
        $this->assertEquals(5, $temp->month());
        $this->assertEquals(4, $temp->dayOfMonth());
        $this->assertEquals(15, $temp->hour());
        $this->assertEquals(25, $temp->minute());
        $this->assertEquals(25, $temp->second());
        $this->assertTrue($temp->isEqualTo(
            TimeStamp::withYearMonthDayHourMinuteSecond(2005, 5, 4, 15, 25, 25)));

        $temp = $timestamp->plusSeconds(7);
        $this->assertEquals('timestamp', strtolower($temp::class));
        $this->assertEquals(2005, $temp->year());
        $this->assertEquals(5, $temp->month());
        $this->assertEquals(4, $temp->dayOfMonth());
        $this->assertEquals(15, $temp->hour());
        $this->assertEquals(25, $temp->minute());
        $this->assertEquals(39, $temp->second());
        $this->assertTrue($temp->isEqualTo(
            TimeStamp::withYearMonthDayHourMinuteSecond(2005, 5, 4, 15, 25, 39)));
    }

    /**
     * Test the plus/minus days/seconds conversion methods.
     */
    public function testTimestampConverion()
    {
        $timestamp = TimeStamp::withYearMonthDayHourMinute(2005, 5, 4, 15, 25);
        $this->assertEquals('timestamp', strtolower($timestamp::class));

        $temp = $timestamp->date();
        $this->assertEquals('date', strtolower($temp::class));
        $this->assertTrue($temp->isEqualTo(Date::withYearMonthDay(2005, 5, 4)));

        $temp = $timestamp->time();
        $this->assertEquals('time', strtolower($temp::class));
        $this->assertTrue($temp->isEqualTo(Time::withHourMinuteSecond(15, 25, 0)));

        $temp = $timestamp->dateAndTimeArray();
        $this->assertEquals('date', strtolower(get_class($temp[0])));
        $this->assertEquals('time', strtolower(get_class($temp[1])));
        $this->assertTrue($temp[0]->isEqualTo(Date::withYearMonthDay(2005, 5, 4)));
        $this->assertTrue($temp[1]->isEqualTo(Time::withHourMinuteSecond(15, 25, 0)));
        $this->assertCount(2, $temp);
    }

    /**
     * Test conversion from Unix timestamps.
     */
    public function testFromUnixTimestamp()
    {
        $timestamp = TimeStamp::fromUnixTimeStamp(0);
        $unixEpoch = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
            1970, 1, 1, 0, 0, 0, Duration::zero());

        $this->assertTrue($timestamp->isEqualTo($unixEpoch));
        $this->assertEquals($unixEpoch, $timestamp);

        $this->assertEquals(1970, $timestamp->year());
        $this->assertEquals(1, $timestamp->month());
        $this->assertEquals(1, $timestamp->dayOfMonth());
        $this->assertEquals(0, $timestamp->hour());
        $this->assertEquals(0, $timestamp->minute());
        $this->assertEquals(0, $timestamp->second());

        $this->assertEquals(0, $timestamp->asUnixTimeStamp());

        $unixTimeStamp = time();
        $timestamp = TimeStamp::fromUnixTimeStamp($unixTimeStamp);

        $this->assertEquals(date('Y', $unixTimeStamp), $timestamp->year());
        $this->assertEquals(date('m', $unixTimeStamp), $timestamp->month());
        $this->assertEquals(date('j', $unixTimeStamp), $timestamp->dayOfMonth());
        $this->assertEquals(
            $timestamp->hour(),
            date('H', $unixTimeStamp) - (date('Z', $unixTimeStamp) / 3600)
        );
        $this->assertEquals(date('i', $unixTimeStamp), $timestamp->minute());
        $this->assertEquals(date('s', $unixTimeStamp), $timestamp->second());
        $this->assertEquals($unixTimeStamp, $timestamp->asUnixTimeStamp());
    }

    /*********************************************************
     * Methods from the date and time test case. These should
     * mostly work since Timestamp extends DateAndTime.
     *********************************************************/

    /**
     * Test the DateAndTime representing the Squeak epoch: 1 January 1901.
     */
    public function testEpoch()
    {
        echo 'test_epoch';

        $dateAndTime = TimeStamp::epoch();
        $this->assertEquals(1901, $dateAndTime->year());
        $this->assertEquals(1, $dateAndTime->month());
        $this->assertEquals(1, $dateAndTime->day());

        $dateAndTime = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
            1901, 1, 1, 0, 0, 0, $null = null);
        $this->assertEquals(1901, $dateAndTime->year());
        $this->assertEquals(1, $dateAndTime->month());
        $this->assertEquals(1, $dateAndTime->day());

        $dateAndTime = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
            1901, 'jan', 1, 0, 0, 0, $null = null);
        $this->assertEquals(1901, $dateAndTime->year());
        $this->assertEquals(1, $dateAndTime->month());
        $this->assertEquals(1, $dateAndTime->day());

        $dateAndTime = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
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
        $dateAndTime = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
            2005, 5, 4, 15, 25, 10, $null = null);
        $this->assertEquals(2005, $dateAndTime->year());
        $this->assertEquals(5, $dateAndTime->month());
        $this->assertEquals(4, $dateAndTime->dayOfMonth());
        $this->assertEquals(15, $dateAndTime->hour());
        $this->assertEquals(3, $dateAndTime->hour12());
        $this->assertEquals(25, $dateAndTime->minute());
        $this->assertEquals(10, $dateAndTime->second());
        $this->assertEquals('timestamp', strtolower($dateAndTime::class));

        $dateAndTime = TimeStamp::withYearMonthDayHourMinute(2005, 5, 4, 15, 25);
        $this->assertEquals(2005, $dateAndTime->year());
        $this->assertEquals(5, $dateAndTime->month());
        $this->assertEquals(4, $dateAndTime->dayOfMonth());
        $this->assertEquals(15, $dateAndTime->hour());
        $this->assertEquals(3, $dateAndTime->hour12());
        $this->assertEquals(25, $dateAndTime->minute());
        $this->assertEquals(0, $dateAndTime->second());
        $this->assertEquals('timestamp', strtolower($dateAndTime::class));

        $dateAndTime = TimeStamp::withYearDay(1950, 1);
        $this->assertEquals(1950, $dateAndTime->year());
        $this->assertEquals(1, $dateAndTime->month());
        $this->assertEquals(1, $dateAndTime->dayOfMonth());
        $this->assertEquals(0, $dateAndTime->hour());
        $this->assertEquals(12, $dateAndTime->hour12());
        $this->assertEquals(0, $dateAndTime->minute());
        $this->assertEquals(0, $dateAndTime->second());
        $this->assertEquals('timestamp', strtolower($dateAndTime::class));

        $dateAndTime = TimeStamp::withYearMonthDay(2005, 1, 1);
        $this->assertEquals(2005, $dateAndTime->year());
        $this->assertEquals(1, $dateAndTime->month());
        $this->assertEquals(1, $dateAndTime->dayOfMonth());
        $this->assertEquals(0, $dateAndTime->hour());
        $this->assertEquals(12, $dateAndTime->hour12());
        $this->assertEquals(0, $dateAndTime->minute());
        $this->assertEquals(0, $dateAndTime->second());
        $this->assertEquals('timestamp', strtolower($dateAndTime::class));

        $date = Date::withYearMonthDay(2005, 5, 4);
        $time = Time::withHourMinuteSecond(15, 25, 10);
        $dateAndTime = TimeStamp::withDateAndTime($date, $time);
        $this->assertEquals(2005, $dateAndTime->year());
        $this->assertEquals(5, $dateAndTime->month());
        $this->assertEquals(4, $dateAndTime->dayOfMonth());
        $this->assertEquals(15, $dateAndTime->hour());
        $this->assertEquals(3, $dateAndTime->hour12());
        $this->assertEquals(25, $dateAndTime->minute());
        $this->assertEquals(10, $dateAndTime->second());
        $this->assertEquals('timestamp', strtolower($dateAndTime::class));
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
     * Test comparisons.
     */
    public function testComparisons()
    {
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
     * Test accessing.
     */
    public function testAccessing()
    {
        $dateAndTime = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
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
        $this->assertEquals('4 June 2005 3:25:10 pm', $dateAndTime->printableString());
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
    }

    /**
     * Test converting.
     */
    public function testConverting()
    {
        $dateAndTime = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
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
        $local = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
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
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(2005, 6, 4, 0, 0, 0, Duration::withHours(-5))));

        // middleOf()
        $dat = TimeStamp::withYearDay(2005, 100);
        $timespan = $dat->middleOf(Duration::withDays(100));
        $start = $timespan->start();
        $duration = $timespan->duration();
        $end = $timespan->end();
        $this->assertEquals(50, $start->dayOfYear());
        $this->assertTrue($start->isEqualTo(DateAndTime::withYearDay(2005, 50)));
        $this->assertEquals(100, $duration->days());
        $this->assertEquals(149, $end->dayOfYear());

        // to()
        $datA = TimeStamp::withYearDay(2005, 125);
        $datB = TimeStamp::withYearDay(2006, 125);

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
        $dateAndTime = TimeStamp::withYearMonthDayHourMinuteSecondOffset(
            2005, 6, 4, 15, 25, 10, Duration::withHours(-5));

        $atUTC = $dateAndTime->utcOffset(Duration::withHours(0));

        $this->assertEquals(2453526, $dateAndTime->julianDayNumber());
        $this->assertEquals(2453526, $atUTC->julianDayNumber());
        $this->assertEquals(55510, $dateAndTime->seconds);
        $this->assertEquals(73510, $atUTC->seconds);
        $this->assertEquals(-18000, $dateAndTime->offset->asSeconds());
        $this->assertEquals(0, $atUTC->offset->asSeconds());

        $this->assertEquals('4 June 2005 3:25:10 pm', $dateAndTime->printableString());
        $this->assertEquals('4 June 2005 8:25:10 pm', $atUTC->printableString());
    }

    /**
     * Magnitude operations.
     */
    public function testMagnitudeOps()
    {
        // Plus a Duration
        $dateAndTime = TimeStamp::withYearDayHourMinuteSecond(2005, 100, 0, 0, 0);
        $this->assertEquals('timestamp', strtolower($dateAndTime::class));
        $result = $dateAndTime->plus(Duration::withSeconds(1));

        $this->assertEquals('timestamp', strtolower($result::class));
        $this->assertTrue($result->isEqualTo(TimeStamp::withYearDayHourMinuteSecond(
            2005, 100, 0, 0, 1)));

        // minus a Duration
        $dateAndTime = TimeStamp::withYearDayHourMinuteSecond(2005, 100, 0, 0, 0);
        $result = $dateAndTime->minus(Duration::withSeconds(1));

        $this->assertEquals('timestamp', strtolower($result::class));
        $this->assertEquals(2005, $result->year());
        $this->assertEquals(99, $result->dayOfYear());
        $this->assertEquals(23, $result->hour());
        $this->assertEquals(59, $result->minute());
        $this->assertEquals(59, $result->second());
        $this->assertTrue($result->isEqualTo(TimeStamp::withYearDayHourMinuteSecond(
            2005, 99, 23, 59, 59)));

        // Minus a DateAndTime
        $dateAndTime = TimeStamp::withYearDayHourMinuteSecond(2006, 100, 0, 0, 0);
        $result = $dateAndTime->minus(TimeStamp::withYearDayHourMinuteSecond(2005, 100, 0, 0, 0));

        $this->assertEquals('duration', strtolower($result::class));
        $this->assertTrue($result->isEqualTo(Duration::withDays(365)));

        // Minus a DateAndTime over a leap year
        $dateAndTime = TimeStamp::withYearDayHourMinuteSecond(2005, 10, 0, 0, 0);
        $result = $dateAndTime->minus(TimeStamp::withYearDayHourMinuteSecond(2004, 10, 0, 0, 0));

        $this->assertEquals('duration', strtolower($result::class));
        $this->assertTrue($result->isEqualTo(Duration::withDays(366)));

        // Plus a DateAndTime
        $dateAndTime = TimeStamp::withYearDayHourMinuteSecond(2000, 100, 5, 15, 30);
        $result = $dateAndTime->plus(TimeStamp::withYearDayHourMinuteSecond(
            2000, 100, 5, 30, 15));

        $this->assertEquals('timestamp', strtolower($result::class));
        $this->assertEquals(2000, $result->year());
        $this->assertEquals(100, $result->dayOfYear());
        $this->assertEquals(10, $result->hour());
        $this->assertEquals(45, $result->minute());
        $this->assertEquals(45, $result->second());
    }
}

// 		print "<pre>";
// 		print_r($duration);
// 		print "</pre>";
