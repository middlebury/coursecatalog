<?php
/** 
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ISO8601StringParserTestCase.class.php,v 1.5 2007/09/04 20:25:25 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 * @since 5/3/05
 */

require_once(dirname(__FILE__)."/../ISO8601StringParser.class.php");
require_once(dirname(__FILE__)."/../ISO8601TimeStringParser.class.php");

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
 * @version $Id: ISO8601StringParserTestCase.class.php,v 1.5 2007/09/04 20:25:25 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

class ISO8601StringParserTestCase extends UnitTestCase {
	
	/**
	*  Sets up unit test wide variables at the start
	*	 of each test method.
	*	 @access public
	*/
	function setUp() {
		
	}
	
	/**
	 *	  Clears the data set in the setUp() method call.
	 *	  @access public
	 */
	function tearDown() {
		// perhaps, unset $obj here
	}
	
	/**
	 * Test the creation methods.
	 */ 
	function test_full_format() {
		$parser = new ISO8601StringParser(
			'2005-05-23T15:25:10-04:00');
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 2005);
		$this->assertEqual($parser->month(), 5);
		$this->assertEqual($parser->day(), 23);
		$this->assertEqual($parser->hour(), 15);
		$this->assertEqual($parser->minute(), 25);
		$this->assertEqual($parser->second(), 10);
		$this->assertEqual($parser->offsetHour(), -4);
		$this->assertEqual($parser->offsetMinute(), 0);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new ISO8601StringParser(
			'2005-05-03 15:25:10-04:30');
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 2005);
		$this->assertEqual($parser->month(), 5);
		$this->assertEqual($parser->day(), 3);
		$this->assertEqual($parser->hour(), 15);
		$this->assertEqual($parser->minute(), 25);
		$this->assertEqual($parser->second(), 10);
		$this->assertEqual($parser->offsetHour(), -4);
		$this->assertEqual($parser->offsetMinute(), -30);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new ISO8601StringParser(
			'20050523152510-0400');
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 2005);
		$this->assertEqual($parser->month(), 5);
		$this->assertEqual($parser->day(), 23);
		$this->assertEqual($parser->hour(), 15);
		$this->assertEqual($parser->minute(), 25);
		$this->assertEqual($parser->second(), 10);
		$this->assertEqual($parser->offsetHour(), -4);
		$this->assertEqual($parser->offsetMinute(), 0);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new ISO8601StringParser(
			'20050523152510');
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 2005);
		$this->assertEqual($parser->month(), 5);
		$this->assertEqual($parser->day(), 23);
		$this->assertEqual($parser->hour(), 15);
		$this->assertEqual($parser->minute(), 25);
		$this->assertEqual($parser->second(), 10);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new ISO8601StringParser(
			'2005-05-03 15:25:10Z');
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 2005);
		$this->assertEqual($parser->month(), 5);
		$this->assertEqual($parser->day(), 3);
		$this->assertEqual($parser->hour(), 15);
		$this->assertEqual($parser->minute(), 25);
		$this->assertEqual($parser->second(), 10);
		$this->assertEqual($parser->offsetHour(), 0);
		$this->assertEqual($parser->offsetMinute(), 0);
		$this->assertEqual($parser->offsetSecond(), 0);
		
		$parser = new ISO8601StringParser(
			'2006-11-12 18:00:00');
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 2006);
		$this->assertEqual($parser->month(), 11);
		$this->assertEqual($parser->day(), 12);
		$this->assertEqual($parser->hour(), 18);
		$this->assertEqual($parser->minute(), 0);
		$this->assertEqual($parser->second(), 0);
		$this->assertEqual($parser->offsetHour(), 0);
		$this->assertEqual($parser->offsetMinute(), 0);
		$this->assertEqual($parser->offsetSecond(), 0);
		
		$parser = new ISO8601StringParser(
			'2005-05-03');
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 2005);
		$this->assertEqual($parser->month(), 5);
		$this->assertEqual($parser->day(), 3);
		$this->assertEqual($parser->hour(), NULL);
		$this->assertEqual($parser->minute(), NULL);
		$this->assertEqual($parser->second(), NULL);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new ISO8601StringParser(
			'20050503');
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 2005);
		$this->assertEqual($parser->month(), 5);
		$this->assertEqual($parser->day(), 3);
		$this->assertEqual($parser->hour(), NULL);
		$this->assertEqual($parser->minute(), NULL);
		$this->assertEqual($parser->second(), NULL);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new ISO8601StringParser(
			'2005-05');
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 2005);
		$this->assertEqual($parser->month(), 5);
		$this->assertEqual($parser->day(), NULL);
		$this->assertEqual($parser->hour(), NULL);
		$this->assertEqual($parser->minute(), NULL);
		$this->assertEqual($parser->second(), NULL);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new ISO8601StringParser(
			'200505');
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 2005);
		$this->assertEqual($parser->month(), 5);
		$this->assertEqual($parser->day(), NULL);
		$this->assertEqual($parser->hour(), NULL);
		$this->assertEqual($parser->minute(), NULL);
		$this->assertEqual($parser->second(), NULL);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new ISO8601StringParser(
			'2005');
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 2005);
		$this->assertEqual($parser->month(), NULL);
		$this->assertEqual($parser->day(), NULL);
		$this->assertEqual($parser->hour(), NULL);
		$this->assertEqual($parser->minute(), NULL);
		$this->assertEqual($parser->second(), NULL);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new ISO8601TimeStringParser(
			'15:25:10Z');
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), NULL);
		$this->assertEqual($parser->month(), NULL);
		$this->assertEqual($parser->day(), NULL);
		$this->assertEqual($parser->hour(), 15);
		$this->assertEqual($parser->minute(), 25);
		$this->assertEqual($parser->second(), 10);
		$this->assertEqual($parser->offsetHour(), 0);
		$this->assertEqual($parser->offsetMinute(), 0);
		$this->assertEqual($parser->offsetSecond(), 0);
		
		$parser = new ISO8601TimeStringParser(
			'15:25:10');
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), NULL);
		$this->assertEqual($parser->month(), NULL);
		$this->assertEqual($parser->day(), NULL);
		$this->assertEqual($parser->hour(), 15);
		$this->assertEqual($parser->minute(), 25);
		$this->assertEqual($parser->second(), 10);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new ISO8601TimeStringParser(
			'T152510');
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), NULL);
		$this->assertEqual($parser->month(), NULL);
		$this->assertEqual($parser->day(), NULL);
		$this->assertEqual($parser->hour(), 15);
		$this->assertEqual($parser->minute(), 25);
		$this->assertEqual($parser->second(), 10);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new ISO8601TimeStringParser(
			'T152510.375');
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), NULL);
		$this->assertEqual($parser->month(), NULL);
		$this->assertEqual($parser->day(), NULL);
		$this->assertEqual($parser->hour(), 15);
		$this->assertEqual($parser->minute(), 25);
		$this->assertEqual($parser->second(), 10.375);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
	}
	
	function test_bad_forms() {
		$parser = new ISO8601StringParser(
			'April');
		$this->assertFalse($parser->canHandle());
		
		$parser = new ISO8601StringParser(
			'5-4-2000');
		$this->assertFalse($parser->canHandle());
		
		$parser = new ISO8601StringParser(
			'1234567890');
		$this->assertFalse($parser->canHandle());
	
	}
}
?>