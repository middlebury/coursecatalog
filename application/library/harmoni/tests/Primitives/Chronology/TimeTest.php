<?php

/**
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: TimeTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
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
 * @version $Id: TimeTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class TimeTest extends TestCase
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
        // withHourMinuteSecond ()
        // withSeconds ()

        $sTime = Time::withSeconds(55510);
        $hmsTime = Time::withHourMinuteSecond(15, 25, 10);

        $this->assertEquals(55510, $sTime->asSeconds());
        $this->assertEquals(55510, $hmsTime->asSeconds());

        $this->assertEquals(15, $sTime->hour());
        $this->assertEquals(15, $hmsTime->hour());

        $this->assertEquals(25, $sTime->minute());
        $this->assertEquals(25, $hmsTime->minute());

        $this->assertEquals(10, $sTime->second());
        $this->assertEquals(10, $hmsTime->second());

        // with times greater than a day
        $sTime = Time::withSeconds(55510 + 86400);
        $hmsTime = Time::withHourMinuteSecond(15 + 24, 25, 10);

        $this->assertEquals(55510, $sTime->asSeconds());
        $this->assertEquals(55510, $hmsTime->asSeconds());

        $this->assertEquals(15, $sTime->hour());
        $this->assertEquals(15, $hmsTime->hour());

        $this->assertEquals(25, $sTime->minute());
        $this->assertEquals(25, $hmsTime->minute());

        $this->assertEquals(10, $sTime->second());
        $this->assertEquals(10, $hmsTime->second());

        // atMidnight()
        $midnight = Time::midnight();
        // atNoon()
        $noon = Time::noon();

        $this->assertEquals(0, $midnight->asSeconds());
        $this->assertEquals(43200, $noon->asSeconds());

        $this->assertEquals(0, $midnight->hour());
        $this->assertEquals(12, $noon->hour());

        $this->assertEquals(0, $midnight->minute());
        $this->assertEquals(0, $noon->minute());

        $this->assertEquals(0, $midnight->second());
        $this->assertEquals(0, $noon->second());
    }

    /**
     * Test instance creation from a string.
     */
    public function testFromString()
    {
        // fromString ()
        $time = Time::withHourMinuteSecond(0, 0, 0);
        $this->assertTrue($time->isEqualTo(Time::fromString('2005-08-20')));

        $time = Time::withHourMinuteSecond(15, 25, 10);
        $this->assertTrue($time->isEqualTo(Time::fromString('2005-08-20T15:25:10-07:00')));
        $this->assertTrue($time->isEqualTo(Time::fromString('2005-08-20T15:25:10')));
        $this->assertTrue($time->isEqualTo(Time::fromString('20050820152510')));
        $this->assertTrue($time->isEqualTo(Time::fromString('15:25:10')));
        $this->assertTrue($time->isEqualTo(Time::fromString('3:25:10 pm')));

        $time = Time::withHourMinuteSecond(15, 25, 0);
        $this->assertTrue($time->isEqualTo(Time::fromString('15:25')));
        $this->assertTrue($time->isEqualTo(Time::fromString('3:25 pm')));
        $this->assertTrue($time->isEqualTo(Time::fromString('3:25 PM')));
        $this->assertTrue($time->isEqualTo(Time::fromString('3:25PM')));

        $time = Time::withHourMinuteSecond(15, 0, 0);
        $this->assertTrue($time->isEqualTo(Time::fromString('3pm')));

        $time = Time::withHourMinuteSecond(8, 25, 0);
        $this->assertTrue($time->isEqualTo(Time::fromString('8:25')));
        $this->assertTrue($time->isEqualTo(Time::fromString('8:25AM')));
    }

    /**
     * Test accessing methods.
     */
    public function testAccessing()
    {
        $time = Time::withHourMinuteSecond(15, 25, 10);

        // duration ()
        $temp = $time->duration();
        $this->assertTrue($temp->isEqualTo(Duration::zero()));

        // hour ()
        $this->assertEquals(15, $time->hour());

        // hour12 ()
        $this->assertEquals(3, $time->hour12());

        // hour24 ()
        $this->assertEquals(15, $time->hour24());

        // meridianAbbreviation ()
        $this->assertEquals('PM', $time->meridianAbbreviation());

        // minute ()
        $this->assertEquals(25, $time->minute());

        // string12 ()
        $this->assertEquals('3:25:10 pm', $time->string12());

        // string24 ()
        $this->assertEquals('15:25:10', $time->string24());

        // printableString ()
        $this->assertEquals('3:25:10 pm', $time->printableString());

        // second ()
        $this->assertEquals(10, $time->second());
    }

    /**
     * Test comparison methods.
     */
    public function testComparison()
    {
        $timeA = Time::withHourMinuteSecond(15, 25, 10);
        $timeB = Time::withHourMinuteSecond(8, 15, 0);
        $timeAAlso = Time::withHourMinuteSecond(15, 25, 10);

        // isEqualTo ()
        $this->assertTrue($timeA->isEqualTo($timeAAlso));
        $this->assertFalse($timeA->isEqualTo($timeB));

        // isLessThan ()
        $this->assertFalse($timeA->isLessThan($timeAAlso));
        $this->assertFalse($timeA->isLessThan($timeB));
        $this->assertTrue($timeB->isLessThan($timeA));
    }

    /**
     * Test add/subtract.
     */
    public function testAddSubtract()
    {
        $time = Time::withHourMinuteSecond(15, 25, 10);

        // addSeconds ()
        $temp = $time->addSeconds(60);
        $this->assertTrue($temp->isEqualTo(Time::withHourMinuteSecond(15, 26, 10)));

        $temp = $time->addSeconds(86400);
        $this->assertTrue($temp->isEqualTo(Time::withHourMinuteSecond(15, 25, 10)));

        $temp = $time->addSeconds(-86400);
        $this->assertTrue($temp->isEqualTo(Time::withHourMinuteSecond(15, 25, 10)));

        // addTime ()
        $timeB = Time::withHourMinuteSecond(5, 0, 0);
        $temp = $time->addTime($timeB);
        $this->assertTrue($temp->isEqualTo(Time::withHourMinuteSecond(20, 25, 10)));

        $timeB = Time::withHourMinuteSecond(12, 0, 0);
        $temp = $time->addTime($timeB);
        $this->assertTrue($temp->isEqualTo(Time::withHourMinuteSecond(3, 25, 10)));

        // subtractTime ()
        $timeB = Time::withHourMinuteSecond(5, 0, 0);
        $temp = $time->subtractTime($timeB);
        $this->assertTrue($temp->isEqualTo(Time::withHourMinuteSecond(10, 25, 10)));

        $timeB = Time::withHourMinuteSecond(20, 0, 0);
        $temp = $time->subtractTime($timeB);
        $this->assertTrue($temp->isEqualTo(Time::withHourMinuteSecond(19, 25, 10)));
    }

    /**
     * Test converting methods.
     */
    public function testConverting()
    {
        $time = Time::withHourMinuteSecond(15, 25, 10);

        // asDate ()
        $temp = $time->asDate();
        $this->assertTrue($temp->isEqualTo(Date::today()));
        $this->assertEquals('date', strtolower($temp::class));

        // asDateAndTime ()
        $temp = $time->asDateAndTime();
        $comparison = DateAndTime::midnight();
        $comparison = $comparison->plus(Duration::withSeconds(55510));
        $this->assertTrue($temp->isEqualTo($comparison));
        $this->assertEquals('dateandtime', strtolower($temp::class));

        // asDuration ()
        $temp = $time->asDuration();
        $this->assertTrue($temp->isEqualTo(Duration::withSeconds(55510)));
        $this->assertEquals('duration', strtolower($temp::class));

        // asMonth ()
        $temp = $time->asMonth();
        $this->assertTrue($temp->isEqualTo(Month::starting(Date::today())));
        $this->assertEquals('month', strtolower($temp::class));

        // asSeconds ()
        $this->assertEquals(55510, $time->asSeconds());

        // asTime ()
        $temp = $time->asTime();
        $this->assertTrue($temp->isEqualTo($time));
        $this->assertEquals('time', strtolower($temp::class));

        // asTimeStamp ()
        $temp = $time->asTimeStamp();
        $comparison = TimeStamp::midnight();
        $comparison = $comparison->plus(Duration::withSeconds(55510));
        $this->assertTrue($temp->isEqualTo($comparison));
        $this->assertEquals('timestamp', strtolower($temp::class));

        // asWeek ()
        $temp = $time->asWeek();
        $this->assertTrue($temp->isEqualTo(Week::starting(Date::today())));
        $this->assertEquals('week', strtolower($temp::class));

        // asYear ()
        $temp = $time->asYear();
        $this->assertTrue($temp->isEqualTo(Year::starting(Date::today())));
        $this->assertEquals('year', strtolower($temp::class));

        // to ()
        $today = DateAndTime::today();
        $tomorrow = DateAndTime::tomorrow();

        $result = $time->to($tomorrow);
        $this->assertEquals('timespan', strtolower($result::class));
        $this->assertTrue($result->isEqualTo(Timespan::startingDuration(
            $today->plus(Duration::withSeconds(55510)),
            Duration::withDaysHoursMinutesSeconds(0, 8, 34, 50))));

        $result = $time->to(Time::withHourMinuteSecond(23, 25, 10));
        $this->assertEquals('timespan', strtolower($result::class));
        $this->assertTrue($result->isEqualTo(Timespan::startingDuration(
            $today->plus(Duration::withSeconds(55510)),
            Duration::withDaysHoursMinutesSeconds(0, 8, 0, 0))));
    }
}
