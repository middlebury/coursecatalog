<?php
/**
 * @since 5/23/05
 * @package harmoni.primitives.chronology.string_parsers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ANSI58216StringParser.class.php,v 1.2 2006/06/26 12:55:08 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */ 
 
require_once(dirname(__FILE__)."/StringParser.class.php");
//require_once(dirname(__FILE__)."/RegexStringParser.class.php");

/**
 * This StringParser parses durations formatted as per ANSI 5.8.2.16: [-]D:HH:MM:SS[.S]
 *
 *	Examples:
 * 		- '0:00:00:00' 
 *		- '0:00:00:00.000000001' 
 *		- '0:00:00:00.999999999' 
 *		- '0:00:00:00.100000000' 
 *		- '0:00:00:00.10' 
 *		- '0:00:00:00.1' 
 *		- '0:00:00:01' 
 *		- '0:12:45:45' 
 *		- '1:00:00:00' 
 *		- '365:00:00:00' 
 *		- '-7:09:12:06.10' 
 *		- '+0:01:02' 
 *		- '+0:01:02:3' 
 * 
 * @since 5/23/05
 * @package harmoni.primitives.chronology.string_parsers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ANSI58216StringParser.class.php,v 1.2 2006/06/26 12:55:08 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class ANSI58216StringParser 
	extends RegexStringParser {
	
/*********************************************************
 * Class Methods
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

#-----------------------------------------------------------------------------
	(?:
		(-)								# The sign of the duration
		|
		\+								# Or a + or space
		|
		\s
	)?							
	
	([0-9]+)							# The number of Days

#-----------------------------------------------------------------------------
	:									# Colon
	
	(									# Two-digit hour
		(?:  [0-1][0-9])
		|
		(?: 2[0-4])
	)
	
	:									# Colon
	
	([0-5][0-9])						# Two-digit minute
	
	
	(?:									# Optional second component
	
		:								# Colon

		(								# Two-digit second
			[0-5][0-9]					

			(?: \.[0-9]+)?				# followed by an optional decimal.
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
		//     [0] => -7:09:12:06.10
		//     [1] => -
		//     [2] => 7
		//     [3] => 09
		//     [4] => 12
		//     [5] => 06.10
		
		if (isset($matches[2]))
			$this->setDay($matches[1].$matches[2]);
		if (isset($matches[3]))
			$this->setHour($matches[1].$matches[3]);
		if (isset($matches[4]))
			$this->setMinute($matches[1].$matches[4]);
		if (isset($matches[5]))
			$this->setSecond($matches[1].$matches[5]);
	}
	
	/**
	 * To allow for very large days, override setDay to not use intval(). and
	 * to just leave the days as a string for now.
	 * 
	 * @param integer $anInteger
	 * @return void
	 * @access private
	 * @since 5/25/05
	 */
	function setDay ( $anInteger ) {
		$this->day = $anInteger;
	}
}

?>