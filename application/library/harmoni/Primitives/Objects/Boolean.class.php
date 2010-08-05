<?php

require_once(dirname(__FILE__)."/../Objects/SObject.class.php");


/**
 * A simple Boolean data type.
 *
 * @package harmoni.primitives
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Boolean.class.php,v 1.11 2007/10/10 22:58:34 adamfranco Exp $
 */
class Boolean 
	extends SObject
{
	
	var $_bool;
	
/*********************************************************
 * Class Methods - Virtual Constructors
 *********************************************************/

	/**
	 * Instantiates a new Boolean object with the passed value.
	 * @param string $value
	 * @return ref object
	 * @access public
	 * @static
	 */
	static function withValue($value) {
		$temp = new Boolean($value);
		return $temp;
	}

	
	/**
	 * Instantiates a new Boolean object from a known string
	 * 
	 * @param string $aString true(case insensitive) is true if not, it's false
	 * @return ref object
	 * @access public
	 * @since 3/14/06
	 * @static
	 */
	static function fromString ($aString) {
		$temp = new Boolean(((strtolower($aString) == "true")?true:false));
		return $temp;
	}
	
	/**
	 * Instantiates a new Boolean object with the value, false.
	 * 
	 * @return object Boolean
	 * @access public
	 * @since 8/11/05
	 * @static
	 */
	static function false () {
		$temp = new Boolean(false);
		return $temp;
	}
	
	/**
	 * Instantiates a new Boolean object with the value, true.
	 * 
	 * @return object Boolean
	 * @access public
	 * @since 8/11/05
	 * @static
	 */
	static function true () {
		$temp = new Boolean(true);
		return $temp;
	}

/*********************************************************
 * Instance Methods
 *********************************************************/

	function Boolean($value=true) {
		$this->_bool = (bool) $value;
	}
	
	/**
	 * Returns the boolean value.
	 * @access public
	 * @return boolean
	 */
	function value()
	{
		return $this->_bool;
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
		return $this->_bool?"true":"false";
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
 		if (!method_exists($anObject, 'value'))
 			return false;
 			
		return ($this->_bool===$anObject->value())?true:false;
	}
	
	/**
	 * Answer true if this object represents a 'true' value, false otherwise.
	 * 
	 * @return boolean
	 * @access public
	 * @since 9/29/05
	 */
	function isTrue () {
		return $this->_bool;
	}
	
	/**
	 * Answer true if this object represents a 'false' value, false otherwise.
	 * 
	 * @return boolean
	 * @access public
	 * @since 9/29/05
	 */
	function isFalse () {
		return $this->_bool?false:true;
	}
}