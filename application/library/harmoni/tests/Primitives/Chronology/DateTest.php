<?php

/**
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DateTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
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
 * @version $Id: DateTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class DateTest extends TestCase
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
        $epoch = Date::epoch();

        $this->assertEquals('date', strtolower($epoch::class));
        $this->assertEquals(1, $epoch->dayOfMonth());
        $this->assertEquals(1, $epoch->dayOfYear());
        $this->assertEquals(1, $epoch->startMonthIndex());
        $this->assertEquals('January', $epoch->startMonthName());
        $this->assertEquals('Jan', $epoch->startMonthAbbreviation());

        $duration = $epoch->duration();
        $this->assertTrue($duration->isEqualTo(Duration::withDays(1)));
    }

    /**
     * Test instance creation from a string.
     */
    public function testFromString()
    {
        $date = Date::withYearMonthDay(2005, 8, 20);

        $this->assertTrue($date->isEqualTo(Date::fromString('2005-08-20')));
        $this->assertTrue($date->isEqualTo(Date::fromString('2005-08-20T15:25:10')));
        $this->assertTrue($date->isEqualTo(Date::fromString('20050820152510')));
        $this->assertTrue($date->isEqualTo(Date::fromString('08/20/2005')));
        $this->assertTrue($date->isEqualTo(Date::fromString('August 20, 2005')));
        $this->assertTrue($date->isEqualTo(Date::fromString('20aug05')));
    }

    /**
     * Test add/subtract days.
     */
    public function testAddSubtractDays()
    {
        $date = Date::withYearMonthDay(2005, 5, 20);

        $result = $date->addDays(5);
        $this->assertTrue($result->isEqualTo(Date::withYearMonthDay(2005, 5, 25)));

        $result = $date->subtractDays(5);
        $this->assertTrue($result->isEqualTo(Date::withYearMonthDay(2005, 5, 15)));
    }

    /**
     * Test previousDayNamed.
     */
    public function testPreviousDayNamed()
    {
        // The 20th is a Friday
        $date = Date::withYearMonthDay(2005, 5, 20);
        $this->assertEquals(6, $date->dayOfWeek());

        $result = $date->previousDayNamed('Thursday');
        $this->assertEquals(5, $result->dayOfWeek());
        $this->assertTrue($result->isEqualTo(Date::withYearMonthDay(2005, 5, 19)));

        $result = $date->previousDayNamed('Wednesday');
        $this->assertEquals(4, $result->dayOfWeek());
        $this->assertTrue($result->isEqualTo(Date::withYearMonthDay(2005, 5, 18)));

        $result = $date->previousDayNamed('Tuesday');
        $this->assertEquals(3, $result->dayOfWeek());
        $this->assertTrue($result->isEqualTo(Date::withYearMonthDay(2005, 5, 17)));

        $result = $date->previousDayNamed('Monday');
        $this->assertEquals(2, $result->dayOfWeek());
        $this->assertTrue($result->isEqualTo(Date::withYearMonthDay(2005, 5, 16)));

        $result = $date->previousDayNamed('Sunday');
        $this->assertEquals(1, $result->dayOfWeek());
        $this->assertTrue($result->isEqualTo(Date::withYearMonthDay(2005, 5, 15)));

        $result = $date->previousDayNamed('Saturday');
        $this->assertEquals(7, $result->dayOfWeek());
        $this->assertTrue($result->isEqualTo(Date::withYearMonthDay(2005, 5, 14)));

        $result = $date->previousDayNamed('Friday');
        $this->assertEquals(6, $result->dayOfWeek());
        $this->assertTrue($result->isEqualTo(Date::withYearMonthDay(2005, 5, 13)));
    }

    /**
     * Test printing.
     */
    public function testPrinting()
    {
        $date = Date::withYearMonthDay(2005, 8, 20);

        $this->assertEquals('08/20/2005', $date->mmddyyyyString());
        $this->assertEquals('2005-08-20', $date->yyyymmddString());
        $this->assertEquals('20 August 2005', $date->printableString());
        $this->assertEquals(
            '8/20/2005',
            $date->printableStringWithFormat([2, 1, 3, '/', 1, 1, 1])
        );
        $this->assertEquals(
            '08/20/05',
            $date->printableStringWithFormat([2, 1, 3, '/', 1, 2, 2])
        );
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

        $timespanA = Date::startingDuration(
            DateAndTime::withYearDay(1950, 1),
            Duration::withDays(10));
        $timespanB = Date::startingDuration(
            DateAndTime::withYearDay(1950, 2),
            Duration::withDays(1));

        $this->assertFalse($timespanA->isEqualTo($timespanB));
        $this->assertTrue($timespanA->isLessThan($timespanB));
        $this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
        $this->assertFalse($timespanA->isGreaterThan($timespanB));
        $this->assertFalse($timespanA->isGreaterThanOrEqualTo($timespanB));

        $timespanA = Date::starting(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4)));
        $timespanB = Date::starting(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-5)));

        $this->assertTrue($timespanA->isEqualTo($timespanB));
        $this->assertFalse($timespanA->isLessThan($timespanB));
        $this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
        $this->assertFalse($timespanA->isGreaterThan($timespanB));
        $this->assertTrue($timespanA->isGreaterThanOrEqualTo($timespanB));

        $timespanA = Date::starting(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 3, 4, 16, 25, 10, Duration::withHours(-4)));
        $timespanB = Date::starting(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-5)));

        $this->assertFalse($timespanA->isEqualTo($timespanB));
        $this->assertTrue($timespanA->isLessThan($timespanB));
        $this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
        $this->assertFalse($timespanA->isGreaterThan($timespanB));
        $this->assertFalse($timespanA->isGreaterThanOrEqualTo($timespanB));

        $timespanA = Date::starting(DateAndTime::withYearMonthDay(2005, 5, 4));
        $timespanB = Date::starting(DateAndTime::withYearMonthDay(2005, 7, 4));
        $this->assertFalse($timespanA->isEqualTo($timespanB));
        $this->assertTrue($timespanA->isLessThan($timespanB));
        $this->assertTrue($timespanA->isLessThanOrEqualTo($timespanB));
        $this->assertFalse($timespanA->isGreaterThan($timespanB));
        $this->assertFalse($timespanA->isGreaterThanOrEqualTo($timespanB));

        $timespanA = Date::starting(DateAndTime::withYearMonthDay(2005, 5, 4));
        $timespanB = Date::starting(DateAndTime::withYearMonthDay(2005, 2, 4));
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
        $timespanA = Date::startingDuration(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
            Duration::withDays(10));

        $timespanB = Date::startingDuration(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
            Duration::withDays(1));

        $timespanC = Date::startingEnding(
            DateAndTime::withYearMonthDay(
                2005, 5, 8),
            DateAndTime::withYearMonthDay(
                2005, 5, 17));

        $timespanD = Date::startingEnding(
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
        $timespanA = Date::starting(
            DateAndTime::withYearMonthDay(
                2005, 5, 4));

        $timespanB = Date::starting(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 10, 12, 0, 0, Duration::withHours(-4)));

        $timespanC = Date::startingDuration(
            DateAndTime::withYearMonthDay(
                2005, 5, 8),
            Duration::withDays(9));

        $timespanD = Date::startingEnding(
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
        $timespanA = Date::startingDuration(
            DateAndTime::withYearMonthDay(
                2005, 5, 4),
            Duration::withDays(10));

        $timespanB = Date::startingDuration(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
            Duration::withDays(1));

        $timespanC = Date::startingEnding(
            DateAndTime::withYearMonthDay(
                2005, 5, 8),
            DateAndTime::withYearMonthDay(
                2005, 5, 17));

        $timespanD = Date::startingEnding(
            DateAndTime::withYearMonthDay(
                2005, 5, 17),
            DateAndTime::withYearMonthDay(
                2005, 5, 21));

        $temp = Date::startingDuration(
            DateAndTime::withYearMonthDay(
                2005, 5, 14),
            Duration::withDays(10));
        $this->assertTrue($temp->isEqualTo($timespanA->next()));
        $temp = Date::startingDuration(
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
        $timespanA = Date::startingDuration(
            DateAndTime::withYearMonthDay(
                2005, 5, 4),
            Duration::withDays(10));

        $timespanB = Date::startingDuration(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 10, 12, 0, 0, Duration::withHours(-4)),
            Duration::withDays(1));

        $timespanC = Date::startingEnding(
            DateAndTime::withYearMonthDay(
                2005, 5, 8),
            DateAndTime::withYearMonthDay(
                2005, 5, 17));

        $timespanD = Date::startingEnding(
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
        $timespan = Date::starting(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4)));
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
        $temp = Duration::withDays(1);
        $this->assertTrue($temp->isEqualTo($timespan->duration()));

        // end()
        $temp = DateAndTime::withYearMonthDay(2005, 5, 5);
        $temp = $temp->minus(DateAndTime::clockPrecision());
        $this->assertTrue($temp->isEqualTo($timespan->end()));

        // firstDayOfMonth()
        $this->assertEquals(121, $timespan->firstDayOfMonth());

        // isLeapYear()
        $this->assertEquals(false, $timespan->isLeapYear());

        // julianDayNumber()
        $this->assertEquals(2453495, $timespan->julianDayNumber());

        // printableString()
        $this->assertEquals('4 May 2005', $timespan->printableString());

        // startMonth()
        $this->assertEquals(5, $timespan->startMonth());

        // startMonthAbbreviation()
        $this->assertEquals('May', $timespan->startMonthAbbreviation());

        // startMonthIndex()
        $this->assertEquals(5, $timespan->startMonthIndex());

        // startMonthName()
        $this->assertEquals('May', $timespan->startMonthName());

        // start()
        $temp = DateAndTime::withYearMonthDay(2005, 5, 4);
        $this->assertTrue($temp->isEqualTo($timespan->start()));

        // startYear()
        $this->assertEquals(2005, $timespan->startYear());
    }

    /**
     * Test Accessing Methods.
     */
    public function testEnumeration()
    {
        $timespan = Date::starting(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4)));

        $timespanB = Date::startingDuration(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 0, 0, 0, Duration::withHours(-4)),
            Duration::withDays(1000));

        // every()
        $everyTwo = $timespan->every(Duration::withDays(2));
        $this->assertCount(1, $everyTwo);
        for ($i = 0; $i < 1; ++$i) {
            $this->assertEquals('dateandtime', strtolower(get_class($everyTwo[$i])));
        }

        $temp = DateAndTime::withYearMonthDayHourMinuteSecond(
            2005, 5, 4, 0, 0, 0);
        $this->assertTrue($temp->isEqualTo($everyTwo[0]));

        // dates()
        $dates = $timespan->dates();
        $this->assertCount(1, $dates);
        for ($i = 0; $i < 1; ++$i) {
            $this->assertEquals('date', strtolower(get_class($dates[$i])));
        }

        $temp = Date::withYearMonthDay(2005, 5, 4);
        $this->assertTrue($temp->isEqualTo($dates[0]));

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
        $timespan = Date::starting(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4)));

        // asDate()
        $temp = $timespan->asDate();
        $this->assertTrue($temp->isEqualTo(Date::withYearMonthDay(2005, 5, 4)));

        // asDateAndTime()
        $temp = $timespan->asDateAndTime();
        $this->assertTrue($temp->isEqualTo(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 0, 0, 0, Duration::withHours(0))));

        // asDuration()
        $temp = $timespan->asDuration();
        $this->assertTrue($temp->isEqualTo(Duration::withDays(1)));

        // asMonth()
        $temp = $timespan->asMonth();
        $this->assertTrue($temp->isEqualTo(Month::withMonthYear(5, 2005)));

        // asTime()
        $temp = $timespan->asTime();
        $dateAndTime = DateAndTime::withYearMonthDayHourMinuteSecond(
            2005, 5, 4, 0, 0, 0);
        $this->assertTrue($temp->isEqualTo($dateAndTime->asTime()));

        // asTimeStamp()
        $temp = $timespan->asTimeStamp();
        $this->assertTrue($temp->isEqualTo(
            TimeStamp::withYearMonthDayHourMinuteSecond(
                2005, 5, 4, 0, 0, 0)));

        // asWeek()
        $temp = $timespan->asWeek();
        $this->assertTrue($temp->isEqualTo(Week::starting(Date::withYearMonthDay(2005, 5, 1))));

        // asYear()
        $temp = $timespan->asYear();
        $this->assertTrue($temp->isEqualTo(Year::starting(Date::withYearMonthDay(2005, 5, 4))));

        // to()
        $temp = $timespan->to(Date::withYearMonthDay(2005, 10, 1));
        $comparison = Timespan::startingEnding(
            DateAndTime::withYearMonthDayHourMinuteSecond(
                2005, 5, 4, 0, 0, 0),
            Date::withYearMonthDay(2005, 10, 1));
        $this->assertTrue($temp->isEqualTo($comparison));
    }
}
