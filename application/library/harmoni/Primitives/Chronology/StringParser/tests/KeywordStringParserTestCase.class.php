<?php
/** 
 * @package harmoni.primitives.chronology.tests
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: KeywordStringParserTestCase.class.php,v 1.3 2007/09/04 20:25:25 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 * @since 5/3/05
 */

require_once(dirname(__FILE__)."/../KeywordStringParser.class.php");
require_once(dirname(__FILE__)."/../../Date.class.php");

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
 * @version $Id: KeywordStringParserTestCase.class.php,v 1.3 2007/09/04 20:25:25 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

class KeywordStringParserTestCase extends UnitTestCase {
	
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
	 * Test the methods.
	 */ 
	function test_now() {
		$parser = new KeywordStringParser('now');
		$this->assertTrue($parser->canHandle());
		
		$dateAndTime = DateAndTime::now();
		$offset =$dateAndTime->offset();
		
		$this->assertEqual($parser->year(), $dateAndTime->year());
		$this->assertEqual($parser->month(), $dateAndTime->month());
		$this->assertEqual($parser->day(), $dateAndTime->dayOfMonth());
		$this->assertEqual($parser->hour(), $dateAndTime->hour());
		$this->assertEqual($parser->minute(), $dateAndTime->minute());
		$this->assertEqual($parser->second(), $dateAndTime->second());
		$this->assertEqual($parser->offsetHour(), $offset->hours());
		$this->assertEqual($parser->offsetMinute(), $offset->minutes());
		$this->assertEqual($parser->offsetSecond(), $offset->seconds());
	}
	
	/**
	 * Test the methods.
	 */ 
	function test_today() {
		$parser = new KeywordStringParser('today');
		$this->assertTrue($parser->canHandle());
		
		$date = Date::today();
		$dateAndTime =$date->start();
		$offset =$dateAndTime->offset();
		
		$this->assertEqual($parser->year(), $dateAndTime->year());
		$this->assertEqual($parser->month(), $dateAndTime->month());
		$this->assertEqual($parser->day(), $dateAndTime->dayOfMonth());
		$this->assertEqual($parser->hour(), 0);
		$this->assertEqual($parser->minute(), 0);
		$this->assertEqual($parser->second(), 0);
		$this->assertEqual($parser->offsetHour(), $offset->hours());
		$this->assertEqual($parser->offsetMinute(), $offset->minutes());
		$this->assertEqual($parser->offsetSecond(), $offset->seconds());
	}
	
	/**
	 * Test the methods.
	 */ 
	function test_tomorrow() {
		$parser = new KeywordStringParser('tomorrow');
		$this->assertTrue($parser->canHandle());
		
		$date = Date::tomorrow();
		$dateAndTime =$date->start();
		$offset =$dateAndTime->offset();
		
		$this->assertEqual($parser->year(), $dateAndTime->year());
		$this->assertEqual($parser->month(), $dateAndTime->month());
		$this->assertEqual($parser->day(), $dateAndTime->dayOfMonth());
		$this->assertEqual($parser->hour(), 0);
		$this->assertEqual($parser->minute(), 0);
		$this->assertEqual($parser->second(), 0);
		$this->assertEqual($parser->offsetHour(), $offset->hours());
		$this->assertEqual($parser->offsetMinute(), $offset->minutes());
		$this->assertEqual($parser->offsetSecond(), $offset->seconds());
	}
	
	/**
	 * Test the methods.
	 */ 
	function test_yesterday() {
		$parser = new KeywordStringParser('yesterday');
		$this->assertTrue($parser->canHandle());
		
		$date = Date::yesterday();
		$dateAndTime =$date->start();
		$offset =$dateAndTime->offset();
		
		$this->assertEqual($parser->year(), $dateAndTime->year());
		$this->assertEqual($parser->month(), $dateAndTime->month());
		$this->assertEqual($parser->day(), $dateAndTime->dayOfMonth());
		$this->assertEqual($parser->hour(), 0);
		$this->assertEqual($parser->minute(), 0);
		$this->assertEqual($parser->second(), 0);
		$this->assertEqual($parser->offsetHour(), $offset->hours());
		$this->assertEqual($parser->offsetMinute(), $offset->minutes());
		$this->assertEqual($parser->offsetSecond(), $offset->seconds());
	}
}
?>