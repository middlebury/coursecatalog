<?php

/**
 * @since 7/14/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Integer.class.php,v 1.7 2007/10/10 22:58:34 adamfranco Exp $
 */

require_once __DIR__.'/Number.class.php';

/**
 * A simple Integer data type.
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Integer.class.php,v 1.7 2007/10/10 22:58:34 adamfranco Exp $
 */
class Integer extends Number
{
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
    public function plus(Number $aNumber)
    {
        if ($aNumber instanceof Integer) {
            return static::withValue($this->value() + $aNumber->value());
        } else {
            return Float::withValue($this->value() + $aNumber->value());
        }
    }

    /**
     * Answer the result of multiplying the receiver and aNumber.
     *
     * @return Number
     *
     * @since 7/14/05
     */
    public function multipliedBy(Number $aNumber)
    {
        if ($aNumber instanceof Integer) {
            return static::withValue($this->value() * $aNumber->value());
        } else {
            return Float::withValue($this->value() * $aNumber->value());
        }
    }

    /**
     * Answer the result of dividing the receiver and aNumber.
     *
     * @return Number
     *
     * @since 7/14/05
     */
    public function dividedBy(Number $aNumber)
    {
        return Float::withValue($this->value() / $aNumber->value());
    }

    /*********************************************************
     * Instance Methods - Private
     *********************************************************/

    /**
     * Cast an input value so that it is of the appropriate storage type.
     */
    protected function cast($value)
    {
        return (int) $value;
    }
}
