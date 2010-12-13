<?php
/** 
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DateAndTimeStringParserTestCase.class.php,v 1.3 2007/09/04 20:25:25 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 * @since 5/3/05
 */

require_once(dirname(__FILE__)."/../DateAndTimeStringParser.class.php");

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
 * @version $Id: DateAndTimeStringParserTestCase.class.php,v 1.3 2007/09/04 20:25:25 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

class DateAndTimeStringParserTestCase extends UnitTestCase {
	
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
		$parser = new DateAndTimeStringParser(
			'2005-08-20 15:25:10');
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 2005);
		$this->assertEqual($parser->month(), 8);
		$this->assertEqual($parser->day(), 20);
		$this->assertEqual($parser->hour(), 15);
		$this->assertEqual($parser->minute(), 25);
		$this->assertEqual($parser->second(), 10);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new DateAndTimeStringParser(
			'2005-08-20 3:25:10 pm');
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 2005);
		$this->assertEqual($parser->month(), 8);
		$this->assertEqual($parser->day(), 20);
		$this->assertEqual($parser->hour(), 15);
		$this->assertEqual($parser->minute(), 25);
		$this->assertEqual($parser->second(), 10);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new DateAndTimeStringParser(
			'08/20/2005 3:25:10 pm');
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 2005);
		$this->assertEqual($parser->month(), 8);
		$this->assertEqual($parser->day(), 20);
		$this->assertEqual($parser->hour(), 15);
		$this->assertEqual($parser->minute(), 25);
		$this->assertEqual($parser->second(), 10);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new DateAndTimeStringParser(
			'August 20, 2005 3:25:10 pm');
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 2005);
		$this->assertEqual($parser->month(), 8);
		$this->assertEqual($parser->day(), 20);
		$this->assertEqual($parser->hour(), 15);
		$this->assertEqual($parser->minute(), 25);
		$this->assertEqual($parser->second(), 10);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
	}
	
	function test_bad_forms() {
		$parser = new DateAndTimeStringParser(
			'April');
		$this->assertFalse($parser->canHandle());
		
		$parser = new DateAndTimeStringParser(
			'5-4-2000');
		$this->assertFalse($parser->canHandle());
		
		$parser = new DateAndTimeStringParser(
			'1234567890');
		$this->assertFalse($parser->canHandle());
	
	}
}
?>