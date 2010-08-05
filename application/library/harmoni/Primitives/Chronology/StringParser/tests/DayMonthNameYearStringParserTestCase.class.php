<?php
/** 
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DayMonthNameYearStringParserTestCase.class.php,v 1.3 2007/09/04 20:25:25 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 * @since 5/3/05
 */

require_once(dirname(__FILE__)."/../DayMonthNameYearStringParser.class.php");

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
 * @version $Id: DayMonthNameYearStringParserTestCase.class.php,v 1.3 2007/09/04 20:25:25 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

class DayMonthNameYearStringParserTestCase extends UnitTestCase {
	
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
		$parser = new DayMonthNameYearStringParser(
			'23 May 2005');
		
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 2005);
		$this->assertEqual($parser->month(), 5);
		$this->assertEqual($parser->day(), 23);
		$this->assertEqual($parser->hour(), NULL);
		$this->assertEqual($parser->minute(), NULL);
		$this->assertEqual($parser->second(), NULL);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new DayMonthNameYearStringParser(
			"5 April '82");
		
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 1982);
		$this->assertEqual($parser->month(), 4);
		$this->assertEqual($parser->day(), 5);
		$this->assertEqual($parser->hour(), NULL);
		$this->assertEqual($parser->minute(), NULL);
		$this->assertEqual($parser->second(), NULL);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new DayMonthNameYearStringParser(
			'5APR82');
		
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 1982);
		$this->assertEqual($parser->month(), 4);
		$this->assertEqual($parser->day(), 5);
		$this->assertEqual($parser->hour(), NULL);
		$this->assertEqual($parser->minute(), NULL);
		$this->assertEqual($parser->second(), NULL);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new DayMonthNameYearStringParser(
			'5-APR-82');
		
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 1982);
		$this->assertEqual($parser->month(), 4);
		$this->assertEqual($parser->day(), 5);
		$this->assertEqual($parser->hour(), NULL);
		$this->assertEqual($parser->minute(), NULL);
		$this->assertEqual($parser->second(), NULL);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new DayMonthNameYearStringParser(
			'5APRIL1982');
		
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 1982);
		$this->assertEqual($parser->month(), 4);
		$this->assertEqual($parser->day(), 5);
		$this->assertEqual($parser->hour(), NULL);
		$this->assertEqual($parser->minute(), NULL);
		$this->assertEqual($parser->second(), NULL);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new DayMonthNameYearStringParser(
			'April 1982');
			
		$this->assertTrue($parser->canHandle());
		$this->assertEqual($parser->year(), 1982);
		$this->assertEqual($parser->month(), 4);
		$this->assertEqual($parser->day(), NULL);
		$this->assertEqual($parser->hour(), NULL);
		$this->assertEqual($parser->minute(), NULL);
		$this->assertEqual($parser->second(), NULL);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
	}
	
	function test_bad_forms() {
		$parser = new DayMonthNameYearStringParser(
			'April');
		$this->assertFalse($parser->canHandle());
		
		$parser = new DayMonthNameYearStringParser(
			'5-4-2000');
		$this->assertFalse($parser->canHandle());
	
	}
}
?>