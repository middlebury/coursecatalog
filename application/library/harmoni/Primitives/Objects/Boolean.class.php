<?php

require_once __DIR__.'/../Objects/SObject.class.php';

/**
 * A simple Boolean data type.
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Boolean.class.php,v 1.11 2007/10/10 22:58:34 adamfranco Exp $
 */
class Boolean extends SObject
{
    private bool $_bool;

    /*********************************************************
     * Class Methods - Virtual Constructors
     *********************************************************/

    /**
     * Instantiates a new Boolean object with the passed value.
     *
     * @return ref object
     *
     * @static
     */
    public static function withValue(bool $value)
    {
        return new static($value);
    }

    /**
     * Instantiates a new Boolean object from a known string.
     *
     * @param string $aString true(case insensitive) is true if not, it's false
     *
     * @return ref object
     *
     * @since 3/14/06
     *
     * @static
     */
    public static function fromString(string $aString)
    {
        return new static(('true' == strtolower($aString)) ? true : false);
    }

    /**
     * Instantiates a new Boolean object with the value, false.
     *
     * @return bool
     *
     * @since 8/11/05
     *
     * @static
     */
    public static function false()
    {
        return new static(false);
    }

    /**
     * Instantiates a new Boolean object with the value, true.
     *
     * @return bool
     *
     * @since 8/11/05
     *
     * @static
     */
    public static function true()
    {
        return new static(true);
    }

    /*********************************************************
     * Instance Methods
     *********************************************************/

    public function __construct(bool $value = true)
    {
        $this->_bool = (bool) $value;
    }

    /**
     * Returns the boolean value.
     *
     * @return bool
     */
    public function value()
    {
        return $this->_bool;
    }

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
        return $this->_bool ? 'true' : 'false';
    }

    /**
     * Answer whether the receiver and the argument are the same.
     * If = is redefined in any subclass, consider also redefining the
     * message hash.
     *
     * @return bool
     *
     * @since 7/11/05
     */
    public function isEqualTo($anObject)
    {
        if (!method_exists($anObject, 'value')) {
            return false;
        }

        return ($this->_bool === $anObject->value()) ? true : false;
    }

    /**
     * Answer true if this object represents a 'true' value, false otherwise.
     *
     * @return bool
     *
     * @since 9/29/05
     */
    public function isTrue()
    {
        return $this->_bool;
    }

    /**
     * Answer true if this object represents a 'false' value, false otherwise.
     *
     * @return bool
     *
     * @since 9/29/05
     */
    public function isFalse()
    {
        return $this->_bool ? false : true;
    }
}
