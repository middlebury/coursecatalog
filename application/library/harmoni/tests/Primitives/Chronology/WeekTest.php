<?php

/**
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: WeekTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
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
 * @version $Id: WeekTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class WeekTest extends TestCase
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
        $epoch = Week::epoch();

        $this->assertEquals('week', strtolower($epoch::class));
        $this->assertEquals(1900, $epoch->startYear());
        $this->assertEquals(12, $epoch->startMonth());
        $this->assertEquals(30, $epoch->dayOfMonth());
        $this->assertEquals('December', $epoch->startMonthName());
        $start = $epoch->start();
        $this->assertEquals(0, $start->hour());
        $this->assertEquals(0, $start->minute());
        $this->assertEquals(0, $start->second());

        $duration = $epoch->duration();
        $this->assertTrue($duration->isEqualTo(Duration::withDays(7)));

        $week = Week::starting(DateAndTime::withYearMonthDayHourMinuteSecondOffset(
            2005, 5, 4, 15, 25, 10, Duration::withHours(-4)));

        $this->assertEquals('week', strtolower($week::class));
        $this->assertEquals(2005, $week->startYear());
        $this->assertEquals(5, $week->startMonth());
        $this->assertEquals(1, $week->dayOfMonth());
        $start = $week->start();
        $this->assertEquals(0, $start->hour());
        $this->assertEquals(0, $start->minute());
        $this->assertEquals(0, $start->second());
        $this->assertEquals('May', $week->startMonthName());
        $duration = $week->duration();
        $this->assertEquals(7, $duration->days());
        $this->assertTrue($week->isEqualTo(
            Week::starting(DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 3, 15, 25, 10, Duration::withHours(-4)))));
    }

    /*********************************************************
     * Tests from parent class, Timespan.
     *********************************************************/

    /**
     * Test comparisons.
     */
    public function testComparisons()
    {
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
     * Test aritmatic operations.
     */
    public function testIncludes()
    {
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
        $this->assertFalse($timespanA->includesAllOf($arg = [
            $timespanB->asDateAndTime(),
            $timespanC->asDateAndTime(),
        ]));
        $this->assertFalse($timespanA->includesAllOf($arg = [
            $timespanB->asDateAndTime(),
            $timespanC->asDateAndTime(),
            $timespanD->asDateAndTime(),
        ]));

        $this->assertFalse($timespanA->includesAnyOf($arg = [
            $timespanB->asDateAndTime(),
            $timespanC->asDateAndTime(),
        ]));
        $this->assertFalse($timespanA->includesAnyOf($arg = [
            $timespanB->asDateAndTime(),
            $timespanC->asDateAndTime(),
            $timespanD->asDateAndTime(),
        ]));
        $this->assertFalse($timespanA->includesAnyOf($arg = [
            $timespanD->asDateAndTime(),
        ]));
    }

    /**
     * Test aritmatic operations.
     */
    public function testOperations()
    {
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

        $result = $timespanC->plus(Duration::withDays(6));
        $this->assertFalse($temp->isEqualTo($result));

        $result = $timespanC->plus(Duration::withDays(8));
        $this->assertTrue($temp->isEqualTo($result));

        $result = $timespanC->plus($timespanD->asDuration());
        $this->assertTrue($temp->isEqualTo($result));

        // minus()
        // Subtracting an object that implemnts asDateAndTime
        $temp = Week::starting(
            DateAndTime::withYearMonthDay(
                2005, 5, 1));
        $result = $timespanC->minus(Duration::withDays(5));
        $this->assertTrue($temp->isEqualTo($result));

        $tempDuration = Duration::withDays(7);
        $this->assertTrue($tempDuration->isEqualTo($timespanC->minus($timespanA)));

        $tempDuration = Duration::withDays(-7);
        $this->assertTrue($tempDuration->isEqualTo($timespanA->minus($timespanC)));
        $tempDuration = Duration::zero();
        $this->assertTrue($tempDuration->isEqualTo($timespanA->minus($timespanA)));
    }

    /**
     * Test aritmatic operations.
     */
    public function testOperationsNextPrev()
    {
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
     * Test aritmatic operations.
     */
    public function testIntersectUnion()
    {
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
        $this->assertEquals(null, $timespanA->intersection($timespanB));

        $this->assertEquals(null, $timespanA->intersection($timespanC));

        $this->assertEquals(null, $timespanA->intersection($timespanD));

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
     * Test Accessing Methods.
     */
    public function testAccessing()
    {
        $timespan = Week::startingDuration(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
            Duration::withDays(10));
        // day()
        $this->assertEquals(121, $timespan->day());

        // dayOfMonth()
        $this->assertEquals(1, $timespan->dayOfMonth());

        // dayOfWeek()
        $this->assertEquals(1, $timespan->dayOfWeek());

        // dayOfWeekName()
        $this->assertEquals('Sunday', $timespan->dayOfWeekName());

        // dayOfYear()
        $this->assertEquals(121, $timespan->dayOfYear());

        // daysInMonth()
        $this->assertEquals(31, $timespan->daysInMonth());

        // daysInYear()
        $this->assertEquals(365, $timespan->daysInYear());

        // daysLeftInYear()
        $this->assertEquals(244, $timespan->daysLeftInYear());

        // duration()
        $temp = Duration::withDays(7);
        $this->assertTrue($temp->isEqualTo($timespan->duration()));

        // end()
        $temp = DateAndTime::withYearMonthDay(2005, 5, 8);
        $temp = $temp->minus(DateAndTime::clockPrecision());
        $this->assertTrue($temp->isEqualTo($timespan->end()));

        // firstDayOfMonth()
        $this->assertEquals(121, $timespan->firstDayOfMonth());

        // isLeapYear()
        $this->assertEquals(false, $timespan->isLeapYear());

        // julianDayNumber()
        $this->assertEquals(2453492, $timespan->julianDayNumber());

        // printableString()
        $this->assertEquals('2005-05-01T00:00:00-04:00D7:00:00:00', $timespan->printableString());

        // startMonth()
        $this->assertEquals(5, $timespan->startMonth());

        // startMonthAbbreviation()
        $this->assertEquals('May', $timespan->startMonthAbbreviation());

        // startMonthIndex()
        $this->assertEquals(5, $timespan->startMonthIndex());

        // startMonthName()
        $this->assertEquals('May', $timespan->startMonthName());

        // start()
        $temp = DateAndTime::withYearMonthDay(2005, 5, 1);
        $this->assertTrue($temp->isEqualTo($timespan->start()));

        // startYear()
        $this->assertEquals(2005, $timespan->startYear());
    }

    /**
     * Test Accessing Methods.
     */
    public function testEnumeration()
    {
        $timespan = Week::startingDuration(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
            Duration::withDays(10));

        $timespanB = Week::startingDuration(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
            Duration::withDays(1000));

        // every()
        $everyTwo = $timespan->every(Duration::withDays(2));
        $this->assertCount(4, $everyTwo);
        for ($i = 0; $i < 4; ++$i) {
            $this->assertEquals('dateandtime', strtolower(get_class($everyTwo[$i])));
        }

        $temp = DateAndTime::withYearMonthDayHourMinuteSecond(
            2005, 5, 3, 0, 0, 0);
        $this->assertTrue($temp->isEqualTo($everyTwo[1]));

        // dates()
        $dates = $timespan->dates();
        $this->assertCount(7, $dates);
        for ($i = 0; $i < 7; ++$i) {
            $this->assertEquals('date', strtolower(get_class($dates[$i])));
        }

        $temp = Date::withYearMonthDay(2005, 5, 1);
        $this->assertTrue($temp->isEqualTo($dates[0]));
        $temp = Date::withYearMonthDay(2005, 5, 2);
        $this->assertTrue($temp->isEqualTo($dates[1]));
        $temp = Date::withYearMonthDay(2005, 5, 3);
        $this->assertTrue($temp->isEqualTo($dates[2]));
        $temp = Date::withYearMonthDay(2005, 5, 7);
        $this->assertTrue($temp->isEqualTo($dates[6]));

        // months()
        $months = $timespan->months();
        $this->assertCount(1, $months);
        for ($i = 0; $i < 1; ++$i) {
            $this->assertEquals('month', strtolower(get_class($months[$i])));
        }

        $temp = Month::withMonthYear(5, 2005);
        $this->assertTrue($temp->isEqualTo($months[0]));

        $months = $timespanB->months();
        $this->assertCount(1, $months);

        for ($i = 0; $i < 1; ++$i) {
            $this->assertEquals('month', strtolower(get_class($months[$i])));
        }

        $temp = Month::withMonthYear(5, 2005);
        $this->assertTrue($temp->isEqualTo($months[0]));

        // weeks()
        $weeks = $timespan->weeks();
        $this->assertCount(1, $weeks);
        for ($i = 0; $i < 1; ++$i) {
            $this->assertEquals('week', strtolower(get_class($weeks[$i])));
        }

        $temp = Week::starting(Date::withYearMonthDay(2005, 5, 4));
        $this->assertTrue($temp->isEqualTo($weeks[0]));

        // years()
        $years = $timespan->years();
        $this->assertCount(1, $years);
        for ($i = 0; $i < 1; ++$i) {
            $this->assertEquals('year', strtolower(get_class($years[$i])));
        }

        $this->assertEquals(2005, $years[0]->startYear());
    }

    /**
     * Test Converting Methods.
     */
    public function testConverting()
    {
        // Converting
        $timespan = Week::startingDuration(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
            Duration::withDays(10));

        // asDate()
        $temp = $timespan->asDate();
        $this->assertTrue($temp->isEqualTo(Date::withYearMonthDay(2005, 5, 1)));

        // asDateAndTime()
        $temp = $timespan->asDateAndTime();
        $this->assertTrue($temp->isEqualTo(
            DateAndTime::withYearMonthDayHourMinuteSecond(
                2005, 5, 1, 00, 00, 00)));

        // asDuration()
        $temp = $timespan->asDuration();
        $this->assertTrue($temp->isEqualTo(Duration::withDays(7)));

        // asMonth()
        $temp = $timespan->asMonth();
        $this->assertTrue($temp->isEqualTo(Month::withMonthYear(5, 2005)));

        // asTime()
        $temp = $timespan->asTime();
        $dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecond(
            2005, 5, 1, 0, 0, 0);
        $this->assertTrue($temp->isEqualTo($dateAndTime->asTime()));

        // asTimeStamp()
        $temp = $timespan->asTimeStamp();
        $this->assertTrue($temp->isEqualTo(
            TimeStamp::withYearMonthDayHourMinuteSecond(
                2005, 5, 1, 0, 0, 0)));

        // asWeek()
        $temp = $timespan->asWeek();
        $this->assertTrue($temp->isEqualTo(Week::starting(Date::withYearMonthDay(2005, 5, 1))));

        // asYear()
        $temp = $timespan->asYear();
        $this->assertTrue($temp->isEqualTo(Year::starting(Date::withYearMonthDay(2005, 5, 1))));

        // to()
        $temp = $timespan->to(Date::withYearMonthDay(2005, 10, 1));
        $comparison = Timespan::startingEnding(
            DateAndTime::withYearMonthDayHourMinuteSecond(
                2005, 5, 1, 0, 0, 0),
            Date::withYearMonthDay(2005, 10, 1));
        $this->assertTrue($temp->isEqualTo($comparison));
    }
}
