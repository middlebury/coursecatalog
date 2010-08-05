<?php

require_once(dirname(__FILE__)."/../Objects/SObject.class.php");


/**
 * A simple String data type.
 *
 * @package harmoni.primitives.collections-text
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: String.class.php,v 1.8 2008/02/14 20:20:24 adamfranco Exp $
 */
class String 
	extends SObject 
{
	
	protected $_string;

	function String($string="") {
		$this->_string = (string) $string;
	}
	
	/**
 	 * Answer a String whose characters are a description of the receiver.
 	 * Override this method as needed to provide a better representation
 	 * 
 	 * @return string
 	 * @access public
 	 * @since 7/11/05
 	 */
 	function printableString () {
		return $this->_string;
	}
	
	/**
	 * Instantiates a new String object with the passed value.
	 * @param string $value
	 * @return ref object
	 * @access public
	 * @static
	 */
	static function withValue($value) {
		$string = new String($value);
		return $string;
	}
	
	/**
	 * Instantiates a new String object with the passed value.
	 *
	 * allowing 'fromString' for string values
	 * @param string $aString
	 * @return ref object
	 * @access public
	 * @static
	 */
	static function fromString($aString) {
		$string = new String($aString);
		return $string;
	}

	
	/**
 	 * Answer whether the receiver and the argument are the same.
 	 * If = is redefined in any subclass, consider also redefining the 
	 * message hash.
 	 * 
 	 * @param object $anObject
 	 * @return boolean
 	 * @access public
 	 * @since 7/11/05
 	 */
 	function isEqualTo ( $anObject ) {
 		if (!method_exists($anObject, 'asString'))
 			return false;
 			
		return strcmp($anObject->asString(), $this->asString())==0?true:false;
	}
	
	/**
 	 * Convert 'smart quotes' and other non-UTF8 characters to the UTF8 equivalent.
 	 *
 	 * Method implementation from Chris Shiflett:
 	 *		http://shiflett.org/blog/2005/oct/convert-smart-quotes-with-php
 	 * 
 	 * @param string $inputString
 	 * @return string
 	 * @access public
 	 * @since 6/16/08
 	 */
 	public function makeUtf8 () {
		// Try to automatically convert if a a non-utf8 encoding is used, but 
		// preserve UTF-8 by making it the first thing to match.
		if (!function_exists('mb_convert_encoding'))
			throw new ConfigurationErrorException("PHP must be compiled with the --enable-mbstring option");
		$this->_string = mb_convert_encoding($this->_string, "UTF-8", "UTF-8, ISO-8859-1");
		
 	
		$search = array(
						// Control Chars
							chr(0),		// NUL	(Null char)
							chr(1),		// SOH	(Start of Heading)
							chr(2),		// STX	(Start of Text)
							chr(3),		// ETX	(End of Text)
							chr(4),		// EOT	(End of Transmission)
							chr(5),		// ENQ	(Enquiry)
							chr(6),		// ACK	(Acknowledgment)
							chr(7),		// BEL	(Bell)
							chr(8),		// BS	(Back Space)
// 							chr(9),		// HT	(Horizontal Tab)
// 							chr(10),	// LF	(Line Feed)
							chr(11),	// VT	(Vertical Tab)
							chr(12),	// FF	(Form Feed)
// 							chr(13),	// CR	(Carriage Return)
							chr(14),	// SO	(Shift Out / X-On)
							chr(15),	// SI	(Shift In / X-Off)
							chr(16),	// DLE	(Data Line Escape)
							chr(17),	// DC1	(Device Control 1 (oft. XON))
							chr(18),	// DC2	(Device Control 2)
							chr(19),	// DC3	(Device Control 3 (oft. XOFF))
							chr(20),	// DC4	(Device Control 4)
							chr(21),	// NAK	(Negative Acknowledgement)
							chr(22),	// SYN	(Synchronous Idle)
							chr(23),	// ETB	(End of Transmit Block)
							chr(24),	// CAN	(Cancel)
							chr(25),	// EM	(End of Medium)
							chr(26),	// SUB	(Substitute)
							chr(27),	// ESC	(Escape)
							chr(28),	// FS	(File Separator)
							chr(29),	// GS	(Group Separator)
							chr(30),	// RS	(Record Separator)
							chr(31),	// US	(Unit Separator)
							chr(127),	// DEL	(Delete)

						// Undefined extended ASCII (ISO 8859-1 is below)
// 							chr(128),	// €	(Euro sign) 	 
// 							chr(129),	// 	
// 							chr(130),	// ‚	(Single low-9 quotation mark)
// 							chr(131),	// ƒ	(Latin small letter f with hook)
// 							chr(132),	// „	(Double low-9 quotation mark)
// 							chr(133),	// …	(Horizontal ellipsis)
// 							chr(134),	// †	(Dagger)
// 							chr(135),	// ‡	(Double dagger)
// 							chr(136),	// ˆ	(Modifier letter circumflex accent)
// 							chr(137),	// ‰	(Per mille sign)
// 							chr(138),	// Š	(Latin capital letter S with caron)
// 							chr(139),	// ‹	(Single left-pointing angle quotation)
// 							chr(140),	// Œ	(Latin capital ligature OE) 
// 							chr(141),	// 		
// 							chr(142),	// Ž	(Latin captial letter Z with caron)
// 							chr(143),	// 
// 							chr(144),	// 
// 							chr(145),	// ‘	(Left single quotation mark)
// 							chr(146),	// ’	(Right single quotation mark)
// 							chr(147),	// “	(Left double quotation mark)
// 							chr(148),	// ”	(Right double quotation mark)
// 							chr(149),	// •	(Bullet)
// 							chr(150),	// –	(En dash)
// 							chr(151),	// —	(Em dash)
// 							chr(152),	// ˜	(Small tilde)
// 							chr(153),	// ™	(Trade mark sign)
// 							chr(154),	// š	(Latin small letter S with caron)
// 							chr(155),	// ›	(Single right-pointing angle quotation mark)
// 							chr(156),	// œ	(Latin small ligature oe) 	 
// 							chr(158),	// 
// 							chr(158),	// ž	(Latin small letter z with caron)
// 							chr(159)	// Ÿ	(Latin capital letter Y with diaeresis)
			);
		
		$replace = array(
						// Control Chars
							"",		// NUL	(Null char)
							"",		// SOH	(Start of Heading)
							"",		// STX	(Start of Text)
							"",		// ETX	(End of Text)
							"",		// EOT	(End of Transmission)
							"",		// ENQ	(Enquiry)
							"",		// ACK	(Acknowledgment)
							"",		// BEL	(Bell)
							"",		// BS	(Back Space)
// 							"\t",	// HT	(Horizontal Tab)
// 							"\n",	// LF	(Line Feed)
							"",		// VT	(Vertical Tab)
							"",		// FF	(Form Feed)
// 							"\r",	// CR	(Carriage Return)
							"",		// SO	(Shift Out / X-On)
							"",		// SI	(Shift In / X-Off)
							"",		// DLE	(Data Line Escape)
							"",		// DC1	(Device Control 1 (oft. XON))
							"",		// DC2	(Device Control 2)
							"",		// DC3	(Device Control 3 (oft. XOFF))
							"",		// DC4	(Device Control 4)
							"",		// NAK	(Negative Acknowledgement)
							"",		// SYN	(Synchronous Idle)
							"",		// ETB	(End of Transmit Block)
							"",		// CAN	(Cancel)
							"",		// EM	(End of Medium)
							"",		// SUB	(Substitute)
							"",		// ESC	(Escape)
							"",		// FS	(File Separator)
							"",		// GS	(Group Separator)
							"",		// RS	(Record Separator)
							"",		// US	(Unit Separator)
							"",		// DEL	(Delete)
							
						
						// Undefined extended ASCII (ISO 8859-1 is below)
// 							"€",	// €	(Euro sign) 	 
// 							"",		// 	
// 							"‚",	// ‚	(Single low-9 quotation mark)
// 							"",		// ƒ	(Latin small letter f with hook)
// 							"„",	// „	(Double low-9 quotation mark)
// 							"…",	// …	(Horizontal ellipsis)
// 							"†",	// †	(Dagger)
// 							"‡",	// ‡	(Double dagger)
// 							"ˆ",	// ˆ	(Modifier letter circumflex accent)
// 							"‰",	// ‰	(Per mille sign)
// 							"Š",	// Š	(Latin capital letter S with caron)
// 							"‹",	// ‹	(Single left-pointing angle quotation)
// 							"Œ",	// Œ	(Latin capital ligature OE) 
// 							"",		// 		
// 							"Ž",	// Ž	(Latin captial letter Z with caron)
// 							"",		// 
// 							"",		// 
// 							"'",	// ‘	(Left single quotation mark)
// 							"'",	// ’	(Right single quotation mark)
// 							'"',	// “	(Left double quotation mark)
// 							'"',	// ”	(Right double quotation mark)
// 							"•",	// •	(Bullet)
// 							"-",	// –	(En dash)
// 							"-",	// —	(Em dash)
// 							"˜",	// ˜	(Small tilde)
// 							"™",	// ™	(Trade mark sign)
// 							"š",	// š	(Latin small letter S with caron)
// 							"›",	// ›	(Single right-pointing angle quotation mark)
// 							"œ",	// œ	(Latin small ligature oe) 	 
// 							"",		// 
// 							"ž",	// ž	(Latin small letter z with caron)
// 							"Ÿ"	// Ÿ	(Latin capital letter Y with diaeresis)
			);
		
		// Convert any characters known
		$this->_string = str_replace($search, $replace, $this->asString());

		// Strip out any remaining non-UTF8 characters
// 		$this->_string = @ iconv("UTF-8", "UTF-8//IGNORE", $this->_string);
 	}
}