<?php
/** 
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: MonthNameDayYearStringParserTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 * @since 5/3/05
 */

require_once(dirname(__FILE__)."/../MonthNameDayYearStringParser.class.php");

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
 * @version $Id: MonthNameDayYearStringParserTestCase.class.php,v 1.4 2007/09/04 20:25:26 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

class MonthNameDayYearStringParserTestCase extends UnitTestCase {
	
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
		$parser = new MonthNameDayYearStringParser(
			'May 23 2005');
		
		$this->assertEqual($parser->year(), 2005);
		$this->assertEqual($parser->month(), 5);
		$this->assertEqual($parser->day(), 23);
		$this->assertEqual($parser->hour(), NULL);
		$this->assertEqual($parser->minute(), NULL);
		$this->assertEqual($parser->second(), NULL);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new MonthNameDayYearStringParser(
			"April 5'82");
		
		$this->assertEqual($parser->year(), 1982);
		$this->assertEqual($parser->month(), 4);
		$this->assertEqual($parser->day(), 5);
		$this->assertEqual($parser->hour(), NULL);
		$this->assertEqual($parser->minute(), NULL);
		$this->assertEqual($parser->second(), NULL);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new MonthNameDayYearStringParser(
			'APR-5-82');
		
		$this->assertEqual($parser->year(), 1982);
		$this->assertEqual($parser->month(), 4);
		$this->assertEqual($parser->day(), 5);
		$this->assertEqual($parser->hour(), NULL);
		$this->assertEqual($parser->minute(), NULL);
		$this->assertEqual($parser->second(), NULL);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new MonthNameDayYearStringParser(
			'April 5, 82');
		
		$this->assertEqual($parser->year(), 1982);
		$this->assertEqual($parser->month(), 4);
		$this->assertEqual($parser->day(), 5);
		$this->assertEqual($parser->hour(), NULL);
		$this->assertEqual($parser->minute(), NULL);
		$this->assertEqual($parser->second(), NULL);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
		
		$parser = new MonthNameDayYearStringParser(
			'August 10th, 2006');
		
		$this->assertEqual($parser->year(), 2006);
		$this->assertEqual($parser->month(), 8);
		$this->assertEqual($parser->day(), 10);
		$this->assertEqual($parser->hour(), NULL);
		$this->assertEqual($parser->minute(), NULL);
		$this->assertEqual($parser->second(), NULL);
		$this->assertEqual($parser->offsetHour(), NULL);
		$this->assertEqual($parser->offsetMinute(), NULL);
		$this->assertEqual($parser->offsetSecond(), NULL);
	}
	
}
?>