<?php
/** 
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DurationTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 * @since 5/3/05
 */

require_once(dirname(__FILE__)."/../Duration.class.php");

/**
 * A single unit test case. This class is intended to test one particular
 * class. Replace 'testedclass.php' below with the class you would like to
 * test.
 *
 * @since 5/3/05
 *
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DurationTestCase.class.php,v 1.3 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

class DurationTestCase extends UnitTestCase {
	
	/**
	*  Sets up unit test wide variables at the start
	*	 of each test method.
	*	 @access public
	*/
	function setUp() {
		// perhaps, initialize $obj here
	}
	
	/**
	 *	  Clears the data set in the setUp() method call.
	 *	  @access public
	 */
	function tearDown() {
		// perhaps, unset $obj here
	}
	
	/**
	 * Test the creation based on a number of days, as well as the basic
	 * accessor methods.
	 */ 
	function test_day_creation() {
		// One day
		$duration = Duration::withDays(1);
		$this->assertEqual($duration->days(), 1);
		$this->assertEqual($duration->hours(), 0);
		$this->assertEqual($duration->minutes(), 0);
		$this->assertEqual($duration->seconds(), 0);
		$this->assertEqual($duration->asSeconds(), 86400);
		
		// 7 days
		$duration = Duration::withDays(7);
		$this->assertEqual($duration->days(), 7);
		$this->assertEqual($duration->hours(), 0);
		$this->assertEqual($duration->minutes(), 0);
		$this->assertEqual($duration->seconds(), 0);
		$this->assertEqual($duration->asSeconds(), 86400*7);
	}
	
	/**
	 * Test the creation based on a times other than a day.
	 */ 
	function test_non_day_creation() {
		// 5 hours
		$duration = Duration::withDaysHoursMinutesSeconds(0, 5, 0, 0);
		$this->assertEqual($duration->days(), 0);
		$this->assertEqual($duration->hours(), 5);
		$this->assertEqual($duration->minutes(), 0);
		$this->assertEqual($duration->seconds(), 0);
		$this->assertEqual($duration->asSeconds(), 3600*5);
		$this->assertTrue($duration->isEqualTo(Duration::withHours(5)));
		
		// 15 minutes
		$duration = Duration::withDaysHoursMinutesSeconds(0, 0, 15, 0);
		$this->assertEqual($duration->days(), 0);
		$this->assertEqual($duration->hours(), 0);
		$this->assertEqual($duration->minutes(), 15);
		$this->assertEqual($duration->seconds(), 0);
		$this->assertEqual($duration->asSeconds(), 15*60);
		$this->assertTrue($duration->isEqualTo(Duration::withMinutes(15)));
		
		// 35 seconds
		$duration = Duration::withDaysHoursMinutesSeconds(0, 0, 0, 35);
		$this->assertEqual($duration->days(), 0);
		$this->assertEqual($duration->hours(), 0);
		$this->assertEqual($duration->minutes(), 0);
		$this->assertEqual($duration->seconds(), 35);
		$this->assertEqual($duration->asSeconds(), 35);
		$this->assertTrue($duration->isEqualTo(Duration::withSeconds(35)));
		
		// 3 days, 2 hours, 23 minutes, and 12 seconds
		$duration = Duration::withDaysHoursMinutesSeconds(3, 2, 23, 12);
		$this->assertEqual($duration->days(), 3);
		$this->assertEqual($duration->hours(), 2);
		$this->assertEqual($duration->minutes(), 23);
		$this->assertEqual($duration->seconds(), 12);
		$this->assertEqual($duration->asSeconds(), ((3*24+2)*60+23)*60+12);
		
		// 48 hours
		$duration = Duration::withDaysHoursMinutesSeconds(0, 48, 0, 0);
		$this->assertEqual($duration->days(), 2);
		$this->assertEqual($duration->hours(), 0);
		$this->assertEqual($duration->minutes(), 0);
		$this->assertEqual($duration->seconds(), 0);
		$this->assertEqual($duration->asSeconds(), 86400*2);
		
		
		// Month
		$duration = Duration::withMonth('June');
		$this->assertEqual($duration->days(), 30);
		$this->assertEqual($duration->hours(), 0);
		$this->assertEqual($duration->minutes(), 0);
		$this->assertEqual($duration->seconds(), 0);
		
		$duration = Duration::withMonth('July');
		$this->assertEqual($duration->days(), 31);
		$this->assertEqual($duration->hours(), 0);
		$this->assertEqual($duration->minutes(), 0);
		$this->assertEqual($duration->seconds(), 0);
		
		$duration = Duration::withMonth(9);
		$this->assertEqual($duration->days(), 30);
		$this->assertEqual($duration->hours(), 0);
		$this->assertEqual($duration->minutes(), 0);
		$this->assertEqual($duration->seconds(), 0);
		
		
		// Weeks
		$duration = Duration::withWeeks(1);
		$this->assertEqual($duration->days(), 7);
		$this->assertEqual($duration->hours(), 0);
		$this->assertEqual($duration->minutes(), 0);
		$this->assertEqual($duration->seconds(), 0);
		
		$duration = Duration::withWeeks(5);
		$this->assertEqual($duration->days(), 35);
		$this->assertEqual($duration->hours(), 0);
		$this->assertEqual($duration->minutes(), 0);
		$this->assertEqual($duration->seconds(), 0);
		
		$duration = Duration::withWeeks(1.5);
		$this->assertEqual($duration->days(), 10);
		$this->assertEqual($duration->hours(), 12);
		$this->assertEqual($duration->minutes(), 0);
		$this->assertEqual($duration->seconds(), 0);
		
		$duration = Duration::withWeeks(1.5);
		$this->assertEqual($duration->days(), 10);
		$this->assertEqual($duration->hours(), 12);
		$this->assertEqual($duration->minutes(), 0);
		$this->assertEqual($duration->seconds(), 0);
		
		$duration = Duration::withWeeks(1.374);
		$this->assertEqual($duration->days(), 9);
		$this->assertEqual($duration->hours(), 14);
		$this->assertEqual($duration->minutes(), 49);
		$this->assertEqual($duration->seconds(), 55);
		
		$duration = Duration::withWeeks(-1.374);
		$this->assertEqual($duration->days(), -9);
		$this->assertEqual($duration->hours(), -14);
		$this->assertEqual($duration->minutes(), -49);
		$this->assertEqual($duration->seconds(), -55);
		
		//Zero
		$duration = Duration::zero();
		$this->assertEqual($duration->days(), 0);
		$this->assertEqual($duration->hours(), 0);
		$this->assertEqual($duration->minutes(), 0);
		$this->assertEqual($duration->seconds(), 0);

	}
	
	/**
	 * Test instance creation from a string.
	 * 
	 */
	function test_from_string () {
		$duration = Duration::fromString('-7:09:12:06.10');
		$this->assertEqual($duration->days(), -7);
		$this->assertEqual($duration->hours(), -9);
		$this->assertEqual($duration->minutes(), -12);
		$this->assertEqual($duration->seconds(), -6);
		
		$duration = Duration::fromString('+0:01:02');
		$this->assertEqual($duration->days(), 0);
		$this->assertEqual($duration->hours(), 1);
		$this->assertEqual($duration->minutes(), 2);
		$this->assertEqual($duration->seconds(), 0);
		
		$duration = Duration::fromString('0:00:00:00');
		$this->assertEqual($duration->days(), 0);
		$this->assertEqual($duration->hours(), 0);
		$this->assertEqual($duration->minutes(), 0);
		$this->assertEqual($duration->seconds(), 0);
		
		// 50 years (18250 days)
		$duration = Duration::fromString('18250:12:00:00');
		$this->assertEqual($duration->days(), 18250);
		$this->assertEqual($duration->hours(), 12);
		$this->assertEqual($duration->minutes(), 0);
		$this->assertEqual($duration->seconds(), 0);
		
		// 500 years (182500 days)
		$duration = Duration::fromString('182500:12:00:00');
		$this->assertEqual($duration->days(), 182500);
		$this->assertEqual($duration->hours(), 12);
		$this->assertEqual($duration->minutes(), 0);
		$this->assertEqual($duration->seconds(), 0);
		
		// -500 years (-182500 days)
		$duration = Duration::fromString('-182500:12:00:00');
		$this->assertEqual($duration->days(), -182500);
		$this->assertEqual($duration->hours(), -12);
		$this->assertEqual($duration->minutes(), 0);
		$this->assertEqual($duration->seconds(), 0);
		
		// 500,000,000 years (182500000000 days)
		$duration = Duration::fromString('182500000000:12:00:00');
		$this->assertEqual($duration->days(), 182500000000);
		$this->assertEqual($duration->hours(), 12);
		$this->assertEqual($duration->minutes(), 0);
		$this->assertEqual($duration->seconds(), 0);
		
		// Beyond 4 billion years, the precision drops from
		// second precision to hour precision.
		
		// 50,000,000,000 years (18250000000000 days)
		$duration = Duration::fromString('18250000000000:12:00:00');
		$this->assertEqual($duration->days(), 18250000000000);
		$this->assertEqual($duration->hours(), 12);
// 		$this->assertEqual($duration->minutes(), 0);
// 		$this->assertEqual($duration->seconds(), 0);
		
	}
	
	/**
	 * Test printable string.
	 * 
	 */
	function test_printable_string () {
		$duration = Duration::withWeeks(1.374);
		$this->assertEqual($duration->printableString(), '9:14:49:55');
		
		$duration = Duration::withWeeks(-1.374);
		$this->assertEqual($duration->printableString(), '-9:14:49:55');
		
		$duration = Duration::withWeeks(1.5);
		$this->assertEqual($duration->printableString(), '10:12:00:00');
		
		$duration = Duration::withDaysHoursMinutesSeconds(3, 2, 23, 7);
		$this->assertEqual($duration->printableString(), '3:02:23:07');
		
		// 3 days, 2 hours, 23 minutes, and 12 seconds
		$duration = Duration::withDaysHoursMinutesSeconds(3, 2, 23, 12);
		$this->assertEqual($duration->printableString(), '3:02:23:12');
		
		// -3 days, -2 hours, -23 minutes, and -12 seconds
		$duration = Duration::withDaysHoursMinutesSeconds(-3, -2, -23, -12);
		$this->assertEqual($duration->printableString(), '-3:02:23:12');
	}
	
	/**
	 * Test the creation based on a times other than a day.
	 */ 
	function test_negative_durations() {
		$duration = Duration::withDays(-4);
		$this->assertEqual($duration->days(), -4);
		$this->assertEqual($duration->hours(), 0);
		$this->assertEqual($duration->minutes(), 0);
		$this->assertEqual($duration->seconds(), 0);
		$this->assertEqual($duration->asSeconds(), -345600);
		
		$duration = Duration::withDaysHoursMinutesSeconds(-3, -2, -23, -12);
		$this->assertEqual($duration->days(), -3);
		$this->assertEqual($duration->hours(), -2);
		$this->assertEqual($duration->minutes(), -23);
		$this->assertEqual($duration->seconds(), -12);
		$this->assertEqual($duration->asSeconds(), -267792);
		
		$duration = Duration::withDaysHoursMinutesSeconds(0, -2, -23, -12);
		$this->assertEqual($duration->days(), 0);
		$this->assertEqual($duration->hours(), -2);
		$this->assertEqual($duration->minutes(), -23);
		$this->assertEqual($duration->seconds(), -12);
		$this->assertEqual($duration->asSeconds(), -8592);
		
		// Big negatives		
		
		// -500 years (-182500 days)
		$duration = Duration::withDaysHoursMinutesSeconds(-182500, -2, -23, -12);
		$this->assertEqual($duration->days(), -182500);
		$this->assertEqual($duration->hours(), -2);
		$this->assertEqual($duration->minutes(), -23);
		$this->assertEqual($duration->seconds(), -12);
		$this->assertEqual($duration->asSeconds(), -15768008592);
		
		// -500,000,000 years (182500000000 days)
		$duration = Duration::withDaysHoursMinutesSeconds(-182500000000, -2, -23, -12);
		$this->assertEqual($duration->days(), -182500000000);
		$this->assertEqual($duration->hours(), -2);
		$this->assertEqual($duration->minutes(), -23);
		$this->assertEqual($duration->seconds(), -12);
		
		// -4,000,000,000 years (-1460000000000 days)
		$duration = Duration::withDaysHoursMinutesSeconds(-1460000000000, -2, -23, -12);
		$this->assertEqual($duration->days(), -1460000000000);
		$this->assertEqual($duration->hours(), -2);
		$this->assertEqual($duration->minutes(), -23);
		$this->assertEqual($duration->seconds(), -12);
		
		// Beyond negative 4 billion years, the precision drops from
		// second precision to hour precision.
		
		// -50,000,000,000 years (18250000000000 days)
		$duration = Duration::withDays(-18250000000000);
		$this->assertEqual($duration->days(), -18250000000000);
		$this->assertEqual($duration->hours(), 0);
		$this->assertEqual($duration->minutes(), 0);
		$this->assertEqual($duration->seconds(), 0);
		$this->assertEqual($duration->asSeconds(), 0 - hexdec('0x15e1eb3ee9d60000'));
		
		// -50,000,000,000 years (-18250000000000 days)
		$duration = Duration::withDaysHoursMinutesSeconds(-18250000000000, -2, -23, -12);
		$this->assertEqual($duration->days(), -18250000000000);
		$this->assertEqual($duration->hours(), -2);
// 		$this->assertEqual($duration->minutes(), -23);
// 		$this->assertEqual($duration->seconds(), -12);
	}
	
	/**
	 * Test the creation based on a times other than a day.
	 */ 
	function test_comparison() {
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
	function test_add_subtract() {
		$duration = Duration::withDays(5);
		
		$duration =$duration->plus(Duration::withDays(15));
		$this->assertEqual($duration->days(), 20);
		
		$duration =$duration->plus(Duration::withDays(15));
		$this->assertEqual($duration->days(), 35);
		
		$duration =$duration->minus(Duration::withDays(33));
		$this->assertEqual($duration->days(), 2);
		
		$duration =$duration->minus(Duration::withDays(1));
		$this->assertEqual($duration->days(), 1);
		
		$duration =$duration->minus(Duration::withDays(1));
		$this->assertEqual($duration->days(), 0);
		$this->assertEqual($duration->seconds(), 0);
		
		$duration =$duration->minus(Duration::withDays(1));
		$this->assertEqual($duration->days(), -1);
		$this->assertEqual($duration->asSeconds(), -86400);
		
		$duration =$duration->minus(Duration::withDays(10));
		$this->assertEqual($duration->days(), -11);
		
		$duration =$duration->plus(Duration::withDays(5));
		$this->assertEqual($duration->days(), -6);
		
		$duration =$duration->plus(Duration::withDays(20));
		$this->assertEqual($duration->days(), 14);
		$this->assertEqual($duration->asSeconds(), 86400*14);
	}
	
	/**
	 * Test the negation
	 */ 
	function test_negation() {
		$duration = Duration::withDays(5);
		$neg =$duration->negated();
		$this->assertEqual($neg->seconds(), 0);
		$this->assertEqual($neg->days(), -5);
		$ticks = $neg->ticks();
		$this->assertEqual($ticks[0], -5);
		$this->assertEqual($ticks[1], 0);
		
		$duration = Duration::withSeconds(1);
		$neg =$duration->negated();
		$this->assertEqual($neg->seconds(), -1);
		$this->assertEqual($neg->days(), 0);
		$ticks = $neg->ticks();
		$this->assertEqual($ticks[0], 0);
		$this->assertEqual($ticks[1], -1);
	}
	
	/**
	 * Test rounding.
	 */ 
	function test_rounding() {
		// 3 days, 2 hours, 23 minutes, and 12 seconds
		$duration = Duration::withDaysHoursMinutesSeconds(3, 2, 23, 12);
		
		// To one day
		$result =$duration->roundTo(Duration::withDays(1));
		$this->assertTrue($result->isEqualTo(Duration::withDays(3)));
		
		// To two days
		$result =$duration->roundTo(Duration::withDays(2));
		$this->assertTrue($result->isEqualTo(Duration::withDays(4)));
		
		// 3 days + 2 hours = 74 hours
		// To one hour
		$result =$duration->roundTo(
			Duration::withDaysHoursMinutesSeconds(0, 1, 0, 0));
		$this->assertTrue(
			$result->isEqualTo(
				Duration::withDaysHoursMinutesSeconds(0, 74, 0, 0)));
		
		// To two hours
		$result =$duration->roundTo(
			Duration::withDaysHoursMinutesSeconds(0, 2, 0, 0));
		$this->assertTrue(
			$result->isEqualTo(
				Duration::withDaysHoursMinutesSeconds(0, 74, 0, 0)));
		
		// To three hours
		$result =$duration->roundTo(
			Duration::withDaysHoursMinutesSeconds(0, 3, 0, 0));
		$this->assertTrue(
			$result->isEqualTo(
				Duration::withDaysHoursMinutesSeconds(0, 75, 0, 0)));
		
		// To four hours
		$result =$duration->roundTo(
			Duration::withDaysHoursMinutesSeconds(0, 4, 0, 0));
		$this->assertTrue(
			$result->isEqualTo(
				Duration::withDaysHoursMinutesSeconds(0, 76, 0, 0)));
				
		// To five hours
		$result =$duration->roundTo(
			Duration::withDaysHoursMinutesSeconds(0, 5, 0, 0));
		$this->assertTrue(
			$result->isEqualTo(
				Duration::withDaysHoursMinutesSeconds(0, 75, 0, 0)));
	}
	
	/**
	 * Test rounding.
	 */ 
	function test_rounding_negs() {
		// 3 days, 2 hours, 23 minutes, and 12 seconds
		$duration = Duration::withDaysHoursMinutesSeconds(-3, -2, -23, -12);
		
		// To one day
		$result =$duration->roundTo(Duration::withDays(1));
		$this->assertTrue($result->isEqualTo(Duration::withDays(-3)));
		
		// To two days
		$result =$duration->roundTo(Duration::withDays(2));
		$this->assertTrue($result->isEqualTo(Duration::withDays(-4)));
		
		// 3 days + 2 hours = 74 hours
		// To one hour
		$result =$duration->roundTo(
			Duration::withDaysHoursMinutesSeconds(0, 1, 0, 0));
		$this->assertTrue(
			$result->isEqualTo(
				Duration::withDaysHoursMinutesSeconds(0, -74, 0, 0)));
		
		// To two hours
		$result =$duration->roundTo(
			Duration::withDaysHoursMinutesSeconds(0, 2, 0, 0));
		$this->assertTrue(
			$result->isEqualTo(
				Duration::withDaysHoursMinutesSeconds(0, -74, 0, 0)));
		
		// To three hours
		$result =$duration->roundTo(
			Duration::withDaysHoursMinutesSeconds(0, 3, 0, 0));
		$this->assertTrue(
			$result->isEqualTo(
				Duration::withDaysHoursMinutesSeconds(0, -75, 0, 0)));
		
		// To four hours
		$result =$duration->roundTo(
			Duration::withDaysHoursMinutesSeconds(0, 4, 0, 0));
		$this->assertTrue(
			$result->isEqualTo(
				Duration::withDaysHoursMinutesSeconds(0, -76, 0, 0)));
				
		// To five hours
		$result =$duration->roundTo(
			Duration::withDaysHoursMinutesSeconds(0, -5, 0, 0));
		$this->assertTrue(
			$result->isEqualTo(
				Duration::withDaysHoursMinutesSeconds(0, -75, 0, 0)));
	}
	
	/**
	 * Test truncating.
	 */ 
	function test_truncating_negs() {
		// 3 days, 2 hours, 23 minutes, and 12 seconds
		$duration = Duration::withDaysHoursMinutesSeconds(-3, -2, -23, -12);
		
		// To one day
		$result =$duration->truncateTo(Duration::withDays(1));
		$this->assertTrue($result->isEqualTo(Duration::withDays(-3)));
		
		// To two days
		$result =$duration->truncateTo(Duration::withDays(2));
		$this->assertTrue($result->isEqualTo(Duration::withDays(-2)));
		
		// 3 days + 2 hours = 74 hours
		// To one hour
		$result =$duration->truncateTo(
			Duration::withDaysHoursMinutesSeconds(0, 1, 0, 0));
		$this->assertTrue(
			$result->isEqualTo(
				Duration::withDaysHoursMinutesSeconds(0, -74, 0, 0)));
		
		// To two hours
		$result =$duration->truncateTo(
			Duration::withDaysHoursMinutesSeconds(0, 2, 0, 0));
		$this->assertTrue(
			$result->isEqualTo(
				Duration::withDaysHoursMinutesSeconds(0, -74, 0, 0)));
		
		// To three hours
		$result =$duration->truncateTo(
			Duration::withDaysHoursMinutesSeconds(0, -3, 0, 0));
		$this->assertTrue(
			$result->isEqualTo(
				Duration::withDaysHoursMinutesSeconds(-3, 0, 0, 0)));
		
		// To four hours
		$result =$duration->truncateTo(
			Duration::withDaysHoursMinutesSeconds(0, -4, 0, 0));
		$this->assertTrue(
			$result->isEqualTo(
				Duration::withDaysHoursMinutesSeconds(-3, 0, 0, 0)));
				
		// To five hours
		$result =$duration->truncateTo(
			Duration::withDaysHoursMinutesSeconds(0, -5, 0, 0));
		$this->assertTrue(
			$result->isEqualTo(
				Duration::withDaysHoursMinutesSeconds(-2, -22, 0, 0)));
	}
	
	/**
	 * Test truncating.
	 */ 
	function test_truncating() {
		// 3 days, 2 hours, 23 minutes, and 12 seconds
		$duration = Duration::withDaysHoursMinutesSeconds(3, 2, 23, 12);
		
		// To one day
		$result =$duration->truncateTo(Duration::withDays(1));
		$this->assertTrue($result->isEqualTo(Duration::withDays(3)));
		
		// To two days
		$result =$duration->truncateTo(Duration::withDays(2));
		$this->assertTrue($result->isEqualTo(Duration::withDays(2)));
		
		// 3 days + 2 hours = 74 hours
		// To one hour
		$result =$duration->truncateTo(
			Duration::withDaysHoursMinutesSeconds(0, 1, 0, 0));
		$this->assertTrue(
			$result->isEqualTo(
				Duration::withDaysHoursMinutesSeconds(0, 74, 0, 0)));
		
		// To two hours
		$result =$duration->truncateTo(
			Duration::withDaysHoursMinutesSeconds(0, 2, 0, 0));
		$this->assertTrue(
			$result->isEqualTo(
				Duration::withDaysHoursMinutesSeconds(0, 74, 0, 0)));
		
		// To three hours
		$result =$duration->truncateTo(
			Duration::withDaysHoursMinutesSeconds(0, 3, 0, 0));
		$this->assertTrue(
			$result->isEqualTo(
				Duration::withDaysHoursMinutesSeconds(3, 0, 0, 0)));
		
		// To four hours
		$result =$duration->truncateTo(
			Duration::withDaysHoursMinutesSeconds(0, 4, 0, 0));
		$this->assertTrue(
			$result->isEqualTo(
				Duration::withDaysHoursMinutesSeconds(3, 0, 0, 0)));
				
		// To five hours
		$result =$duration->truncateTo(
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
	 * The system this was developed on, 
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
	function test_big_durations() {
		// 50 years (18250 days)
		$duration = Duration::withDays(18250);
		$this->assertEqual($duration->days(), 18250);
  		$this->assertEqual($duration->hours(), 0);
 		$this->assertEqual($duration->minutes(), 0);
 		$this->assertEqual($duration->seconds(), 0);
 		$this->assertEqual($duration->asSeconds(), 86400*18250);
 		$this->assertEqual($duration->asSeconds(), 1576800000);
 		
		// 100 years (36500 days)
		$duration = Duration::withDays(36500);
		$this->assertEqual($duration->days(), 36500);
  		$this->assertEqual($duration->hours(), 0);
 		$this->assertEqual($duration->minutes(), 0);
 		$this->assertEqual($duration->seconds(), 0);
 		$this->assertEqual($duration->asSeconds(), 86400*36500);
 		$this->assertEqual($duration->asSeconds(), 3153600000);
 		
		// 500 years (182500 days)
		$duration = Duration::withDays(182500);
		$this->assertEqual($duration->days(), 182500);
  		$this->assertEqual($duration->hours(), 0);
 		$this->assertEqual($duration->minutes(), 0);
 		$this->assertEqual($duration->seconds(), 0);
 		$this->assertEqual($duration->asSeconds(), 86400*182500);
 		$this->assertEqual($duration->asSeconds(), 15768000000);
 		
 		// 5000 years (1825000 days)
		$duration = Duration::withDays(1825000);
		$this->assertEqual($duration->days(), 1825000);
 		$this->assertEqual($duration->hours(), 0);
 		$this->assertEqual($duration->minutes(), 0);
 		$this->assertEqual($duration->seconds(), 0);
 		$this->assertEqual($duration->asSeconds(), 86400*1825000);
 		$this->assertEqual($duration->asSeconds(), 157680000000);
 		
 		// 5000 years (1825000 days)
 		// minus 500 years (182500 days)
 		// should equal 4500 years (1642500 days)
		$duration = Duration::withDays(1825000);
		$result =$duration->minus(Duration::withDays(182500));
		
		$this->assertEqual($result->days(), 1642500);
 		$this->assertEqual($result->hours(), 0);
 		$this->assertEqual($result->minutes(), 0);
 		$this->assertEqual($duration->seconds(), 0);
 		$this->assertEqual($result->asSeconds(), 86400*1642500);
 		$this->assertEqual($result->asSeconds(), 141912000000);
 		
 		// 500,000,000 years (182500000000 days)
 		// minus 500 years (182500 days)
 		// should equal 499,999,500 years (182481750000 days)
		$duration = Duration::withDays(182500000000);
		$result =$duration->minus(Duration::withDays(182500));
		
		$this->assertEqual($result->days(), 182499817500);
 		$this->assertEqual($result->hours(), 0);
 		$this->assertEqual($result->minutes(), 0);
 		$this->assertEqual($duration->seconds(), 0);
 		$this->assertEqual($result->asSeconds(), 86400*182499817500);
 		$this->assertEqual($result->asSeconds(), hexdec('3804e5eaf8ea00'));
 		
 		// 50,000,000,000 years (18250000000000 days)
 		// minus 500 years (182500 days)
 		// should equal 49,999,999,500 years (18249999817500 days)
		$duration = Duration::withDays(18250000000000);
		$result =$duration->minus(Duration::withDays(182500));
		
		$this->assertEqual($result->days(), 18249999817500);
 		$this->assertEqual($result->hours(), 0);
 		$this->assertEqual($result->minutes(), 0);
 		$this->assertEqual($duration->seconds(), 0);
 		$this->assertEqual($result->asSeconds(), 86400*18249999817500);
 		$this->assertEqual($result->asSeconds(), hexdec('15e1eb3b3dfd6a00'));
 		
		// Beyond negative 4 billion years, the precision drops from
		// second precision to hour precision.
		
		// 4,000,000,000 years (1460000000000 days)
		$duration = Duration::withDaysHoursMinutesSeconds(1460000000000, 2, 23, 12);
		$this->assertEqual($duration->days(), 1460000000000);
		$this->assertEqual($duration->hours(), 2);
		$this->assertEqual($duration->minutes(), 23);
		$this->assertEqual($duration->seconds(), 12);
		
		// 50,000,000,000 years (18250000000000 days)
		$duration = Duration::withDaysHoursMinutesSeconds(18250000000000, 2, 23, 12);
		$this->assertEqual($duration->days(), 18250000000000);
		$this->assertEqual($duration->hours(), 2);
// 		$this->assertEqual($duration->minutes(), 23);
// 		$this->assertEqual($duration->seconds(), 12);

	}
}

// 		print "<pre>";
// 		print_r($duration);
// 		print "</pre>";

?>