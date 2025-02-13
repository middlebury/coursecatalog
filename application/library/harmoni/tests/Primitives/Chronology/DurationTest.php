<?php

/**
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DurationTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
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
 * @version $Id: DurationTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class DurationTest extends TestCase
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
     * Test the creation based on a number of days, as well as the basic
     * accessor methods.
     */
    public function testDayCreation()
    {
        // One day
        $duration = Duration::withDays(1);
        $this->assertEquals(1, $duration->days());
        $this->assertEquals(0, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());
        $this->assertEquals(86400, $duration->asSeconds());

        // 7 days
        $duration = Duration::withDays(7);
        $this->assertEquals(7, $duration->days());
        $this->assertEquals(0, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());
        $this->assertEquals(86400 * 7, $duration->asSeconds());
    }

    /**
     * Test the creation based on a times other than a day.
     */
    public function testNonDayCreation()
    {
        // 5 hours
        $duration = Duration::withDaysHoursMinutesSeconds(0, 5, 0, 0);
        $this->assertEquals(0, $duration->days());
        $this->assertEquals(5, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());
        $this->assertEquals(3600 * 5, $duration->asSeconds());
        $this->assertTrue($duration->isEqualTo(Duration::withHours(5)));

        // 15 minutes
        $duration = Duration::withDaysHoursMinutesSeconds(0, 0, 15, 0);
        $this->assertEquals(0, $duration->days());
        $this->assertEquals(0, $duration->hours());
        $this->assertEquals(15, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());
        $this->assertEquals(15 * 60, $duration->asSeconds());
        $this->assertTrue($duration->isEqualTo(Duration::withMinutes(15)));

        // 35 seconds
        $duration = Duration::withDaysHoursMinutesSeconds(0, 0, 0, 35);
        $this->assertEquals(0, $duration->days());
        $this->assertEquals(0, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(35, $duration->seconds());
        $this->assertEquals(35, $duration->asSeconds());
        $this->assertTrue($duration->isEqualTo(Duration::withSeconds(35)));

        // 3 days, 2 hours, 23 minutes, and 12 seconds
        $duration = Duration::withDaysHoursMinutesSeconds(3, 2, 23, 12);
        $this->assertEquals(3, $duration->days());
        $this->assertEquals(2, $duration->hours());
        $this->assertEquals(23, $duration->minutes());
        $this->assertEquals(12, $duration->seconds());
        $this->assertEquals(((3 * 24 + 2) * 60 + 23) * 60 + 12, $duration->asSeconds());

        // 48 hours
        $duration = Duration::withDaysHoursMinutesSeconds(0, 48, 0, 0);
        $this->assertEquals(2, $duration->days());
        $this->assertEquals(0, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());
        $this->assertEquals(86400 * 2, $duration->asSeconds());

        // Month
        $duration = Duration::withMonth('June');
        $this->assertEquals(30, $duration->days());
        $this->assertEquals(0, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());

        $duration = Duration::withMonth('July');
        $this->assertEquals(31, $duration->days());
        $this->assertEquals(0, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());

        $duration = Duration::withMonth(9);
        $this->assertEquals(30, $duration->days());
        $this->assertEquals(0, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());

        // Weeks
        $duration = Duration::withWeeks(1);
        $this->assertEquals(7, $duration->days());
        $this->assertEquals(0, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());

        $duration = Duration::withWeeks(5);
        $this->assertEquals(35, $duration->days());
        $this->assertEquals(0, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());

        $duration = Duration::withWeeks(1.5);
        $this->assertEquals(10, $duration->days());
        $this->assertEquals(12, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());

        $duration = Duration::withWeeks(1.5);
        $this->assertEquals(10, $duration->days());
        $this->assertEquals(12, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());

        $duration = Duration::withWeeks(1.374);
        $this->assertEquals(9, $duration->days());
        $this->assertEquals(14, $duration->hours());
        $this->assertEquals(49, $duration->minutes());
        $this->assertEquals(55, $duration->seconds());

        $duration = Duration::withWeeks(-1.374);
        $this->assertEquals(-9, $duration->days());
        $this->assertEquals(-14, $duration->hours());
        $this->assertEquals(-49, $duration->minutes());
        $this->assertEquals(-55, $duration->seconds());

        // Zero
        $duration = Duration::zero();
        $this->assertEquals(0, $duration->days());
        $this->assertEquals(0, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());
    }

    /**
     * Test instance creation from a string.
     */
    public function testFromString()
    {
        $duration = Duration::fromString('-7:09:12:06.10');
        $this->assertEquals(-7, $duration->days());
        $this->assertEquals(-9, $duration->hours());
        $this->assertEquals(-12, $duration->minutes());
        $this->assertEquals(-6, $duration->seconds());

        $duration = Duration::fromString('+0:01:02');
        $this->assertEquals(0, $duration->days());
        $this->assertEquals(1, $duration->hours());
        $this->assertEquals(2, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());

        $duration = Duration::fromString('0:00:00:00');
        $this->assertEquals(0, $duration->days());
        $this->assertEquals(0, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());

        // 50 years (18250 days)
        $duration = Duration::fromString('18250:12:00:00');
        $this->assertEquals(18250, $duration->days());
        $this->assertEquals(12, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());

        // 500 years (182500 days)
        $duration = Duration::fromString('182500:12:00:00');
        $this->assertEquals(182500, $duration->days());
        $this->assertEquals(12, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());

        // -500 years (-182500 days)
        $duration = Duration::fromString('-182500:12:00:00');
        $this->assertEquals(-182500, $duration->days());
        $this->assertEquals(-12, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());

        // 500,000,000 years (182500000000 days)
        $duration = Duration::fromString('182500000000:12:00:00');
        $this->assertEquals(182500000000, $duration->days());
        $this->assertEquals(12, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());

        // Beyond 4 billion years, the precision drops from
        // second precision to hour precision.

        // 50,000,000,000 years (18250000000000 days)
        $duration = Duration::fromString('18250000000000:12:00:00');
        $this->assertEquals(18250000000000, $duration->days());
        $this->assertEquals(12, $duration->hours());
        // 		$this->assertEquals(0, $duration->minutes());
        // 		$this->assertEquals(0, $duration->seconds());
    }

    /**
     * Test printable string.
     */
    public function testPrintableString()
    {
        $duration = Duration::withWeeks(1.374);
        $this->assertEquals('9:14:49:55', $duration->printableString());

        $duration = Duration::withWeeks(-1.374);
        $this->assertEquals('-9:14:49:55', $duration->printableString());

        $duration = Duration::withWeeks(1.5);
        $this->assertEquals('10:12:00:00', $duration->printableString());

        $duration = Duration::withDaysHoursMinutesSeconds(3, 2, 23, 7);
        $this->assertEquals('3:02:23:07', $duration->printableString());

        // 3 days, 2 hours, 23 minutes, and 12 seconds
        $duration = Duration::withDaysHoursMinutesSeconds(3, 2, 23, 12);
        $this->assertEquals('3:02:23:12', $duration->printableString());

        // -3 days, -2 hours, -23 minutes, and -12 seconds
        $duration = Duration::withDaysHoursMinutesSeconds(-3, -2, -23, -12);
        $this->assertEquals('-3:02:23:12', $duration->printableString());
    }

    /**
     * Test the creation based on a times other than a day.
     */
    public function testNegativeDurations()
    {
        $duration = Duration::withDays(-4);
        $this->assertEquals(-4, $duration->days());
        $this->assertEquals(0, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());
        $this->assertEquals(-345600, $duration->asSeconds());

        $duration = Duration::withDaysHoursMinutesSeconds(-3, -2, -23, -12);
        $this->assertEquals(-3, $duration->days());
        $this->assertEquals(-2, $duration->hours());
        $this->assertEquals(-23, $duration->minutes());
        $this->assertEquals(-12, $duration->seconds());
        $this->assertEquals(-267792, $duration->asSeconds());

        $duration = Duration::withDaysHoursMinutesSeconds(0, -2, -23, -12);
        $this->assertEquals(0, $duration->days());
        $this->assertEquals(-2, $duration->hours());
        $this->assertEquals(-23, $duration->minutes());
        $this->assertEquals(-12, $duration->seconds());
        $this->assertEquals(-8592, $duration->asSeconds());

        // Big negatives

        // -500 years (-182500 days)
        $duration = Duration::withDaysHoursMinutesSeconds(-182500, -2, -23, -12);
        $this->assertEquals(-182500, $duration->days());
        $this->assertEquals(-2, $duration->hours());
        $this->assertEquals(-23, $duration->minutes());
        $this->assertEquals(-12, $duration->seconds());
        $this->assertEquals(-15768008592, $duration->asSeconds());

        // -500,000,000 years (182500000000 days)
        $duration = Duration::withDaysHoursMinutesSeconds(-182500000000, -2, -23, -12);
        $this->assertEquals(-182500000000, $duration->days());
        $this->assertEquals(-2, $duration->hours());
        $this->assertEquals(-23, $duration->minutes());
        $this->assertEquals(-12, $duration->seconds());

        // -4,000,000,000 years (-1460000000000 days)
        $duration = Duration::withDaysHoursMinutesSeconds(-1460000000000, -2, -23, -12);
        $this->assertEquals(-1460000000000, $duration->days());
        $this->assertEquals(-2, $duration->hours());
        $this->assertEquals(-23, $duration->minutes());
        $this->assertEquals(-12, $duration->seconds());

        // Beyond negative 4 billion years, the precision drops from
        // second precision to hour precision.

        // -50,000,000,000 years (18250000000000 days)
        $duration = Duration::withDays(-18250000000000);
        $this->assertEquals(-18250000000000, $duration->days());
        $this->assertEquals(0, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());
        $this->assertEquals(0 - hexdec('0x15e1eb3ee9d60000'), $duration->asSeconds());

        // -50,000,000,000 years (-18250000000000 days)
        $duration = Duration::withDaysHoursMinutesSeconds(-18250000000000, -2, -23, -12);
        $this->assertEquals(-18250000000000, $duration->days());
        $this->assertEquals(-2, $duration->hours());
        // 		$this->assertEquals(-23, $duration->minutes());
        // 		$this->assertEquals(-12, $duration->seconds());
    }

    /**
     * Test the creation based on a times other than a day.
     */
    public function testComparison()
    {
        $duration = Duration::withDays(5);

        // Equality
        $this->assertTrue($duration->isEqualTo(Duration::withDays(5)));
        $this->assertTrue($duration->isEqualTo(
            Duration::withDaysHoursMinutesSeconds(0, 120, 0, 0)));
        $this->assertFalse($duration->isEqualTo(Duration::withDays(6)));
        $this->assertFalse($duration->isEqualTo(Duration::withDays(4)));
        $this->assertFalse($duration->isEqualTo(Duration::withDays(0)));

        // Less than
        $this->assertFalse($duration->isLessThan(Duration::withDays(5)));
        $this->assertTrue($duration->isLessThan(Duration::withDays(6)));
        $this->assertFalse($duration->isLessThan(Duration::withDays(4)));
        $this->assertFalse($duration->isLessThan(Duration::withDays(0)));

        // greater than
        $this->assertFalse($duration->isGreaterThan(Duration::withDays(5)));
        $this->assertFalse($duration->isGreaterThan(Duration::withDays(6)));
        $this->assertTrue($duration->isGreaterThan(Duration::withDays(4)));
        $this->assertTrue($duration->isGreaterThan(Duration::withDays(0)));
    }

    /**
     * Test the addition/subtraction.
     */
    public function testAddSubtract()
    {
        $duration = Duration::withDays(5);

        $duration = $duration->plus(Duration::withDays(15));
        $this->assertEquals(20, $duration->days());

        $duration = $duration->plus(Duration::withDays(15));
        $this->assertEquals(35, $duration->days());

        $duration = $duration->minus(Duration::withDays(33));
        $this->assertEquals(2, $duration->days());

        $duration = $duration->minus(Duration::withDays(1));
        $this->assertEquals(1, $duration->days());

        $duration = $duration->minus(Duration::withDays(1));
        $this->assertEquals(0, $duration->days());
        $this->assertEquals(0, $duration->seconds());

        $duration = $duration->minus(Duration::withDays(1));
        $this->assertEquals(-1, $duration->days());
        $this->assertEquals(-86400, $duration->asSeconds());

        $duration = $duration->minus(Duration::withDays(10));
        $this->assertEquals(-11, $duration->days());

        $duration = $duration->plus(Duration::withDays(5));
        $this->assertEquals(-6, $duration->days());

        $duration = $duration->plus(Duration::withDays(20));
        $this->assertEquals(14, $duration->days());
        $this->assertEquals(86400 * 14, $duration->asSeconds());
    }

    /**
     * Test the negation.
     */
    public function testNegation()
    {
        $duration = Duration::withDays(5);
        $neg = $duration->negated();
        $this->assertEquals(0, $neg->seconds());
        $this->assertEquals(-5, $neg->days());
        $ticks = $neg->ticks();
        $this->assertEquals(-5, $ticks[0]);
        $this->assertEquals(0, $ticks[1]);

        $duration = Duration::withSeconds(1);
        $neg = $duration->negated();
        $this->assertEquals(-1, $neg->seconds());
        $this->assertEquals(0, $neg->days());
        $ticks = $neg->ticks();
        $this->assertEquals(0, $ticks[0]);
        $this->assertEquals(-1, $ticks[1]);
    }

    /**
     * Test rounding.
     */
    public function testRounding()
    {
        // 3 days, 2 hours, 23 minutes, and 12 seconds
        $duration = Duration::withDaysHoursMinutesSeconds(3, 2, 23, 12);

        // To one day
        $result = $duration->roundTo(Duration::withDays(1));
        $this->assertTrue($result->isEqualTo(Duration::withDays(3)));

        // To two days
        $result = $duration->roundTo(Duration::withDays(2));
        $this->assertTrue($result->isEqualTo(Duration::withDays(4)));

        // 3 days + 2 hours = 74 hours
        // To one hour
        $result = $duration->roundTo(
            Duration::withDaysHoursMinutesSeconds(0, 1, 0, 0));
        $this->assertTrue(
            $result->isEqualTo(
                Duration::withDaysHoursMinutesSeconds(0, 74, 0, 0)));

        // To two hours
        $result = $duration->roundTo(
            Duration::withDaysHoursMinutesSeconds(0, 2, 0, 0));
        $this->assertTrue(
            $result->isEqualTo(
                Duration::withDaysHoursMinutesSeconds(0, 74, 0, 0)));

        // To three hours
        $result = $duration->roundTo(
            Duration::withDaysHoursMinutesSeconds(0, 3, 0, 0));
        $this->assertTrue(
            $result->isEqualTo(
                Duration::withDaysHoursMinutesSeconds(0, 75, 0, 0)));

        // To four hours
        $result = $duration->roundTo(
            Duration::withDaysHoursMinutesSeconds(0, 4, 0, 0));
        $this->assertTrue(
            $result->isEqualTo(
                Duration::withDaysHoursMinutesSeconds(0, 76, 0, 0)));

        // To five hours
        $result = $duration->roundTo(
            Duration::withDaysHoursMinutesSeconds(0, 5, 0, 0));
        $this->assertTrue(
            $result->isEqualTo(
                Duration::withDaysHoursMinutesSeconds(0, 75, 0, 0)));
    }

    /**
     * Test rounding.
     */
    public function testRoundingNegs()
    {
        // 3 days, 2 hours, 23 minutes, and 12 seconds
        $duration = Duration::withDaysHoursMinutesSeconds(-3, -2, -23, -12);

        // To one day
        $result = $duration->roundTo(Duration::withDays(1));
        $this->assertTrue($result->isEqualTo(Duration::withDays(-3)));

        // To two days
        $result = $duration->roundTo(Duration::withDays(2));
        $this->assertTrue($result->isEqualTo(Duration::withDays(-4)));

        // 3 days + 2 hours = 74 hours
        // To one hour
        $result = $duration->roundTo(
            Duration::withDaysHoursMinutesSeconds(0, 1, 0, 0));
        $this->assertTrue(
            $result->isEqualTo(
                Duration::withDaysHoursMinutesSeconds(0, -74, 0, 0)));

        // To two hours
        $result = $duration->roundTo(
            Duration::withDaysHoursMinutesSeconds(0, 2, 0, 0));
        $this->assertTrue(
            $result->isEqualTo(
                Duration::withDaysHoursMinutesSeconds(0, -74, 0, 0)));

        // To three hours
        $result = $duration->roundTo(
            Duration::withDaysHoursMinutesSeconds(0, 3, 0, 0));
        $this->assertTrue(
            $result->isEqualTo(
                Duration::withDaysHoursMinutesSeconds(0, -75, 0, 0)));

        // To four hours
        $result = $duration->roundTo(
            Duration::withDaysHoursMinutesSeconds(0, 4, 0, 0));
        $this->assertTrue(
            $result->isEqualTo(
                Duration::withDaysHoursMinutesSeconds(0, -76, 0, 0)));

        // To five hours
        $result = $duration->roundTo(
            Duration::withDaysHoursMinutesSeconds(0, -5, 0, 0));
        $this->assertTrue(
            $result->isEqualTo(
                Duration::withDaysHoursMinutesSeconds(0, -75, 0, 0)));
    }

    /**
     * Test truncating.
     */
    public function testTruncatingNegs()
    {
        // 3 days, 2 hours, 23 minutes, and 12 seconds
        $duration = Duration::withDaysHoursMinutesSeconds(-3, -2, -23, -12);

        // To one day
        $result = $duration->truncateTo(Duration::withDays(1));
        $this->assertTrue($result->isEqualTo(Duration::withDays(-3)));

        // To two days
        $result = $duration->truncateTo(Duration::withDays(2));
        $this->assertTrue($result->isEqualTo(Duration::withDays(-2)));

        // 3 days + 2 hours = 74 hours
        // To one hour
        $result = $duration->truncateTo(
            Duration::withDaysHoursMinutesSeconds(0, 1, 0, 0));
        $this->assertTrue(
            $result->isEqualTo(
                Duration::withDaysHoursMinutesSeconds(0, -74, 0, 0)));

        // To two hours
        $result = $duration->truncateTo(
            Duration::withDaysHoursMinutesSeconds(0, 2, 0, 0));
        $this->assertTrue(
            $result->isEqualTo(
                Duration::withDaysHoursMinutesSeconds(0, -74, 0, 0)));

        // To three hours
        $result = $duration->truncateTo(
            Duration::withDaysHoursMinutesSeconds(0, -3, 0, 0));
        $this->assertTrue(
            $result->isEqualTo(
                Duration::withDaysHoursMinutesSeconds(-3, 0, 0, 0)));

        // To four hours
        $result = $duration->truncateTo(
            Duration::withDaysHoursMinutesSeconds(0, -4, 0, 0));
        $this->assertTrue(
            $result->isEqualTo(
                Duration::withDaysHoursMinutesSeconds(-3, 0, 0, 0)));

        // To five hours
        $result = $duration->truncateTo(
            Duration::withDaysHoursMinutesSeconds(0, -5, 0, 0));
        $this->assertTrue(
            $result->isEqualTo(
                Duration::withDaysHoursMinutesSeconds(-2, -22, 0, 0)));
    }

    /**
     * Test truncating.
     */
    public function testTruncating()
    {
        // 3 days, 2 hours, 23 minutes, and 12 seconds
        $duration = Duration::withDaysHoursMinutesSeconds(3, 2, 23, 12);

        // To one day
        $result = $duration->truncateTo(Duration::withDays(1));
        $this->assertTrue($result->isEqualTo(Duration::withDays(3)));

        // To two days
        $result = $duration->truncateTo(Duration::withDays(2));
        $this->assertTrue($result->isEqualTo(Duration::withDays(2)));

        // 3 days + 2 hours = 74 hours
        // To one hour
        $result = $duration->truncateTo(
            Duration::withDaysHoursMinutesSeconds(0, 1, 0, 0));
        $this->assertTrue(
            $result->isEqualTo(
                Duration::withDaysHoursMinutesSeconds(0, 74, 0, 0)));

        // To two hours
        $result = $duration->truncateTo(
            Duration::withDaysHoursMinutesSeconds(0, 2, 0, 0));
        $this->assertTrue(
            $result->isEqualTo(
                Duration::withDaysHoursMinutesSeconds(0, 74, 0, 0)));

        // To three hours
        $result = $duration->truncateTo(
            Duration::withDaysHoursMinutesSeconds(0, 3, 0, 0));
        $this->assertTrue(
            $result->isEqualTo(
                Duration::withDaysHoursMinutesSeconds(3, 0, 0, 0)));

        // To four hours
        $result = $duration->truncateTo(
            Duration::withDaysHoursMinutesSeconds(0, 4, 0, 0));
        $this->assertTrue(
            $result->isEqualTo(
                Duration::withDaysHoursMinutesSeconds(3, 0, 0, 0)));

        // To five hours
        $result = $duration->truncateTo(
            Duration::withDaysHoursMinutesSeconds(0, 5, 0, 0));
        $this->assertTrue(
            $result->isEqualTo(
                Duration::withDaysHoursMinutesSeconds(2, 22, 0, 0)));
    }

    /**
     * Test durations that generate seconds larger than 2^32 seconds.
     * This may or may not work on some systems.
     * (32-bit) integers can be up to ~2billion.
     * This corresponds to ~100 years (36500 days).
     * The system may or may not support going over this limit.
     * The system this was developed on,.
     *
     *		Linux version 2.4.18-14 (bhcompile@stripples.devel.redhat.com)
     *			(gcc version 3.2 20020903 (Red Hat Linux 8.0 3.2-7)) #1
     *			Wed Sep 4 13:35:50 EDT 2002
     *
     *		PHP 4.3.2
     *			'./configure' '--with-apxs2=/usr/local/apache2/bin/apxs'
     *			'--enable-safe-mode' '--with-openssl' '--with-gd'
     *			'--enable-gd-native-ttf' '--with-jpeg-dir=/usr/lib'
     *			'--with-png-dir=/usr/lib' '--with-xpm-dir=/usr/lib'
     *			'--with-ttf=/usr/lib' '--with-t1lib=/usr/lib' '--with-ldap'
     *			'--with-mysql' '--with-pdflib' '--with-zlib-dir=/usr/lib'
     *			'--with-gettext' '--with-xml' '--with-pgsql=/usr'
     *			'--with-java=/usr/java' '--with-iconv=/usr/local/lib' '--with-dom'
     *
     * supports the following tests.
     */
    public function testBigDurations()
    {
        // 50 years (18250 days)
        $duration = Duration::withDays(18250);
        $this->assertEquals(18250, $duration->days());
        $this->assertEquals(0, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());
        $this->assertEquals(86400 * 18250, $duration->asSeconds());
        $this->assertEquals(1576800000, $duration->asSeconds());

        // 100 years (36500 days)
        $duration = Duration::withDays(36500);
        $this->assertEquals(36500, $duration->days());
        $this->assertEquals(0, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());
        $this->assertEquals(86400 * 36500, $duration->asSeconds());
        $this->assertEquals(3153600000, $duration->asSeconds());

        // 500 years (182500 days)
        $duration = Duration::withDays(182500);
        $this->assertEquals(182500, $duration->days());
        $this->assertEquals(0, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());
        $this->assertEquals(86400 * 182500, $duration->asSeconds());
        $this->assertEquals(15768000000, $duration->asSeconds());

        // 5000 years (1825000 days)
        $duration = Duration::withDays(1825000);
        $this->assertEquals(1825000, $duration->days());
        $this->assertEquals(0, $duration->hours());
        $this->assertEquals(0, $duration->minutes());
        $this->assertEquals(0, $duration->seconds());
        $this->assertEquals(86400 * 1825000, $duration->asSeconds());
        $this->assertEquals(157680000000, $duration->asSeconds());

        // 5000 years (1825000 days)
        // minus 500 years (182500 days)
        // should equal 4500 years (1642500 days)
        $duration = Duration::withDays(1825000);
        $result = $duration->minus(Duration::withDays(182500));

        $this->assertEquals(1642500, $result->days());
        $this->assertEquals(0, $result->hours());
        $this->assertEquals(0, $result->minutes());
        $this->assertEquals(0, $duration->seconds());
        $this->assertEquals(86400 * 1642500, $result->asSeconds());
        $this->assertEquals(141912000000, $result->asSeconds());

        // 500,000,000 years (182500000000 days)
        // minus 500 years (182500 days)
        // should equal 499,999,500 years (182481750000 days)
        $duration = Duration::withDays(182500000000);
        $result = $duration->minus(Duration::withDays(182500));

        $this->assertEquals(182499817500, $result->days());
        $this->assertEquals(0, $result->hours());
        $this->assertEquals(0, $result->minutes());
        $this->assertEquals(0, $duration->seconds());
        $this->assertEquals(86400 * 182499817500, $result->asSeconds());
        $this->assertEquals(hexdec('3804e5eaf8ea00'), $result->asSeconds());

        // 50,000,000,000 years (18250000000000 days)
        // minus 500 years (182500 days)
        // should equal 49,999,999,500 years (18249999817500 days)
        $duration = Duration::withDays(18250000000000);
        $result = $duration->minus(Duration::withDays(182500));

        $this->assertEquals(18249999817500, $result->days());
        $this->assertEquals(0, $result->hours());
        $this->assertEquals(0, $result->minutes());
        $this->assertEquals(0, $duration->seconds());
        $this->assertEquals(86400 * 18249999817500, $result->asSeconds());
        $this->assertEquals(hexdec('15e1eb3b3dfd6a00'), $result->asSeconds());

        // Beyond negative 4 billion years, the precision drops from
        // second precision to hour precision.

        // 4,000,000,000 years (1460000000000 days)
        $duration = Duration::withDaysHoursMinutesSeconds(1460000000000, 2, 23, 12);
        $this->assertEquals(1460000000000, $duration->days());
        $this->assertEquals(2, $duration->hours());
        $this->assertEquals(23, $duration->minutes());
        $this->assertEquals(12, $duration->seconds());

        // 50,000,000,000 years (18250000000000 days)
        $duration = Duration::withDaysHoursMinutesSeconds(18250000000000, 2, 23, 12);
        $this->assertEquals(18250000000000, $duration->days());
        $this->assertEquals(2, $duration->hours());
        // 		$this->assertEquals(23, $duration->minutes());
        // 		$this->assertEquals(12, $duration->seconds());
    }
}

// 		print "<pre>";
// 		print_r($duration);
// 		print "</pre>";
