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
        return static::withValue($this->value() + $aNumber->value());
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
        return static::withValue($this->value() * $aNumber->value());
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
        return static::withValue($this->value() / $aNumber->value());
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
