<?php
/**
 * @since 7/14/05
 * @package harmoni.primitives.numbers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Number.class.php,v 1.6 2007/10/10 22:58:34 adamfranco Exp $
 */ 
 
require_once(dirname(__FILE__)."/../Magnitudes/Magnitude.class.php");

/**
 * This is a partial port of the Squeak/Smalltalk Number class.
 *
 * Class Number holds the most general methods for dealing with numbers. 
 * Subclasses Float, Fraction, and Integer, and their subclasses, provide concrete
 * representations of a numeric quantity.
 *
 * All of Number's subclasses participate in a simple type coercion mechanism that 
 * supports mixed-mode arithmetic and comparisons.  It works as follows:  If
 *		self<typeA> op: arg<typeB>
 * fails because of incompatible types, then it is retried in the following guise:
 *		(arg adaptTypeA: self) op: arg adaptToTypeA.
 * This gives the arg of typeB an opportunity to resolve the incompatibility, knowing 
 * exactly what two types are involved.  If self is more general, then arg will be 
 * converted, and viceVersa.  This mechanism is extensible to any new number classes 
 * that one might wish to add to Squeak.  The only requirement is that every subclass 
 * of Number must support a pair of conversion methods specific to each of the other
 * subclasses of Number.
 * 
 * To create new Number subclass instances, <b>use one of the static instance-creation 
 * methods</b>, NOT 'new Integer', etc:
 *		- {@link withValue Number::withValue($value)}
 *		- {@link zero Number::zero()}
 *
 * @since 7/14/05
 * @package harmoni.primitives.numbers
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Number.class.php,v 1.6 2007/10/10 22:58:34 adamfranco Exp $
 */
abstract class Number 
	extends Magnitude
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
	 * @return object Number
	 * @access public
	 * @static
	 * @since 7/14/05
	 */
	static function withValue ( $value, $class = 'Number') {
		// Validate our passed class name.
		if (!is_subclass_of(new $class, 'Number'))
		{
			die("Class, '$class', is not a subclass of 'Number'.");
		}
		
		$number = new $class;
		$number->_setValue($value);
		return $number;
	}
	
	/**
	 * Answer a new object with the value specified
	 * 
	 * @param string $string
	 * @param optional string $class The class to instantiate. Do NOT use outside 
	 *		of this package.
	 * @return object Number
	 * @access public
	 * @since 3/14/06
	 * @static
	 */
	static function fromString ( $string, $class = 'Number') {
		// Validate our passed class name.
		if (!is_subclass_of(new $class, 'Number'))
		{
			die("Class, '$class', is not a subclass of 'Number'.");
		}
		
		$number = new $class;
		$number->_setValue($value);
		return $number;
	}
	
	
	/**
	 * Answer a new object with the value zero
	 * 
	 * @param optional string $class The class to instantiate. Do NOT use outside 
	 *		of this package.
	 * @return object Number
	 * @access public
	 * @since 7/14/05
	 * @static
	 */
	static function zero ( $class = 'Number') {
		eval('$result = '.$class.'::withValue(0);');
		
		return $result;
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
	abstract function plus ( $aNumber );
	
	/**
	 * Answer the difference of the receiver and aNumber.
	 * 
	 * @param object Number $aNumber
	 * @return object Number
	 * @access public
	 * @since 7/14/05
	 */
	function minus ( $aNumber ) {
		return $this->plus($aNumber->negated());
	}
	
	/**
	 * Answer the result of multiplying the receiver and aNumber.
	 * 
	 * @param object Number $aNumber
	 * @return object Number
	 * @access public
	 * @since 7/14/05
	 */
	abstract function multipliedBy ( $aNumber ) ;
	
	/**
	 * Answer the result of dividing the receiver and aNumber.
	 * 
	 * @param object Number $aNumber
	 * @return object Number
	 * @access public
	 * @since 7/14/05
	 */
	abstract function dividedBy ( $aNumber ) ;
	
	/**
	 * Integer quotient defined by division with truncation toward negative 
	 * infinity. 9//4 = 2, -9//4 = -3. -0.9//0.4 = -3. Modulo (\\) answers the remainder 
	 * from this division.
	 * 
	 * @param object Number $aNumber
	 * @return object Number
	 * @access public
	 * @since 7/14/05
	 */
	function modIntegerQuotient ( $aNumber ) {
		$temp =$this->dividedBy($aNumber);
		return $temp->floor();
	}
	
	/**
	 * modulo. Remainder defined in terms of the integerQutient (\\). Answer a 
	 * Number with the same sign as aNumber. 
	 * e.g. 9\\4 = 1, -9\\4 = 3, 9\\-4 = -3, 0.9\\0.4 = 0.1.
	 * 
	 * @param object Number $aNumber
	 * @return object Number
	 * @access public
	 * @since 7/14/05
	 */
	function modulo ( $aNumber ) {
		$temp =$this->integerQuotient($aNumber);
		$temp =$temp->multipliedBy($aNumber);
		return $this->minus($temp);
	}
	
	/**
	 * Answer a Number that is the absolute value (positive magnitude) of the 
	 * receiver.
	 * 
	 * @return object Number
	 * @access public
	 * @since 7/14/05
	 */
	function abs () {
		if ($this->isLessThan(Integer::zero())) {
			return $this->negated();
		} else {
			return $this;
		}
	}
	
	/**
	 * Answer a Number that is the negation of the receiver.
	 * 
	 * @return object Number
	 * @access public
	 * @since 7/14/05
	 */
	function negated () {
		$zero = Integer::zero();
		return $zero->minus($this);
	}
	
	/**
	 * Integer quotient defined by division with truncation toward zero. 
	 * 	-9 quo: 4 = -2
	 *	-0.9 quo: 0.4 = -2. 
	 * rem: answers the remainder from this division.
	 * 
	 * @param object Number $aNumber
	 * @return object Number
	 * @access public
	 * @since 7/14/05
	 */
	function remIntegerQuotient ( $aNumber ) {
		$temp =$this->dividedBy($aNumber);
		return $temp->truncated();
	}
	
	/**
	 * Remainder defined in terms of quo:. Answer a Number with the same 
	 * sign as self. e.g. 9 rem: 4 = 1, -9 rem: 4 = -1. 0.9 rem: 0.4 = 0.1.
	 * 
	 * @param object Number $aNumber
	 * @return object Number
	 * @access public
	 * @since 7/14/05
	 */
	function remainder ( $aNumber ) {
		$temp =$this->remIntegerQuotient($aNumber);
		return $this->minus($temp->multipliedBy($aNumber));
	}
	
	/**
	 * Answer 1 divided by the receiver. Create an error notification if the 
	 * receiver is 0.
	 * 
	 * @param object Number $aNumber
	 * @return object Number
	 * @access public
	 * @since 7/14/05
	 */
	function reciprical () {
		if ($this->isEqualTo(Integer::withValue(0)))
			throwError(new Error("Division by zero"));
		else {
			$one = Integer::withValue(1);
			return $one->dividedBy($this);
		}
	}
	
/*********************************************************
 * Instance Methods - Truncation and Rounding
 *********************************************************/
	
	/**
	 * Answer the integer nearest the receiver toward positive infinity.
	 * 
	 * @return object Number
	 * @access public
	 * @since 7/14/05
	 */
	function ceiling () {
		if ($this->isLessThanOrEqualTo(Integer::zero()))
			return $this->truncated();
		else {
			$temp =$this->negated();
			$temp =$temp->floor();
			return $temp->negated();
		}
	}
	
	/**
	 * Answer the integer nearest the receiver toward negative infinity.
	 * 
	 * @return object Number
	 * @access public
	 * @since 7/14/05
	 */
	function floor () {
		$truncation =$this->truncated();
		if ($this->isGreaterThanOrEqualTo(Integer::zero()))
			return $truncation;
		else {
			if ($this->isEqualTo($truncation))
				return $truncation;
			else
				return $truncation->minus(Integer::withValue(1));
		}
	}
	
	/**
	 * Answer an integer nearest the receiver toward zero.
	 * 
	 * @return object Number
	 * @access public
	 * @since 7/14/05
	 */
	function truncated () {
		return $this->remIntegerQuotient(Integer::withValue(1));
	}
	
/*********************************************************
 * Instance Methods - Converting
 *********************************************************/
 	
 	/**
 	 * Answer a double-precision floating-point number approximating the receiver.
 	 * 
 	 * @return Double
 	 * @access public
 	 * @since 7/14/05
 	 */
 	function asDouble () {
 		$obj = Double::withValue(doubleval($this->value()));
 		return $obj;
 	}
 	
 	/**
 	 * Answer a floating-point number approximating the receiver.
 	 * 
 	 * @return object Float
 	 * @access public
 	 * @since 7/14/05
 	 */
 	function asFloat () {
 		$obj = Float::withValue(floatval($this->value()));
 		return $obj;
 	}
 	
 	/**
 	 * Answer an Integer nearest the receiver toward zero.
 	 * 
 	 * @return object Integer
 	 * @access public
 	 * @since 7/14/05
 	 */
 	function asInteger () {
 		return $this->truncated();
 	}
 	
 	/**
 	 * Answer a number.
 	 * 
 	 * @return object Number
 	 * @access public
 	 * @since 7/14/05
 	 */
 	function asNumber () {
 		return $this;
 	}
 	
/*********************************************************
 * Instance Methods - Comparing
 *********************************************************/
	
	/**
	 * Test if this is less than aMagnitude.
	 * 
	 * @param object Magnitude $aMagnitude
	 * @return boolean
	 * @access public
	 * @since 5/4/05
	 */
	function isLessThan ( $aMagnitude ) {
		if (!method_exists($aMagnitude, 'asFloat'))
 			return false;
 		
 		$asFloat =$aMagnitude->asFloat();
 		return ($this->value() < $asFloat->value())?true:false;
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
 		if (!method_exists($anObject, 'asFloat'))
 			return false;
 		
 		$asFloat =$anObject->asFloat();
		return ($this->value() == $asFloat->value())?true:false;
	}
 	
/*********************************************************
 * Instance Methods - Printing/Accessing
 *********************************************************/
 	
 	/**
 	 * Answer a String whose characters are a description of the receiver.
 	 * Override this method as needed to provide a better representation
 	 * 
 	 * @return string
 	 * @access public
 	 * @since 7/11/05
 	 */
 	function printableString () {
		return (string) $this->value();
	}
	
	/**
	 * Answer the PHP primitive value of the reciever.
	 * 
	 * @return mixed may be an int, float, double, etcetera
	 * @access public
	 * @since 7/14/05
	 */
	function value () {
		return $this->_value;
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
	abstract function _setValue ( $value ) ;
}

?>