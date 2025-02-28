<?php

/**
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: MonthTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
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
 * @version $Id: MonthTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class MonthTest extends TestCase
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
        $epochMonth = Month::epoch();

        $this->assertEquals('month', strtolower($epochMonth::class));
        $this->assertEquals(1, $epochMonth->dayOfMonth());
        $this->assertEquals(1, $epochMonth->dayOfYear());
        $this->assertEquals(31, $epochMonth->daysInMonth());
        $this->assertEquals(1, $epochMonth->startMonthIndex());
        $this->assertEquals('January', $epochMonth->startMonthName());
        $this->assertEquals('Jan', $epochMonth->startMonthAbbreviation());

        $duration = $epochMonth->duration();
        $this->assertTrue($duration->isEqualTo(Duration::withDays(31)));
    }

    /**
     * Test instance creation from a string.
     */
    public function testFromString()
    {
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
     * Test days in month.
     */
    public function testDaysInMonth()
    {
        $this->assertEquals(31, Month::daysInMonthForYear(1, 1999));
        $this->assertEquals(28, Month::daysInMonthForYear(2, 1999));
        $this->assertEquals(31, Month::daysInMonthForYear(3, 1999));
        $this->assertEquals(30, Month::daysInMonthForYear(4, 1999));
        $this->assertEquals(31, Month::daysInMonthForYear(5, 1999));
        $this->assertEquals(30, Month::daysInMonthForYear(6, 1999));
        $this->assertEquals(31, Month::daysInMonthForYear(7, 1999));
        $this->assertEquals(31, Month::daysInMonthForYear(8, 1999));
        $this->assertEquals(30, Month::daysInMonthForYear(9, 1999));
        $this->assertEquals(31, Month::daysInMonthForYear(10, 1999));
        $this->assertEquals(30, Month::daysInMonthForYear(11, 1999));
        $this->assertEquals(31, Month::daysInMonthForYear(12, 1999));

        $this->assertEquals(31, Month::daysInMonthForYear(1, 2000));
        $this->assertEquals(29, Month::daysInMonthForYear(2, 2000));
        $this->assertEquals(31, Month::daysInMonthForYear(3, 2000));
        $this->assertEquals(30, Month::daysInMonthForYear(4, 2000));
        $this->assertEquals(31, Month::daysInMonthForYear(5, 2000));
        $this->assertEquals(30, Month::daysInMonthForYear(6, 2000));
        $this->assertEquals(31, Month::daysInMonthForYear(7, 2000));
        $this->assertEquals(31, Month::daysInMonthForYear(8, 2000));
        $this->assertEquals(30, Month::daysInMonthForYear(9, 2000));
        $this->assertEquals(31, Month::daysInMonthForYear(10, 2000));
        $this->assertEquals(30, Month::daysInMonthForYear(11, 2000));
        $this->assertEquals(31, Month::daysInMonthForYear(12, 2000));
    }

    /**
     * Test name and index.
     */
    public function testNameIndex()
    {
        $month = Month::withMonthYear(5, 2005);

        $this->assertEquals(5, $month->index());
        $this->assertEquals('May', $month->name());
    }

    /**
     * Test printing.
     */
    public function testPrinting()
    {
        $month = Month::withMonthYear(8, 2005);

        $this->assertEquals('August 2005', $month->printableString());
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
     * Test aritmatic operations.
     */
    public function testIncludes()
    {
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
        $this->assertTrue($timespanA->includesAllOf($arg = [
            $timespanB->asDateAndTime(),
            $timespanC->asDateAndTime(),
        ]));
        $this->assertFalse($timespanA->includesAllOf($arg = [
            $timespanB->asDateAndTime(),
            $timespanC->asDateAndTime(),
            $timespanD->asDateAndTime(),
        ]));

        $this->assertTrue($timespanA->includesAnyOf($arg = [
            $timespanB->asDateAndTime(),
            $timespanC->asDateAndTime(),
        ]));
        $this->assertTrue($timespanA->includesAnyOf($arg = [
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
        $result = $timespanC->minus(Duration::withDays(5));
        $this->assertTrue($temp->isEqualTo($result));

        $tempDuration = Duration::withDays(-31);
        $this->assertTrue($tempDuration->isEqualTo($timespanC->minus($timespanD)));

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
     * Test aritmatic operations.
     */
    public function testIntersectUnion()
    {
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

        $this->assertEquals(null, $timespanA->intersection($timespanD));

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
     * Test Accessing Methods.
     */
    public function testAccessing()
    {
        $timespan = Month::startingDuration(
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
        $temp = Duration::withDays(31);
        $this->assertTrue($temp->isEqualTo($timespan->duration()));

        // end()
        $temp = DateAndTime::withYearMonthDay(2005, 6, 1);
        $temp = $temp->minus(DateAndTime::clockPrecision());
        $this->assertTrue($temp->isEqualTo($timespan->end()));

        // firstDayOfMonth()
        $this->assertEquals(121, $timespan->firstDayOfMonth());

        // isLeapYear()
        $this->assertEquals(false, $timespan->isLeapYear());

        // julianDayNumber()
        $this->assertEquals(2453492, $timespan->julianDayNumber());

        // printableString()
        $this->assertEquals('May 2005', $timespan->printableString());

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
        $timespan = Month::startingDuration(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
            Duration::withDays(10));

        $timespanB = Month::startingDuration(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
            Duration::withDays(1000));

        // every()
        $everyTwo = $timespan->every(Duration::withDays(2));
        $this->assertCount(16, $everyTwo);
        for ($i = 0; $i < 16; ++$i) {
            $this->assertEquals('dateandtime', strtolower(get_class($everyTwo[$i])));
        }

        $temp = DateAndTime::withYearMonthDayHourMinuteSecond(
            2005, 5, 3, 0, 0, 0);
        $this->assertTrue($temp->isEqualTo($everyTwo[1]));

        // dates()
        $dates = $timespan->dates();
        $this->assertCount(31, $dates);
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
        $this->assertCount(5, $weeks);
        for ($i = 0; $i < 5; ++$i) {
            $this->assertEquals('week', strtolower(get_class($weeks[$i])));
        }

        $temp = Week::starting(Date::withYearMonthDay(2005, 5, 1));
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
        $timespan = Month::startingDuration(
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
        $this->assertTrue($temp->isEqualTo(Duration::withDays(31)));

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
