<?php
/**
 * @since 5/23/05
 * @package harmoni.primitives.chronology.string_parsers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ISO8601TimeStringParser.class.php,v 1.3 2006/06/26 12:55:08 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */ 
 
require_once(dirname(__FILE__)."/StringParser.class.php");
//require_once(dirname(__FILE__)."/RegexStringParser.class.php");

/**
 * This StringParser can handle ISO 8601 dates. {@link http://www.cl.cam.ac.uk/~mgk25/iso-time.html}
 * Examples:
 * 		- 4/5/82
 * 		- 04/05/82
 *		- 04/05/1982
 *		- 4-5-82
 * 
 * @since 5/23/05
 * @package harmoni.primitives.chronology.string_parsers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ISO8601TimeStringParser.class.php,v 1.3 2006/06/26 12:55:08 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class ISO8601TimeStringParser 
	extends RegexStringParser {
	
/*********************************************************
 * Static Methods
 *********************************************************/
 	
 	/**
	 * Return the regular expression used by this parser
	 * 
	 * @return string
	 * @access protected
	 * @since 5/24/05
	 * @static
	 */
	public static function getRegex () {
		return
"/
^										# Start of the line

	[\sT]?								# Optional delimiter

#-----------------------------------------------------------------------------		
										# The time component

	(									# Two-digit hour
		(?:  [0-1][0-9])
		|
		(?: 2[0-4])
	)
	
	:?									# Optional Colon
	
	([0-5][0-9])?						# Two-digit minute
	
	:?									# Optional Colon
	
	(									# Two-digit second 
		[0-5][0-9]
		(?: \.[0-9]+)?						# followed by an optional decimal.
	)?

#-----------------------------------------------------------------------------
	(									# Offset component
	
		Z								# Zero offset (UTC)
		|								# OR
		(?:								# Offset from UTC
			([+\-])						# Sign of the offset
		
			(							# Two-digit offset hour
				(?:  [0-1][0-9])
				|
				(?:  2[0-4])
			)			

			:?							# Optional Colon
			
			([0-5][0-9])?				# Two-digit offset minute
		)
	)?

$
/x";
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
	 * @since 5/23/05
	 */
	function parse () {
		preg_match(self::getRegex(), $this->input, $matches);
		
		// Matches:
		//     [0] => T15:25:10-04:00
		//     [1] => 15
		//     [2] => 25
		//     [3] => 10
		//     [4] => -04:00
		//     [5] => -
		//     [6] => 04
		//     [7] => 00
		
		if (isset($matches[1]))
			$this->setHour($matches[1]);
		
		if (isset($matches[2]))
			$this->setMinute($matches[2]);
		
		if (isset($matches[3]))
			$this->setSecond($matches[3]);
		
		if (isset($matches[4]) && $matches[4] == 'Z') {
			$this->setOffsetHour(0);
			$this->setOffsetMinute(0);
		} else if (isset($matches[4])) {
			$sign = $matches[5];
			$hour = $matches[6];
			if (isset($matches[7]))
				$minute = $matches[7];
			else
				$minute = 0;
			$this->setOffsetHour(intval($sign.$hour));
			$this->setOffsetMinute(intval($sign.$minute));
		}
	}
}

?>