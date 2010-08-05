<?php
/**
 * @since 5/23/05
 * @package harmoni.primitives.chronology.string_parsers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: StringParser.class.php,v 1.8 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

require_once(dirname(__FILE__)."/../../Objects/SObject.class.php");
require_once(dirname(__FILE__)."/../Month.class.php");


/**
 * StringParser and its decendent classes form a Strategy pattern. They classes 
 * that each implement a differnt method (strategy) for parsing strings into
 * dates and times.
 *
 * To try to parse a string using all (general) StringParsers use the 
 * {@link getParserFor getParserFor($aString)} method to iterate through the
 * parsers until one is found that can handle the input:
 * <code>
 * 	$parser = StringParser::getParserFor($aString);
 *		
 *	if (!$parser)
 *		die("'".$aString."' is not in a valid format.");
 *	
 * 	$result = Date::withYearMonthDay($parser->year(), $parser->month(), $parser->day());
 * </code>
 *
 * To use StringParsers individually, use the canHandle($aString) method to find out if it is
 * appropriate to use this parse for a given string. If it is appropriate, create
 * a new StringParser with the given string and access its elements for the results:
 * <code>
 * 	$parser = new ANSI58216StringParser($aString);
 *		
 *	if (!$parser)
 *		die("'".$aString."' is not in a valid format.");
 *	
 * 	$result = Duration::withDaysHoursMinutesSeconds($parser->day(), $parser->hour(),
 *					$parser->minute(), $parser->second());
 * </code>
 *
 * To create new StringParsers, implement the canHandle() and parse() methods.
 * 
 * @since 5/23/05
 * @package harmoni.primitives.chronology.string_parsers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: StringParser.class.php,v 1.8 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
abstract class StringParser 
	extends SObject {

/*********************************************************
 * Class Methods
 *********************************************************/

	/**
	 * Answer the parser that was able to successfully parse the input string
	 * or FALSE if none could handle the input string.
	 * 
	 * @param string $aString
	 * @return mixed object StringParser OR FALSE
	 * @access public
	 * @since 5/24/05
	 * @static
	 */
	static function getParserFor ( $aString ) {
		// Go through our parsers and try to find one that understands the format.
		$parserClasses = array(	
			'ISO8601StringParser',
			'ISO8601TimeStringParser',
			'DayMonthNameYearStringParser',
			'MonthNameDayYearStringParser',
			'MonthNumberDayYearStringParser',
			'KeywordStringParser',
			'TimeStringParser',
			'DateAndTimeStringParser',
			'DateAndISOTimeStringParser'
		);
		
		$handled = FALSE;
		while (!$handled && current($parserClasses)) {
			$parserClass = current($parserClasses);
			$parser = new $parserClass($aString);
			
			if ($parser->canHandle()) {
				$handled = TRUE;
				break;
			} else {
				next($parserClasses);
			}
		}
		
		if ($handled && is_object($parser))
			return $parser;
		else {
			$false =FALSE;
			return $false;
		}
	}

/*********************************************************
 * Instance Variables
 *********************************************************/
 	
 	/**
 	 * @var string $input; The input string 
 	 * @access private
 	 * @since 5/23/05
 	 */
 	var $input;
	
	/**
	 * @var integer $year; The year found in the input 
	 * @access private
	 * @since 5/23/05
	 */
	var $year = NULL;
	
	/**
	 * @var integer $month; The month found in the input 
	 * @access private
	 * @since 5/23/05
	 */
	var $month = NULL;
	
	/**
	 * @var integer $day; The day found in the input 
	 * @access private
	 * @since 5/23/05
	 */
	var $day = NULL;
	
	/**
	 * @var integer $hour; The hour found in the input  
	 * @access private
	 * @since 5/23/05
	 */
	var $hour = NULL;
	
	/**
	 * @var integer $minute; The minute found in the input 
	 * @access private
	 * @since 5/23/05
	 */
	var $minute = NULL;
	
	/**
	 * @var integer $second; The second found in the input 
	 * @access private
	 * @since 5/23/05
	 */
	var $second = NULL;
	
	/**
	 * @var integer $offsetHour; The hour offset from UTC found in the input
	 * @access private
	 * @since 5/23/05
	 */
	var $offsetHour = NULL;
	
	/**
	 * @var integer $offsetMinute; The minute offset from UTC found in the input
	 * @access private
	 * @since 5/23/05
	 */
	var $offsetMinute = NULL;
	
	/**
	 * @var integer $offsetSecond; The second offset from UTC found in the input
	 * @access private
	 * @since 5/23/05
	 */
	var $offsetSecond = NULL;
	
	
/*********************************************************
 * Instance Methods
 *********************************************************/

	/**
	 * Create a new parser with the given input string.
	 *
	 * @param string $aString
	 * @return null
	 * @access public
	 * @since 5/23/05
	 */
	function __construct ( $aString ) {
		ArgumentValidator::validate($aString, StringValidatorRule::getRule());
		$this->input = $aString;
		
		if ($this->canHandle())
			$this->parse();
	}
	
	/**
	 * Answer True if this parser can handle the format of the string passed.
	 * 
	 * @return boolean
	 * @access public
	 * @since 5/23/05
	 */
	abstract function canHandle ();
	
	/**
	 * Parse the input string and set our elements based on the contents of the
	 * input string. Elements not found in the string will be null.
	 * 
	 * @return void
	 * @access private
	 * @since 5/23/05
	 */
	abstract function parse ();

/*********************************************************
 * Instance Methods - Accessing
 *********************************************************/
	
	/**
	 * Answer the year or NULL.
	 *
	 * @return integer
	 * @access public
	 * @since 5/23/05
	 */
	function year () {
		return $this->year;
	}
	
	/**
	 * Answer the month or NULL.
	 *
	 * @return integer
	 * @access public
	 * @since 5/23/05
	 */
	function month () {
		return $this->month;
	}
	
	/**
	 * Answer the day or NULL.
	 *
	 * @return integer
	 * @access public
	 * @since 5/23/05
	 */
	function day () {
		return $this->day;
	}
	
	/**
	 * Answer the hour or NULL.
	 *
	 * @return integer
	 * @access public
	 * @since 5/23/05
	 */
	function hour () {
		return $this->hour;
	}
	
	/**
	 * Answer the minute or NULL.
	 *
	 * @return integer
	 * @access public
	 * @since 5/23/05
	 */
	function minute () {
		return $this->minute;
	}
	
	/**
	 * Answer the second or NULL.
	 *
	 * @return integer
	 * @access public
	 * @since 5/23/05
	 */
	function second () {
		return $this->second;
	}
	
	/**
	 * Answer the hour offset from UTC or NULL.
	 *
	 * @return integer
	 * @access public
	 * @since 5/23/05
	 */
	function offsetHour () {
		return $this->offsetHour;
	}
	
	/**
	 * Answer the minute offset from UTC or NULL.
	 *
	 * @return integer
	 * @access public
	 * @since 5/23/05
	 */
	function offsetMinute () {
		return $this->offsetMinute;
	}
	
	/**
	 * Answer the second offset from UTC or NULL.
	 *
	 * @return integer
	 * @access public
	 * @since 5/23/05
	 */
	function offsetSecond () {
		return $this->offsetSecond;
	}
	
/*********************************************************
 * Instance Methods - Setting (private
 *********************************************************/
	
	/**
	 * Set the year
	 * 
	 * @param integer $anInteger
	 * @return void
	 * @access private
	 * @since 5/23/05
	 */
	function setYear ( $anInteger ) {
		$this->year = intval($anInteger);
	}
	
	/**
	 * Set the month
	 * 
	 * @param mixed $anIntOrString
	 * @return void
	 * @access private
	 * @since 5/23/05
	 */
	function setMonth ( $anIntOrString ) {
		if (!$anIntOrString) {
			$this->month = NULL;
		} else if (is_numeric($anIntOrString)) {
			$this->month = intval($anIntOrString);
		} else {
			$this->month = Month::indexOfMonth($anIntOrString);
		}
	}
	
	/**
	 * Set the day
	 * 
	 * @param integer $anInteger
	 * @return void
	 * @access private
	 * @since 5/23/05
	 */
	function setDay ( $anInteger ) {
		$this->day = intval($anInteger);
	}
	
	/**
	 * Set the hour
	 * 
	 * @param integer $anInteger
	 * @return void
	 * @access private
	 * @since 5/23/05
	 */
	function setHour ( $anInteger ) {
		$this->hour = intval($anInteger);
	}
	
	/**
	 * Set the minute
	 * 
	 * @param integer $anInteger
	 * @return void
	 * @access private
	 * @since 5/23/05
	 */
	function setMinute ( $anInteger ) {
		$this->minute = intval($anInteger);
	}
	
	/**
	 * Set the second
	 * 
	 * @param float $aFloat
	 * @return void
	 * @access private
	 * @since 5/23/05
	 */
	function setSecond ( $anInteger ) {
		$this->second = $anInteger;
	}
	
	/**
	 * Set the hour offset from UTC
	 * 
	 * @param integer $anInteger
	 * @return void
	 * @access private
	 * @since 5/23/05
	 */
	function setOffsetHour ( $anInteger ) {
		$this->offsetHour = intval($anInteger);
	}
	
	/**
	 * Set the minute offset from UTC
	 * 
	 * @param integer $anInteger
	 * @return void
	 * @access private
	 * @since 5/23/05
	 */
	function setOffsetMinute ( $anInteger ) {
		$this->offsetMinute = intval($anInteger);
	}
	
	/**
	 * Set the second offset from UTC
	 * 
	 * @param integer $anInteger
	 * @return void
	 * @access private
	 * @since 5/23/05
	 */
	function setOffsetSecond ( $anInteger ) {
		$this->offsetSecond = $anInteger;
	}
}

require_once(dirname(__FILE__)."/RegexStringParser.class.php");
require_once(dirname(__FILE__)."/TwoDigitYearStringParser.class.php");

require_once(dirname(__FILE__)."/ANSI58216StringParser.class.php");
require_once(dirname(__FILE__)."/ISO8601StringParser.class.php");
require_once(dirname(__FILE__)."/ISO8601TimeStringParser.class.php");
require_once(dirname(__FILE__)."/DayMonthNameYearStringParser.class.php");
require_once(dirname(__FILE__)."/MonthNameDayYearStringParser.class.php");
require_once(dirname(__FILE__)."/MonthNumberDayYearStringParser.class.php");
require_once(dirname(__FILE__)."/KeywordStringParser.class.php");
require_once(dirname(__FILE__)."/TimeStringParser.class.php");
require_once(dirname(__FILE__)."/DateAndTimeStringParser.class.php");
require_once(dirname(__FILE__)."/DateAndISOTimeStringParser.class.php");

?>