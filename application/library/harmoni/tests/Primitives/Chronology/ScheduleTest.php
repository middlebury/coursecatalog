<?php

/**
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ScheduleTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 *
 * @since 5/25/05
 */

use PHPUnit\Framework\TestCase;

/**
 * A single unit test case. This class is intended to test one particular
 * class. Replace 'testedclass.php' below with the class you would like to
 * test.
 *
 * @since 5/25/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ScheduleTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class ScheduleTest extends TestCase
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
     * Test the enumeration methods.
     */
    public function testSingledurationEnumeration()
    {
        $schedule = Schedule::startingDuration(
            DateAndTime::withYearMonthDay(2005, 5, 15),
            Duration::withDays(7));
        $durations = [];
        $durations[] = Duration::withDays(1);
        $schedule->setSchedule($durations);

        $this->assertEquals($durations, $schedule->getSchedule());

        $datesAndTimes = [];
        $datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 15);
        $datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 16);
        $datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 17);
        $datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 18);
        $datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 19);
        $datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 20);
        $datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 21);

        $this->assertEquals($datesAndTimes, $schedule->dateAndTimes());

        $datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 22);
        $this->assertNotEquals($datesAndTimes, $schedule->dateAndTimes());

        $datesAndTimes = [];
        $datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 17);
        $datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 18);
        $datesAndTimes[] = DateAndTime::withYearMonthDay(2005, 5, 19);

        $this->assertEquals(
            $datesAndTimes,
            $schedule->between(
                DateAndTime::withYearMonthDay(2005, 5, 17),
                DateAndTime::withYearMonthDay(2005, 5, 19)
            )
        );
    }

    /**
     * Test the enumeration methods.
     */
    public function testMultipledurationEnumeration()
    {
        $schedule = Schedule::startingDuration(
            DateAndTime::withYearMonthDay(2005, 5, 15),
            Duration::withDays(7));
        $durations = [];
        $durations[] = Duration::withDays(1);
        $durations[] = Duration::withHours(1);
        $schedule->setSchedule($durations);

        $this->assertEquals($durations, $schedule->getSchedule());

        $datesAndTimes = [];
        $datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 15, 0, 0, 0);
        $datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 16, 0, 0, 0);
        $datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 16, 1, 0, 0);
        $datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 17, 1, 0, 0);
        $datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 17, 2, 0, 0);
        $datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 18, 2, 0, 0);
        $datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 18, 3, 0, 0);
        $datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 19, 3, 0, 0);
        $datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 19, 4, 0, 0);
        $datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 20, 4, 0, 0);
        $datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 20, 5, 0, 0);
        $datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 21, 5, 0, 0);
        $datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 21, 6, 0, 0);

        $this->assertEquals($datesAndTimes, $schedule->dateAndTimes());

        $datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 21, 5, 0, 0);
        $this->assertNotEquals($datesAndTimes, $schedule->dateAndTimes());

        $datesAndTimes = [];
        $datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 17, 1, 0, 0);
        $datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 18, 1, 0, 0);
        $datesAndTimes[] = DateAndTime::withYearMonthDayHourMinuteSecond(2005, 5, 18, 2, 0, 0);

        $this->assertEquals(
            $datesAndTimes,
            $schedule->between(
                DateAndTime::withYearMonthDay(2005, 5, 17),
                DateAndTime::withYearMonthDay(2005, 5, 19)
            )
        );
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

        $timespan = Schedule::current();
        $this->assertEquals((int) date('Y'), $timespan->startYear());
        $this->assertEquals((int) date('n'), $timespan->startMonth());
        $this->assertEquals((int) date('j'), $timespan->dayOfMonth());
        $duration = $timespan->duration();
        $this->assertTrue($duration->isEqualTo(Duration::zero()));
        $this->assertEquals('schedule', strtolower($timespan::class));

        $timespan = Schedule::epoch();
        $this->assertEquals(1901, $timespan->startYear());
        $this->assertEquals(1, $timespan->startMonth());
        $this->assertEquals(1, $timespan->dayOfMonth());
        $duration = $timespan->duration();
        $this->assertTrue($duration->isEqualTo(Duration::zero()));
        $this->assertEquals('schedule', strtolower($timespan::class));
    }

    /**
     * Test some leap years.
     */
    public function testEnd()
    {
        $datA = DateAndTime::withYearDay(2005, 125);
        $datB = DateAndTime::withYearDay(2006, 125);

        $timespan = Schedule::startingDuration(
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
}
