<?php
/**
 * @since 5/24/05
 * @package harmoni.primitives.chronology.string_parsers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: KeywordStringParser.class.php,v 1.3 2007/09/04 20:25:25 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */ 
 
require_once(dirname(__FILE__)."/StringParser.class.php");


/**
 * KeywordStringParser matches keywords to common times.
 *
 * Valid keywords:
 * <br/>
 * <br/>Now
 *	- now
 * Today
 *	- today
 *	- current
 * Tomorrow
 *	- tomorrow
 * Yesterday
 *	- yesterday
 * 
 * @since 5/24/05
 * @package harmoni.primitives.chronology.string_parsers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: KeywordStringParser.class.php,v 1.3 2007/09/04 20:25:25 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class KeywordStringParser
	extends StringParser
{

/*********************************************************
 * Instance Methods
 *********************************************************/
	
	/**
	 * Answer True if this parser can handle the format of the string passed.
	 * 
	 * @return boolean
	 * @access public
	 * @since 5/24/05
	 */
	function canHandle () {
		$term = trim(strtolower($this->input));
		return (
				in_array($term, $this->nowArray())
			||	in_array($term, $this->todayArray())
			||	in_array($term, $this->tomorrowArray())
			||	in_array($term, $this->yesterdayArray())
		);
		
	}
	
	/**
	 * Parse the input string and set our elements based on the contents of the
	 * input string. Elements not found in the string will be null.
	 * 
	 * @return void
	 * @access private
	 * @since 5/24/05
	 */
	function parse () {
		$term = trim(strtolower($this->input));
		
		if (in_array($term, $this->nowArray()))
			$timespan = Timespan::current();
		else if (in_array($term, $this->todayArray()))
			$timespan = Date::today();
		else if (in_array($term, $this->tomorrowArray()))
			$timespan = Date::tomorrow();
		else if (in_array($term, $this->yesterdayArray()))
			$timespan = Date::yesterday();
		
		$dateAndTime =$timespan->start();
		$offset =$dateAndTime->offset();

		$this->setYear($dateAndTime->year());
		$this->setMonth($dateAndTime->month());
		$this->setDay($dateAndTime->dayOfMonth());
		$this->setHour($dateAndTime->hour());
		$this->setMinute($dateAndTime->minute());
		$this->setSecond($dateAndTime->second());
		$this->setOffsetHour($offset->hours());
		$this->setOffsetMinute($offset->minutes());
		$this->setOffsetSecond($offset->seconds());
	}

/*********************************************************
 * Instance Methods - Accessing
 *********************************************************/
	
	/**
	 * Answer the array of keywords that refer to now.
	 * 
	 * @return array
	 * @access protected
	 * @since 5/24/05
	 */
	function nowArray () {
		return array(
			'now'
		);
	}
	
	/**
	 * Answer the array of keywords that refer to tomorrow.
	 * 
	 * @return array
	 * @access protected
	 * @since 5/24/05
	 */
	function todayArray () {
		return array(
			'today',
			'current'
		);
	}
	
	/**
	 * Answer the array of keywords that refer to tomorrow.
	 * 
	 * @return array
	 * @access protected
	 * @since 5/24/05
	 */
	function tomorrowArray () {
		return array(
			'tomorrow'
		);
	}
	
	/**
	 * Answer the array of keywords that refer to yesterday.
	 * 
	 * @return array
	 * @access protected
	 * @since 5/24/05
	 */
	function yesterdayArray () {
		return array(
			'yesterday'
		);
	}
}

?>