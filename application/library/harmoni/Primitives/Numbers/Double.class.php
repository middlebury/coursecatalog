<?php

/**
 * @since 7/14/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Double.class.php,v 1.6 2007/10/10 22:58:34 adamfranco Exp $
 */

require_once dirname(__FILE__).'/Float.class.php';

/**
 * A simple Float data type.
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Double.class.php,v 1.6 2007/10/10 22:58:34 adamfranco Exp $
 */
class Double extends Float
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
     * @return object Double
     *
     * @since 7/14/05
     *
     * @static
     */
    public static function withValue($value, $class = 'Double')
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
    public static function fromString($string, $class = 'Double')
    {
        return parent::fromString($string, $class);
    }

    /**
     * Answer a new object with the value zero.
     *
     * @param optional string $class The class to instantiate. Do NOT use outside
     *		of this package.
     *
     * @return object Double
     *
     * @static
     *
     * @since 7/14/05
     */
    public static function zero($class = 'Double')
    {
        return parent::zero($class);
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
    public function _setValue($value)
    {
        $this->_value = doubleval($value);
    }
}
