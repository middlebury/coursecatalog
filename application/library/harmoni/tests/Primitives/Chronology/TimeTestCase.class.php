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

require_once __DIR__.'/../Time.class.php';

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
class TimeTestCase extends UnitTestCase
{
    /**
     *  Sets up unit test wide variables at the start
     *	 of each test method.
     */
    protected function setUp()
    {
        // perhaps, initialize $obj here
    }

    /**
     *	  Clears the data set in the setUp() method call.
     */
    protected function tearDown()
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

        $this->assertEqual($sTime->asSeconds(), 55510);
        $this->assertEqual($hmsTime->asSeconds(), 55510);

        $this->assertEqual($sTime->hour(), 15);
        $this->assertEqual($hmsTime->hour(), 15);

        $this->assertEqual($sTime->minute(), 25);
        $this->assertEqual($hmsTime->minute(), 25);

        $this->assertEqual($sTime->second(), 10);
        $this->assertEqual($hmsTime->second(), 10);

        // with times greater than a day
        $sTime = Time::withSeconds(55510 + 86400);
        $hmsTime = Time::withHourMinuteSecond(15 + 24, 25, 10);

        $this->assertEqual($sTime->asSeconds(), 55510);
        $this->assertEqual($hmsTime->asSeconds(), 55510);

        $this->assertEqual($sTime->hour(), 15);
        $this->assertEqual($hmsTime->hour(), 15);

        $this->assertEqual($sTime->minute(), 25);
        $this->assertEqual($hmsTime->minute(), 25);

        $this->assertEqual($sTime->second(), 10);
        $this->assertEqual($hmsTime->second(), 10);

        // atMidnight()
        $midnight = Time::midnight();
        // atNoon()
        $noon = Time::noon();

        $this->assertEqual($midnight->asSeconds(), 0);
        $this->assertEqual($noon->asSeconds(), 43200);

        $this->assertEqual($midnight->hour(), 0);
        $this->assertEqual($noon->hour(), 12);

        $this->assertEqual($midnight->minute(), 0);
        $this->assertEqual($noon->minute(), 0);

        $this->assertEqual($midnight->second(), 0);
        $this->assertEqual($noon->second(), 0);
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
        $this->assertEqual($time->hour(), 15);

        // hour12 ()
        $this->assertEqual($time->hour12(), 3);

        // hour24 ()
        $this->assertEqual($time->hour24(), 15);

        // meridianAbbreviation ()
        $this->assertEqual($time->meridianAbbreviation(), 'PM');

        // minute ()
        $this->assertEqual($time->minute(), 25);

        // string12 ()
        $this->assertEqual($time->string12(), '3:25:10 pm');

        // string24 ()
        $this->assertEqual($time->string24(), '15:25:10');

        // printableString ()
        $this->assertEqual($time->printableString(), '3:25:10 pm');

        // second ()
        $this->assertEqual($time->second(), 10);
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
        $this->assertEqual(strtolower($temp::class), 'date');

        // asDateAndTime ()
        $temp = $time->asDateAndTime();
        $comparison = DateAndTime::midnight();
        $comparison = $comparison->plus(Duration::withSeconds(55510));
        $this->assertTrue($temp->isEqualTo($comparison));
        $this->assertEqual(strtolower($temp::class), 'dateandtime');

        // asDuration ()
        $temp = $time->asDuration();
        $this->assertTrue($temp->isEqualTo(Duration::withSeconds(55510)));
        $this->assertEqual(strtolower($temp::class), 'duration');

        // asMonth ()
        $temp = $time->asMonth();
        $this->assertTrue($temp->isEqualTo(Month::starting(Date::today())));
        $this->assertEqual(strtolower($temp::class), 'month');

        // asSeconds ()
        $this->assertEqual($time->asSeconds(), 55510);

        // asTime ()
        $temp = $time->asTime();
        $this->assertTrue($temp->isEqualTo($time));
        $this->assertEqual(strtolower($temp::class), 'time');

        // asTimeStamp ()
        $temp = $time->asTimeStamp();
        $comparison = TimeStamp::midnight();
        $comparison = $comparison->plus(Duration::withSeconds(55510));
        $this->assertTrue($temp->isEqualTo($comparison));
        $this->assertEqual(strtolower($temp::class), 'timestamp');

        // asWeek ()
        $temp = $time->asWeek();
        $this->assertTrue($temp->isEqualTo(Week::starting(Date::today())));
        $this->assertEqual(strtolower($temp::class), 'week');

        // asYear ()
        $temp = $time->asYear();
        $this->assertTrue($temp->isEqualTo(Year::starting(Date::today())));
        $this->assertEqual(strtolower($temp::class), 'year');

        // to ()
        $today = DateAndTime::today();
        $tomorrow = DateAndTime::tomorrow();

        $result = $time->to($tomorrow);
        $this->assertEqual(strtolower($result::class), 'timespan');
        $this->assertTrue($result->isEqualTo(Timespan::startingDuration(
            $today->plus(Duration::withSeconds(55510)),
            Duration::withDaysHoursMinutesSeconds(0, 8, 34, 50))));

        $result = $time->to(Time::withHourMinuteSecond(23, 25, 10));
        $this->assertEqual(strtolower($result::class), 'timespan');
        $this->assertTrue($result->isEqualTo(Timespan::startingDuration(
            $today->plus(Duration::withSeconds(55510)),
            Duration::withDaysHoursMinutesSeconds(0, 8, 0, 0))));
    }
}
