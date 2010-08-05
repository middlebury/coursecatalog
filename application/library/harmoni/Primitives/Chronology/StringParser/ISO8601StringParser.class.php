<?php
/**
 * @since 5/23/05
 * @package harmoni.primitives.chronology.string_parsers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ISO8601StringParser.class.php,v 1.5 2007/02/26 14:47:44 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */ 
 
require_once(dirname(__FILE__)."/StringParser.class.php");
//require_once(dirname(__FILE__)."/RegexStringParser.class.php");

/**
 * This StringParser can handle ISO 8601 dates. {@link http://www.cl.cam.ac.uk/~mgk25/iso-time.html}
 * Examples:
 * 		- 1982-04-05T15:25:21+5:00
 * 
 * @since 5/23/05
 * @package harmoni.primitives.chronology.string_parsers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ISO8601StringParser.class.php,v 1.5 2007/02/26 14:47:44 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class ISO8601StringParser 
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
^											# Start of the line

#-----------------------------------------------------------------------------
	(?:										# The date component
		([0-9]{4})							# Four-digit year
		
		[\-\/:]?							# Optional Hyphen, slash, or colon delimiter
		
		(?:									# Two-digit month
			(
			(?:  0[1-9])
			|
			(?:  1[0-2])
			)
		
			[\-\/:]?						# Optional Hyphen, slash, or colon delimiter
			
			(?:									# Two-digit day
				(
				(?:  0[1-9])
				|
				(?:  (?: 1|2)[0-9])
				|
				(?:  3[0-1])
				)
				
		
		
				[\sT]?									# Optional delimiter
			
			#-----------------------------------------------------------------------------		
				(?:										# The time component
				
					(									# Two-digit hour
						(?:  [0-1][0-9])
						|
						(?: 2[0-4])
					)
					
					(?:
						:?									# Optional Colon
						
						([0-5][0-9])?						# Two-digit minute
						
						(?:
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
						)?
					)?
				)?
			)?
		)?
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
		//     [0] => 2005-05-23T15:25:10-04:00
		//     [1] => 2005
		//     [2] => 05
		//     [3] => 23
		//     [4] => 15
		//     [5] => 25
		//     [6] => 10
		//     [7] => -04:00
		//     [8] => -
		//     [9] => 04
		//     [10] => 00

		if (isset($matches[1]))
			$this->setYear($matches[1]);
		
		if (isset($matches[2]))
			$this->setMonth($matches[2]);
		
		if (isset($matches[3]))
			$this->setDay($matches[3]);
		
		if (isset($matches[4]))
			$this->setHour($matches[4]);
		
		if (isset($matches[5]))
			$this->setMinute($matches[5]);
		
		if (isset($matches[6]))
			$this->setSecond($matches[6]);
		
		if (isset($matches[7]) && $matches[7] == 'Z') {
			$this->setOffsetHour(0);
			$this->setOffsetMinute(0);
		} else if (isset($matches[7])) {
			$sign = $matches[8];
			$hour = $matches[9];
			if (isset($matches[10]))
				$minute = $matches[10];
			else
				$minute = 0;
			$this->setOffsetHour(intval($sign.$hour));
			$this->setOffsetMinute(intval($sign.$minute));
		}
	}
}

?>