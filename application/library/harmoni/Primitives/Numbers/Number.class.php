<?php

/**
 * @since 7/14/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Number.class.php,v 1.6 2007/10/10 22:58:34 adamfranco Exp $
 */

require_once __DIR__.'/../Magnitudes/Magnitude.class.php';

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
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Number.class.php,v 1.6 2007/10/10 22:58:34 adamfranco Exp $
 */
abstract class Number extends Magnitude
{
    private $value;

    /*********************************************************
     * Class Methods - Instance Creation
     *********************************************************/

    /**
     * Answer a new object with the value specified.
     *
     * @return Number
     *
     * @static
     *
     * @since 7/14/05
     */
    public static function withValue($value)
    {
        if (!is_numeric($value)) {
            throw new InvalidArgumentException('non-numeric value given.');
        }
        $number = new static();
        $number->_setValue($value);

        return $number;
    }

    /**
     * Answer a new object with the value specified.
     *
     * @return Number
     *
     * @since 3/14/06
     *
     * @static
     */
    public static function fromString(string $string)
    {
        if (!is_numeric($string)) {
            throw new InvalidArgumentException('non-numeric value given.');
        }
        $number = new static();
        $number->_setValue($value);

        return $number;
    }

    /**
     * Answer a new object with the value zero.
     *
     * @return Number
     *
     * @since 7/14/05
     *
     * @static
     */
    public static function zero()
    {
        return static::withValue(0);
    }

    /*********************************************************
     * Instance Methods - Arithmatic
     *********************************************************/

    /**
     * Answer the sum of the receiver and aNumber.
     *
     * @return Number
     *
     * @since 7/14/05
     */
    abstract public function plus(Number $aNumber);

    /**
     * Answer the difference of the receiver and aNumber.
     *
     * @return Number
     *
     * @since 7/14/05
     */
    public function minus(Number $aNumber)
    {
        return $this->plus($aNumber->negated());
    }

    /**
     * Answer the result of multiplying the receiver and aNumber.
     *
     * @return Number
     *
     * @since 7/14/05
     */
    abstract public function multipliedBy(Number $aNumber);

    /**
     * Answer the result of dividing the receiver and aNumber.
     *
     * @return Number
     *
     * @since 7/14/05
     */
    abstract public function dividedBy(Number $aNumber);

    /**
     * Integer quotient defined by division with truncation toward negative
     * infinity. 9//4 = 2, -9//4 = -3. -0.9//0.4 = -3. Modulo (\\) answers the remainder
     * from this division.
     *
     * @return Number
     *
     * @since 7/14/05
     */
    public function modIntegerQuotient(Number $aNumber)
    {
        $temp = $this->dividedBy($aNumber);

        return $temp->floor();
    }

    /**
     * modulo. Remainder defined in terms of the integerQutient (\\). Answer a
     * Number with the same sign as aNumber.
     * e.g. 9\\4 = 1, -9\\4 = 3, 9\\-4 = -3, 0.9\\0.4 = 0.1.
     *
     * @return Number
     *
     * @since 7/14/05
     */
    public function modulo(Number $aNumber)
    {
        $temp = $this->integerQuotient($aNumber);
        $temp = $temp->multipliedBy($aNumber);

        return $this->minus($temp);
    }

    /**
     * Answer a Number that is the absolute value (positive magnitude) of the
     * receiver.
     *
     * @return Number
     *
     * @since 7/14/05
     */
    public function abs()
    {
        if ($this->isLessThan(Integer::zero())) {
            return $this->negated();
        } else {
            return $this;
        }
    }

    /**
     * Answer a Number that is the negation of the receiver.
     *
     * @return Number
     *
     * @since 7/14/05
     */
    public function negated()
    {
        $zero = Integer::zero();

        return $zero->minus($this);
    }

    /**
     * Integer quotient defined by division with truncation toward zero.
     * 	-9 quo: 4 = -2
     *	-0.9 quo: 0.4 = -2.
     * rem: answers the remainder from this division.
     *
     * @return Number
     *
     * @since 7/14/05
     */
    public function remIntegerQuotient(Number $aNumber)
    {
        $temp = $this->dividedBy($aNumber);

        return $temp->truncated();
    }

    /**
     * Remainder defined in terms of quo:. Answer a Number with the same
     * sign as self. e.g. 9 rem: 4 = 1, -9 rem: 4 = -1. 0.9 rem: 0.4 = 0.1.
     *
     * @return Number
     *
     * @since 7/14/05
     */
    public function remainder(Number $aNumber)
    {
        $temp = $this->remIntegerQuotient($aNumber);

        return $this->minus($temp->multipliedBy($aNumber));
    }

    /**
     * Answer 1 divided by the receiver. Create an error notification if the
     * receiver is 0.
     *
     * @return Number
     *
     * @since 7/14/05
     */
    public function reciprical()
    {
        if ($this->isEqualTo(Integer::withValue(0))) {
            throwError(new Error('Division by zero'));
        } else {
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
     * @return Number
     *
     * @since 7/14/05
     */
    public function ceiling()
    {
        if ($this->isLessThanOrEqualTo(Integer::zero())) {
            return $this->truncated();
        } else {
            $temp = $this->negated();
            $temp = $temp->floor();

            return $temp->negated();
        }
    }

    /**
     * Answer the integer nearest the receiver toward negative infinity.
     *
     * @return Number
     *
     * @since 7/14/05
     */
    public function floor()
    {
        $truncation = $this->truncated();
        if ($this->isGreaterThanOrEqualTo(Integer::zero())) {
            return $truncation;
        } else {
            if ($this->isEqualTo($truncation)) {
                return $truncation;
            } else {
                return $truncation->minus(Integer::withValue(1));
            }
        }
    }

    /**
     * Answer an integer nearest the receiver toward zero.
     *
     * @return Number
     *
     * @since 7/14/05
     */
    public function truncated()
    {
        return $this->remIntegerQuotient(Integer::withValue(1));
    }

    /*********************************************************
     * Instance Methods - Converting
     *********************************************************/

    /**
     * Answer a double-precision floating-point number approximating the receiver.
     *
     * @return float
     *
     * @since 7/14/05
     */
    public function asDouble()
    {
        $obj = Double::withValue((float) $this->value());

        return $obj;
    }

    /**
     * Answer a floating-point number approximating the receiver.
     *
     * @return float
     *
     * @since 7/14/05
     */
    public function asFloat()
    {
        $obj = Float::withValue((float) $this->value());

        return $obj;
    }

    /**
     * Answer an Integer nearest the receiver toward zero.
     *
     * @return int
     *
     * @since 7/14/05
     */
    public function asInteger()
    {
        return $this->truncated();
    }

    /**
     * Answer a number.
     *
     * @return Number
     *
     * @since 7/14/05
     */
    public function asNumber()
    {
        return $this;
    }

    /*********************************************************
     * Instance Methods - Comparing
     *********************************************************/

    /**
     * Test if this is less than aMagnitude.
     *
     * @return bool
     *
     * @since 5/4/05
     */
    public function isLessThan(Magnitude $aMagnitude)
    {
        if (!method_exists($aMagnitude, 'asFloat')) {
            return false;
        }

        $asFloat = $aMagnitude->asFloat();

        return ($this->value() < $asFloat->value()) ? true : false;
    }

    /**
     * Answer whether the receiver and the argument are the same.
     * If = is redefined in any subclass, consider also redefining the
     * message hash.
     *
     * @param $anObject
     *
     * @return bool
     *
     * @since 7/11/05
     */
    public function isEqualTo($anObject)
    {
        if (!method_exists($anObject, 'asFloat')) {
            return false;
        }

        $asFloat = $anObject->asFloat();

        return ($this->value() == $asFloat->value()) ? true : false;
    }

    /*********************************************************
     * Instance Methods - Printing/Accessing
     *********************************************************/

    /**
     * Answer a String whose characters are a description of the receiver.
     * Override this method as needed to provide a better representation.
     *
     * @return string
     *
     * @since 7/11/05
     */
    public function printableString()
    {
        return (string) $this->value();
    }

    /**
     * Answer the PHP primitive value of the reciever.
     *
     * @return mixed may be an int, float, double, etcetera
     *
     * @since 7/14/05
     */
    public function value()
    {
        return $this->value;
    }

    /*********************************************************
     * Instance Methods - Private
     *********************************************************/

    /**
     * Set the internal value to a PHP primitive.
     *
     * @return void
     *
     * @since 7/14/05
     */
    protected function _setValue($value)
    {
        $this->value = $this->cast($value);
    }

    /**
     * Cast an input value so that it is of the appropriate storage type.
     */
    protected function cast($value)
    {
        return $value;
    }
}
