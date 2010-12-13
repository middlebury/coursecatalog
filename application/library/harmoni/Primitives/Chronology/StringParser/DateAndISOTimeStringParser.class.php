<?php
/**
 * @since 5/24/05
 * @package harmoni.primitives.chronology.string_parsers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DateAndTimeStringParser.class.php,v 1.3 2007/09/04 20:25:25 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */ 
 
require_once(dirname(__FILE__)."/DateAndTimeStringParser.class.php");


/**
 * DateAndTimeStringParser breaks up strings into a date component and a time
 * component and attempts to match each individually.
 * The string must contain a valid time component at the end in order to be parsed.
 * 
 * @since 5/24/05
 * @package harmoni.primitives.chronology.string_parsers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DateAndTimeStringParser.class.php,v 1.3 2007/09/04 20:25:25 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class DateAndISOTimeStringParser
	extends DateAndTimeStringParser
{

/*********************************************************
 * Static Methods
 *********************************************************/
	
	/**
	 * Return the regular expression used by this parser.
	 * If the input has a time component, then this parser can handle it.
	 * 
	 * @return string
	 * @access protected
	 * @since 5/24/05
	 * @static
	 */
	public static function getRegex () {
		// Remove a line-beginning anchor from the time expression
		return preg_replace('/\/[\s\r]*\^/', '/', ISO8601TimeStringParser::getRegex());
	}
	
	/*********************************************************
 * Instance Methods
 *********************************************************/
 	
	/**
	 * Parse the input string and set our elements based on the contents of the
	 * input string. Elements not found in the string will be null.
	 * 
	 * @return void
	 * @access private
	 * @since 5/24/05
	 */
	function parse () {
		preg_match(self::getRegex(), $this->input, $timeMatches);
		$timeComponent = $timeMatches[0];
		
		// The date is anything before the time
		$dateComponent = trim(str_replace($timeComponent, '', $this->input));
		
		$timeParser = new ISO8601TimeStringParser($timeComponent);
		$dateParser = StringParser::getParserFor($dateComponent);
		
		// Merge the two results into our fields
		if ($dateParser) {
			$this->setYear($dateParser->year());
			$this->setMonth($dateParser->month());
			$this->setDay($dateParser->day());
		}
		
		$this->setHour($timeParser->hour());
		$this->setMinute($timeParser->minute());
		$this->setSecond($timeParser->second());
		
		if (!is_null($timeParser->offsetHour()))
	 		$this->setOffsetHour($timeParser->offsetHour());
	 	if (!is_null($timeParser->offsetMinute()))
	 		$this->setOffsetMinute($timeParser->offsetMinute());
	 	if (!is_null($timeParser->offsetSecond()))
 			$this->setOffsetSecond($timeParser->offsetSecond());
	}
}

?>