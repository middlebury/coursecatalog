<?php

/**
 * @since 7/14/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Float.class.php,v 1.7 2007/10/10 22:58:34 adamfranco Exp $
 */

require_once __DIR__.'/Number.class.php';

/**
 * A simple Float data type.
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Float.class.php,v 1.7 2007/10/10 22:58:34 adamfranco Exp $
 */
class Float extends Number
{
    /*********************************************************
     * Class Methods - Instance Creation
     *********************************************************/

    /**
     * Answer a new object with the value specified.
     *
     * @param optional string $class The class to instantiate. Do NOT use outside
     *		of this package.
     *
     * @return object Float
     *
     * @since 7/14/05
     *
     * @static
     */
    public static function withValue($value, $class = 'Float')
    {
        return parent::withValue($value, $class);
    }

    /**
     * Answer a new object with the value specified.
     *
     * @param string $string a string representation of the object
     *
     * @return object Double
     *
     * @since 3/14/06
     *
     * @static
     */
    public static function fromString($string, $class = 'Float')
    {
        return parent::fromString($string, $class);
    }

    /**
     * Answer a new object with the value zero.
     *
     * @param optional string $class The class to instantiate. Do NOT use outside
     *		of this package.
     *
     * @return object Float
     *
     * @since 7/14/05
     *
     * @static
     */
    public static function zero($class = 'Float')
    {
        return parent::zero($class);
    }

    /*********************************************************
     * Instance Methods - Arithmatic
     *********************************************************/

    /**
     * Answer the sum of the receiver and aNumber.
     *
     * @param object Number $aNumber
     *
     * @return object Number
     *
     * @since 7/14/05
     */
    public function plus($aNumber)
    {
        $class = static::class;
        eval('$obj = '.$class.'::withValue($this->value() + $aNumber->value());');

        return $obj;
    }

    /**
     * Answer the result of multiplying the receiver and aNumber.
     *
     * @param object Number $aNumber
     *
     * @return object Number
     *
     * @since 7/14/05
     */
    public function multipliedBy($aNumber)
    {
        $class = static::class;
        eval('$obj = '.$class.'::withValue($this->value() * $aNumber->value());');

        return $obj;
    }

    /**
     * Answer the result of dividing the receiver and aNumber.
     *
     * @param object Number $aNumber
     *
     * @return object Number
     *
     * @since 7/14/05
     */
    public function dividedBy($aNumber)
    {
        $class = static::class;
        eval('$obj = '.$class.'::withValue($this->value() / $aNumber->value());');

        return $obj;
    }

    /*********************************************************
     * Instance Methods - Private
     *********************************************************/

    /**
     * Cast an input value so that it is of the appropriate storage type.
     */
    protected function cast($value)
    {
        return (float) $value;
    }
}
