<?php

/**
 * A simple Blob data type.
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Blob.class.php,v 1.9 2007/10/10 22:58:33 adamfranco Exp $
 */
class Blob extends String
{
    public function __construct($string = '')
    {
        $this->_string = $string;
    }

    /**
     * Instantiates a new Blob object with the passed value.
     *
     * @param string $value
     *
     * @return ref object
     *
     * @static
     */
    public static function withValue($value)
    {
        $string = new self($value);

        return $string;
    }

    /**
     * Instantiates a new Blob object with the passed value.
     *
     * allowing 'fromString' instantiation
     *
     * @param string $aString
     *
     * @return ref object
     *
     * @static
     */
    public static function fromString($aString)
    {
        $string = new self($aString);

        return $string;
    }

    public function value()
    {
        return $this->_string;
    }
}
