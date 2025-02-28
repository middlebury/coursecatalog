<?php

/**
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: TimespanTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
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
 * @version $Id: TimespanTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class TimespanTest extends TestCase
{
    private $currentYear;

    /**
     *  Sets up unit test wide variables at the start
     *	 of each test method.
     */
    protected function setUp(): void
    {
        $this->currentYear = date('Y');
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
        // class methods - instance creation
        // current()
        // epoch()
        // starting()
        // startingDuration()
        // startingEnding()

        $timespan = Timespan::current();
        $this->assertEquals((int) date('Y'), $timespan->startYear());
        $this->assertEquals((int) date('n'), $timespan->startMonth());
        $this->assertEquals((int) date('j'), $timespan->dayOfMonth());
        $duration = $timespan->duration();
        $this->assertTrue($duration->isEqualTo(Duration::zero()));
        $this->assertEquals('timespan', strtolower($timespan::class));

        $timespan = Timespan::epoch();
        $this->assertEquals(1901, $timespan->startYear());
        $this->assertEquals(1, $timespan->startMonth());
        $this->assertEquals(1, $timespan->dayOfMonth());
        $duration = $timespan->duration();
        $this->assertTrue($duration->isEqualTo(Duration::zero()));
        $this->assertEquals('timespan', strtolower($timespan::class));
    }

    /**
     * Test some leap years.
     */
    public function testEnd()
    {
        $datA = DateAndTime::withYearDay(2005, 125);
        $datB = DateAndTime::withYearDay(2006, 125);

        $timespan = Timespan::startingDuration(
            DateAndTime::withYearDay(2005, 125),
            Duration::withDays(365)
        );

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
    }

    /**
     * Test comparisons.
     */
    public function testComparisons()
    {
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

        $result = $timespanA->intersection($timespanB);

        $this->assertTrue($temp->isEqualTo($result));

        $tempEnd = DateAndTime::withYearMonthDay(
            2005, 5, 14);
        $temp = Timespan::startingEnding(
            DateAndTime::withYearMonthDay(
                2005, 5, 8),
            $tempEnd->minus(DateAndTime::clockPrecision()
            ));
        $this->assertTrue($temp->isEqualTo($timespanA->intersection($timespanC)));

        $this->assertEquals(null, $timespanA->intersection($timespanD));

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
     * Test Accessing Methods.
     */
    public function testAccessing()
    {
        $timespan = Timespan::startingDuration(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
            Duration::withDays(10));
        // day()
        $this->assertEquals(124, $timespan->day());

        // dayOfMonth()
        $this->assertEquals(4, $timespan->dayOfMonth());

        // dayOfWeek()
        $this->assertEquals(4, $timespan->dayOfWeek());

        // dayOfWeekName()
        $this->assertEquals('Wednesday', $timespan->dayOfWeekName());

        // dayOfYear()
        $this->assertEquals(124, $timespan->dayOfYear());

        // daysInMonth()
        $this->assertEquals(31, $timespan->daysInMonth());

        // daysInYear()
        $this->assertEquals(365, $timespan->daysInYear());

        // daysLeftInYear()
        $this->assertEquals(241, $timespan->daysLeftInYear());

        // duration()
        $temp = Duration::withDays(10);
        $this->assertTrue($temp->isEqualTo($timespan->duration()));

        // end()
        $temp = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
            2005, 5, 14, 15, 25, 10, Duration::withHours(-4));
        $temp = $temp->minus(DateAndTime::clockPrecision());
        $this->assertTrue($temp->isEqualTo($timespan->end()));

        // firstDayOfMonth()
        $this->assertEquals(121, $timespan->firstDayOfMonth());

        // isLeapYear()
        $this->assertEquals(false, $timespan->isLeapYear());

        // julianDayNumber()
        $this->assertEquals(2453495, $timespan->julianDayNumber());

        // printableString()
        $this->assertEquals('2005-05-04T15:25:10-04:00D10:00:00:00', $timespan->printableString());

        // startMonth()
        $this->assertEquals(5, $timespan->startMonth());

        // startMonthAbbreviation()
        $this->assertEquals('May', $timespan->startMonthAbbreviation());

        // startMonthIndex()
        $this->assertEquals(5, $timespan->startMonthIndex());

        // startMonthName()
        $this->assertEquals('May', $timespan->startMonthName());

        // start()
        $temp = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
            2005, 5, 4, 15, 25, 10, Duration::withHours(-4));
        $this->assertTrue($temp->isEqualTo($timespan->start()));

        // startYear()
        $this->assertEquals(2005, $timespan->startYear());
    }

    /**
     * Test Accessing Methods.
     */
    public function testEnumeration()
    {
        $timespan = Timespan::startingDuration(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
            Duration::withDays(10));

        $timespanB = Timespan::startingDuration(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
            Duration::withDays(1000));

        // every()
        $everyTwo = $timespan->every(Duration::withDays(2));
        $this->assertCount(5, $everyTwo);
        for ($i = 0; $i < 5; ++$i) {
            $this->assertEquals('dateandtime', strtolower(get_class($everyTwo[$i])));
        }

        $temp = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
            2005, 5, 6, 15, 25, 10, Duration::withHours(-4));
        $this->assertTrue($temp->isEqualTo($everyTwo[1]));

        // dates()
        $dates = $timespan->dates();
        $this->assertCount(11, $dates);
        for ($i = 0; $i < 11; ++$i) {
            $this->assertEquals('date', strtolower(get_class($dates[$i])));
        }

        $temp = Date::withYearMonthDay(2005, 5, 4);
        $this->assertTrue($temp->isEqualTo($dates[0]));
        $temp = Date::withYearMonthDay(2005, 5, 5);
        $this->assertTrue($temp->isEqualTo($dates[1]));
        $temp = Date::withYearMonthDay(2005, 5, 6);
        $this->assertTrue($temp->isEqualTo($dates[2]));
        $temp = Date::withYearMonthDay(2005, 5, 14);
        $this->assertTrue($temp->isEqualTo($dates[10]));

        // months()
        $months = $timespan->months();
        $this->assertCount(1, $months);
        for ($i = 0; $i < 1; ++$i) {
            $this->assertEquals('month', strtolower(get_class($months[$i])));
        }

        $temp = Month::withMonthYear(5, 2005);
        $this->assertTrue($temp->isEqualTo($months[0]));

        $months = $timespanB->months();
        $this->assertCount(33, $months);

        for ($i = 0; $i < 3; ++$i) {
            $this->assertEquals('month', strtolower(get_class($months[$i])));
        }

        $temp = Month::withMonthYear(5, 2005);
        $this->assertTrue($temp->isEqualTo($months[0]));

        $temp = Month::withMonthYear(6, 2005);
        $this->assertTrue($temp->isEqualTo($months[1]));

        $temp = Month::withMonthYear(1, 2008);
        $this->assertTrue($temp->isEqualTo($months[32]));

        // weeks()
        $weeks = $timespan->weeks();
        $this->assertCount(2, $weeks);
        for ($i = 0; $i < 2; ++$i) {
            $this->assertEquals('week', strtolower(get_class($weeks[$i])));
        }

        $temp = Week::starting(Date::withYearMonthDay(2005, 5, 4));
        $this->assertTrue($temp->isEqualTo($weeks[0]));

        $temp = Week::starting(Date::withYearMonthDay(2005, 5, 14));
        $this->assertTrue($temp->isEqualTo($weeks[1]));

        // years()
        $years = $timespan->years();
        $this->assertCount(1, $years);
        for ($i = 0; $i < 1; ++$i) {
            $this->assertEquals('year', strtolower(get_class($years[$i])));
        }

        $this->assertEquals(2005, $years[0]->startYear());

        $years = $timespanB->years();
        $this->assertCount(3, $years);

        for ($i = 0; $i < 3; ++$i) {
            $this->assertEquals('year', strtolower(get_class($years[$i])));
        }

        $this->assertEquals(2005, $years[0]->startYear());

        $this->assertEquals(2006, $years[1]->startYear());

        $this->assertEquals(2007, $years[2]->startYear());
    }

    /**
     * Test Converting Methods.
     */
    public function testConverting()
    {
        // Converting
        $timespan = Timespan::startingDuration(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
            Duration::withDays(10));

        // asDate()
        $temp = $timespan->asDate();
        $this->assertTrue($temp->isEqualTo(Date::withYearMonthDay(2005, 5, 4)));

        // asDateAndTime()
        $temp = $timespan->asDateAndTime();
        $this->assertTrue($temp->isEqualTo(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4))));

        // asDuration()
        $temp = $timespan->asDuration();
        $this->assertTrue($temp->isEqualTo(Duration::withDays(10)));

        // asMonth()
        $temp = $timespan->asMonth();
        $this->assertTrue($temp->isEqualTo(Month::withMonthYear(5, 2005)));

        // asTime()
        $temp = $timespan->asTime();
        $dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecondOffset(
            2005, 5, 4, 15, 25, 10, Duration::withHours(-4));
        $this->assertTrue($temp->isEqualTo($dateAndTime->asTime()));

        // asTimeStamp()
        $temp = $timespan->asTimeStamp();
        $this->assertTrue($temp->isEqualTo(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4))));

        // asWeek()
        $temp = $timespan->asWeek();
        $this->assertTrue($temp->isEqualTo(Week::starting(Date::withYearMonthDay(2005, 5, 4))));

        // asYear()
        $temp = $timespan->asYear();
        $this->assertTrue($temp->isEqualTo(Year::starting(Date::withYearMonthDay(2005, 5, 4))));

        // to()
        $temp = $timespan->to(Date::withYearMonthDay(2005, 10, 1));
        $comparison = Timespan::startingEnding(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
            Date::withYearMonthDay(2005, 10, 1));
        $this->assertTrue($temp->isEqualTo($comparison));
    }
}
