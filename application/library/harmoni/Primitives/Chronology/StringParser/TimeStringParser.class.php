<?php
/**
 * @since 5/24/05
 * @package harmoni.primitives.chronology.string_parsers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: TimeStringParser.class.php,v 1.3 2006/12/01 16:34:53 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */ 
 
require_once(dirname(__FILE__)."/StringParser.class.php");
//require_once(dirname(__FILE__)."/RegexStringParser.class.php");

/**
 * This StringParser can handle Times (12-hour or 24-hour) in the form:
 *		- <hour>:<minute>:<second> <am/pm>

*	<minute>, <second> or <am/pm> may be omitted.  e.g. 1:59:30 pm; 8AM; 15:30
 * 
 * @since 5/24/05
 * @package harmoni.primitives.chronology.string_parsers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: TimeStringParser.class.php,v 1.3 2006/12/01 16:34:53 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class TimeStringParser 
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

#-----------------------------------------------------------------------------			
	(									# One or Two-digit hour
		(?: [1-9])
		|
		(?: 1[0-9])
		|
		(?: 2[0-4])
	)
	
	(?:									# Optional :Minute:Seconds component
		:								# Colon
		([0-5][0-9])?					# Two-digit minute
		
		(?:								# Optional :Seconds component
			:							# Colon				
			(							# Two-digit second 
				[0-5][0-9]
				(?: \.[0-9]+)?			# followed by an optional decimal.
			)
		)?
	)?
	
	\s?									# Optional space
	
	(am|pm)?							# Optional AM or PM
	
$
/xi";
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
		preg_match(self::getRegex(), $this->input, $matches);
		
		// Matches:
		// [0] => 3:25:10 pm
		// [1] => 3
		// [2] => 25
		// [3] => 10
		// [4] => pm
		
		if (isset($matches[4]) && strtolower($matches[4]) == 'am' && $matches[1] == '12')
			$this->setHour(0);
		else if (isset($matches[4]) && strtolower($matches[4]) == 'pm' && $matches[1] < 13)
			$this->setHour($matches[1] + 12);
		else
			$this->setHour($matches[1]);
		
		if (isset($matches[2]))
			$this->setMinute($matches[2]);
		
		if (isset($matches[3]))
			$this->setSecond($matches[3]);
	}
}

?>