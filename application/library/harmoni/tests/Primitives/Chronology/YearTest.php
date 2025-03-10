<?php

/**
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: YearTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
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
 * @version $Id: YearTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class YearTest extends TestCase
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
        $epochYear = Year::epoch();

        $this->assertEquals('year', strtolower($epochYear::class));
        $this->assertEquals(1, $epochYear->dayOfYear());
        $this->assertEquals(365, $epochYear->daysInYear());

        $duration = $epochYear->duration();
        $this->assertTrue($duration->isEqualTo(Duration::withDays(365)));
        $this->assertEquals(1901, $epochYear->startYear());

        $current = Year::current();
        $this->assertEquals($this->currentYear, $current->startYear());

        $aYear = Year::withYear(1999);
        $this->assertEquals(1999, $aYear->startYear());
        $aYear = Year::withYear(2005);
        $this->assertEquals(2005, $aYear->startYear());

        $aYear = Year::starting(DateAndTime::withYearDay(1982, 25));
        $this->assertEquals(1982, $aYear->startYear());
        $this->assertEquals(25, $aYear->dayOfYear());
        $this->assertEquals(365, $aYear->daysInYear());
    }

    /**
     * Test some leap years.
     */
    public function testLeapYears()
    {
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
        $this->assertEquals(1980, $aYear->startYear());
        $this->assertEquals(55, $aYear->dayOfYear());
        $this->assertEquals(366, $aYear->daysInYear());

        $aYear = Year::withYear(1980);
        $this->assertEquals(1980, $aYear->startYear());
        $this->assertEquals(1, $aYear->dayOfYear());
        $this->assertEquals(366, $aYear->daysInYear());

        $aYear = Year::withYear(2000);
        $this->assertEquals(2000, $aYear->startYear());
        $this->assertEquals(1, $aYear->dayOfYear());
        $this->assertEquals(366, $aYear->daysInYear());
    }

    /**
     * Test printing.
     */
    public function testPrinting()
    {
        $year = Year::withYear(2005);

        $this->assertEquals('2005', $year->printableString());
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
     * Test aritmatic operations.
     */
    public function testIncludes()
    {
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
        $result = $timespanC->plus(Duration::withDays(4));
        $this->assertEquals('year', strtolower($result::class));
        $this->assertTrue($temp->isEqualTo($result));

        // minus()
        // Subtracting an object that implemnts asDateAndTime
        $temp = Year::starting(
            DateAndTime::withYearMonthDay(
                2005, 5, 15));
        $result = $timespanC->minus(Duration::withDays(5));
        $this->assertEquals('year', strtolower($result::class));
        $this->assertTrue($temp->isEqualTo($result));

        $tempDuration = Duration::withDays(-12);
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
     * Test aritmatic operations.
     */
    public function testIntersectUnion()
    {
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

        $this->assertEquals(null, $timespanA->intersection($timespanD));

        // union()
        $temp = Timespan::startingDuration(
            DateAndTime::withYearMonthDay(
                2005, 5, 4),
            Duration::withDays(371));
        $union = $timespanA->union($timespanB);
        $this->assertTrue($temp->isEqualTo($union));
        $unionDuration = $union->duration();
        $this->assertEquals(371, $unionDuration->days());

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
     * Test Accessing Methods.
     */
    public function testAccessing()
    {
        $timespan = Year::startingDuration(
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
        $temp = Duration::withDays(365);
        $this->assertTrue($temp->isEqualTo($timespan->duration()));

        // end()
        $temp = DateAndTime::withYearMonthDay(2006, 5, 4);
        $temp = $temp->minus(DateAndTime::clockPrecision());
        $this->assertTrue($temp->isEqualTo($timespan->end()));

        // firstDayOfMonth()
        $this->assertEquals(121, $timespan->firstDayOfMonth());

        // isLeapYear()
        $this->assertEquals(false, $timespan->isLeapYear());

        // julianDayNumber()
        $this->assertEquals(2453495, $timespan->julianDayNumber());

        // printableString()
        $this->assertEquals('2005', $timespan->printableString());

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
        $timespan = Year::startingDuration(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
            Duration::withDays(10));

        $timespanB = Year::startingDuration(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
            Duration::withDays(1000));

        // every()
        $everyTwo = $timespan->every(Duration::withDays(2));
        $this->assertCount(183, $everyTwo);
        for ($i = 0; $i < 16; ++$i) {
            $this->assertEquals('dateandtime', strtolower(get_class($everyTwo[$i])));
        }

        $temp = DateAndTime::withYearMonthDayHourMinuteSecond(
            2005, 5, 6, 0, 0, 0);
        $this->assertTrue($temp->isEqualTo($everyTwo[1]));

        // dates()
        $dates = $timespan->dates();
        $this->assertCount(365, $dates);
        for ($i = 0; $i < 7; ++$i) {
            $this->assertEquals('date', strtolower(get_class($dates[$i])));
        }

        $temp = Date::withYearMonthDay(2005, 5, 4);
        $this->assertTrue($temp->isEqualTo($dates[0]));
        $temp = Date::withYearMonthDay(2005, 5, 5);
        $this->assertTrue($temp->isEqualTo($dates[1]));
        $temp = Date::withYearMonthDay(2005, 5, 6);
        $this->assertTrue($temp->isEqualTo($dates[2]));

        // months()
        $months = $timespan->months();
        $this->assertCount(13, $months);
        for ($i = 0; $i < 13; ++$i) {
            $this->assertEquals('month', strtolower(get_class($months[$i])));
        }

        $temp = Month::withMonthYear(5, 2005);
        $this->assertTrue($temp->isEqualTo($months[0]));

        $months = $timespanB->months();
        $this->assertCount(13, $months);

        for ($i = 0; $i < 13; ++$i) {
            $this->assertEquals('month', strtolower(get_class($months[$i])));
        }

        $temp = Month::withMonthYear(5, 2005);
        $this->assertTrue($temp->isEqualTo($months[0]));

        $temp = Month::withMonthYear(5, 2006);
        $this->assertTrue($temp->isEqualTo($months[12]));

        // weeks()
        $weeks = $timespan->weeks();
        $this->assertCount(53, $weeks);
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
        $timespan = Year::startingDuration(
            DateAndTime::withYearMonthDayHourMinuteSecondOffset(
                2005, 5, 4, 15, 25, 10, Duration::withHours(-4)),
            Duration::withDays(10));

        // asDate()
        $temp = $timespan->asDate();
        $this->assertTrue($temp->isEqualTo(Date::withYearMonthDay(2005, 5, 4)));

        // asDateAndTime()
        $temp = $timespan->asDateAndTime();
        $this->assertTrue($temp->isEqualTo(
            DateAndTime::withYearMonthDayHourMinuteSecond(
                2005, 5, 4, 00, 00, 00)));

        // asDuration()
        $temp = $timespan->asDuration();
        $this->assertTrue($temp->isEqualTo(Duration::withDays(365)));

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
