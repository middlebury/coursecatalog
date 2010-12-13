<?php
/**
 * @since 5/23/05
 * @package harmoni.primitives.chronology.string_parsers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: MonthNameDayYearStringParser.class.php,v 1.3 2006/11/30 22:02:04 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */ 
 
require_once(dirname(__FILE__)."/StringParser.class.php");
//require_once(dirname(__FILE__)."/TwoDigitYearStringParser.class.php");

/**
 * This StringParser can handle dates that contain a textual month-name or 
 * month-abbreviation followed by an integer day then an integer year.
 * Delimiters are ignored, but required between the day and year. Examples:
 * 		- April 5, 1982
 * 		- Apr 5 1982
 *		- Apr 5, '82
 * 
 * @since 5/23/05
 * @package harmoni.primitives.chronology.string_parsers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: MonthNameDayYearStringParser.class.php,v 1.3 2006/11/30 22:02:04 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class MonthNameDayYearStringParser 
	extends TwoDigitYearStringParser 
{

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
	([a-zA-Z]+)		# MonthName
	
	[^0-9a-zA-Z]*	# Optional delimiters
	
	[^0-9a-zA-Z]+		# delimiters
	
	(					# Day of the Month
		(?:  0?[1-9])
		|
		(?:  [1-2][0-9])
		|
		(?:  3[01])
	)
	
	(?: st|nd|rd|th)?	# apendum to date, i.e. 1st, 2nd, 10th
	
	[^0-9a-zA-Z]+		# delimiters
	
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
		//     [0] => May 23, 2005
		//     [1] => May
		//     [2] => 23
		//     [3] => 2005
		
		$this->setYear($matches[3]);
		$this->setMonth($matches[1]);
		$this->setDay($matches[2]);
	}
}

?>