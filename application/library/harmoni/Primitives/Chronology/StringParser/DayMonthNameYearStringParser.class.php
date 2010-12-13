<?php
/**
 * @since 5/23/05
 * @package harmoni.primitives.chronology.string_parsers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DayMonthNameYearStringParser.class.php,v 1.2 2006/06/26 12:55:08 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */ 

require_once(dirname(__FILE__)."/StringParser.class.php");
//require_once(dirname(__FILE__)."/TwoDigitYearStringParser.class.php");

/**
 * This StringParser can handle dates that contain an integer day followed by
 * a textual month-name or month-abbreviation followed by an integer year.
 * Delimiters are ignored. Examples:
 * 		- 5 April 1982
 * 		- 5 April '82
 *		- 5-APR-82
 *		- 5APR82
 *		- 5APRIL1982
 * 
 * @since 5/23/05
 * @package harmoni.primitives.chronology.string_parsers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: DayMonthNameYearStringParser.class.php,v 1.2 2006/06/26 12:55:08 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class DayMonthNameYearStringParser 
	extends TwoDigitYearStringParser {
	
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
^
	(					# Day of Month (optional)
		(?:  0?[1-9])
		|
		(?:  [1-2][0-9])
		|
		(?:  3[01])
	)?
	
	[^0-9a-zA-Z]*		# Delimiter
	
	([a-zA-Z]+)			# Month
	
	[^0-9a-zA-Z]*		# Delimiter
	
	([0-9]{2,})			# Year
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
		//     [0] => 23 May 2005
		//     [1] => 23
		//     [2] => May
		//     [3] => 2005
		
		$this->setYear($matches[3]);
		$this->setMonth($matches[2]);
		$this->setDay($matches[1]);
	}
}

?>