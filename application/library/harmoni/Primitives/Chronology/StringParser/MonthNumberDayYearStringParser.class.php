<?php
/**
 * @since 5/23/05
 * @package harmoni.primitives.chronology.string_parsers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: MonthNumberDayYearStringParser.class.php,v 1.2 2006/06/26 12:55:08 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
 
require_once(dirname(__FILE__)."/StringParser.class.php");
//require_once(dirname(__FILE__)."/TwoDigitYearStringParser.class.php");

/**
 * This StringParser can handle dates that contain an integer month followed by 
 * an integer day then an integer year. Delimiter choice is arbitrary, but delimiters 
 * are required. Examples:
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
 * @version $Id: MonthNumberDayYearStringParser.class.php,v 1.2 2006/06/26 12:55:08 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class MonthNumberDayYearStringParser 
	extends TwoDigitYearStringParser
{

/*********************************************************
 * Static Methods
 *********************************************************/
 	
	/**
	 * Return the regular expression used by this parser
	 * 
	 * @return string
	 * @access public
	 * @since 5/24/05
	 * @static
	 */
	public static function getRegex () {
		return
"/
^
	(					# MonthNumber
		(?:  0?[1-9])
		|
		(?:  1[0-2])
	)
	
	[^0-9a-zA-Z]+		# delimiters
	
	(					# Day of the Month
		(?:  0?[1-9])
		|
		(?:  [1-2][0-9])
		|
		(?:  3[01])
	)
	
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
	 * @access protected
	 * @since 5/23/05
	 */
	function parse () {
		preg_match(self::getRegex(), $this->input, $matches);
		
		// Matches:
		//     [0] => 04/05/1982
		//     [1] => 04
		//     [2] => 05
		//     [3] => 1982
		
		$this->setYear($matches[3]);
		$this->setMonth($matches[1]);
		$this->setDay($matches[2]);
	}
}

?>