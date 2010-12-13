<?php

/**
 * @since 7/14/05
 * @package harmoni.primitives.numbers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Integer.class.php,v 1.7 2007/10/10 22:58:34 adamfranco Exp $
 */ 

require_once(dirname(__FILE__)."/Number.class.php");

/**
 * A simple Integer data type.
 *
 * @package harmoni.primitives.numbers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Integer.class.php,v 1.7 2007/10/10 22:58:34 adamfranco Exp $
 */
class Integer 
	extends Number
{
	
/*********************************************************
 * Class Methods - Instance Creation
 *********************************************************/

	/**
	 * Answer a new object with the value specified
	 * 
	 * @param mixed $value
	 * @param optional string $class The class to instantiate. Do NOT use outside 
	 *		of this package.
	 * @return object Integer
	 * @access public
	 * @static
	 * @since 7/14/05
	 */
	static function withValue ( $value, $class = 'Integer') {
		return parent::withValue($value, $class);
	}

	/**
	 * Answer a new object with the value specified
	 * 
	 * @param string $string a string representation of the object
	 * @return object Double
	 * @access public
	 * @static
	 * @since 3/14/06
	 */
	static function fromString ($string, $class = 'Integer') {
		return parent::fromString($string, $class);
	}

	
	/**
	 * Answer a new object with the value zero
	 * 
	 * @param optional string $class The class to instantiate. Do NOT use outside 
	 *		of this package.
	 * @return object Integer
	 * @access public
	 * @static
	 * @since 7/14/05
	 */
	static function zero ( $class = 'Integer') {
		return parent::zero($class);
	}
		
/*********************************************************
 * Instance Methods - Arithmatic
 *********************************************************/
	
	/**
	 * Answer the sum of the receiver and aNumber.
	 * 
	 * @param object Number $aNumber
	 * @return object Number
	 * @access public
	 * @since 7/14/05
	 */
	function plus ( $aNumber ) {
		if (!(strtolower($class) == strtolower('Integer')
			|| is_subclass_of(new $class, 'Integer')))
		{
			$obj = Integer::withValue($this->value() + $aNumber->value());
			return $obj;
		} else {
			$obj = Float::withValue($this->value() + $aNumber->value());
			return $obj;
		}
	}
	
	/**
	 * Answer the result of multiplying the receiver and aNumber.
	 * 
	 * @param object Number $aNumber
	 * @return object Number
	 * @access public
	 * @since 7/14/05
	 */
	function multipliedBy ( $aNumber ) {
		if (!(strtolower($class) == strtolower('Integer')
			|| is_subclass_of(new $class, 'Integer')))
		{
			$obj = Integer::withValue($this->value() * $aNumber->value());
			return $obj;
		} else {
			$obj = Float::withValue($this->value() * $aNumber->value());
			return $obj;
		}
	}
	
	/**
	 * Answer the result of dividing the receiver and aNumber.
	 * 
	 * @param object Number $aNumber
	 * @return object Number
	 * @access public
	 * @since 7/14/05
	 */
	function dividedBy ( $aNumber ) {
		$obj = Float::withValue($this->value() / $aNumber->value());
		return $obj;
	}
	
/*********************************************************
 * Instance Methods - Private
 *********************************************************/
	
	/**
	 * Set the internal value to a PHP primitive.
	 * 
	 * @param mixed $value
	 * @return void
	 * @access private
	 * @since 7/14/05
	 */
	function _setValue ( $value ) {
		$this->_value = intval($value);
	}
}