<?php

/**
 * @since 7/14/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ByteSize.class.php,v 1.6 2007/10/10 22:58:34 adamfranco Exp $
 */

require_once __DIR__.'/Integer.class.php';

/**
 * A representation of a Byte size. Provides easy conversion between B, KB, MB, etc
 * as well as a pretty string reprentation.
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: ByteSize.class.php,v 1.6 2007/10/10 22:58:34 adamfranco Exp $
 */
class ByteSize extends Integer
{
    /*********************************************************
     * Class Methods
     *********************************************************/

    /**
     * Answer the string suffix for the desired muliple of 2^10 bytes
     * i.e. 0 -> B, 10 -> kB, 20 -> MB, 30 -> GB, etc.
     *
     * @param int $power A multiple of 10; Range, 0-80
     *
     * @return string
     *
     * @since 10/11/05
     *
     * @static
     */
    public static function suffixForPower(int $power)
    {
        $multiple = (int) ($power / 10);
        if ($multiple < 0 || $multiple > 8) {
            throw new InvalidArgumentException("Invalid power, $power. Valid values are multiples of ten, 0-80.");
        }
        $suffixes = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        return $suffixes[$multiple];
    }

    /*********************************************************
     * Class Methods - Instance Creation
     *********************************************************/

    /**
     * Answer a new object with the string value specified.
     *
     * @param string $stringValue String representation of the size
     *
     * @return ByteSize
     *
     * @static
     *
     * @since 7/14/05
     */
    public static function fromString(string $stringValue)
    {
        if (preg_match("/([0-9\.]+)\s*(B|k|kB|M|MB|G|GB|T|TB|P|PB|E|EB|Z|ZB|Y|YB)\s*$/i",
            $stringValue, $matches)) {
            $suffix = strtoupper($matches[2]);

            $suffixes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
            if (in_array($suffix, $suffixes)) {
                $bytes = $matches[1] * 2 ** (10 * array_search($suffix, $suffixes));
            } else {
                $suffixes = ['', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y'];
                $bytes = $matches[1] * 2 ** (10 * array_search($suffix, $suffixes));
            }
        } elseif (preg_match('/^[0-9]+$/', $stringValue)) {
            $bytes = $stringValue * 1;
        } else {
            throw new InvalidArgumentException("Format '$stringValue' not recognized.");
        }

        return static::withValue($bytes);
    }

    /*********************************************************
     * Instance Methods - Printing
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
        for ($i = 0; $i <= 80; $i += 10) {
            if ($this->value() < 2 ** ($i + 10)) {
                break;
            }
        }

        if (0 == $i) {
            $numString = $this->multipleOfPowerOf2($i);
        } else {
            $numString = sprintf('%01.2f', $this->multipleOfPowerOf2($i));
        }

        return $numString.' '.$this->suffixForPower($i);
    }

    /**
     * Answer the string in kilo bytes (kB).
     *
     * @return string
     *
     * @since 10/11/05
     */
    public function asKBString()
    {
        return round($this->multipleOfPowerOf2(10), 2).$this->suffixForPower(10);
    }

    /**
     * Answer the string in mega bytes (MB).
     *
     * @return string
     *
     * @since 10/11/05
     */
    public function asMBString()
    {
        return round($this->multipleOfPowerOf2(20), 2).$this->suffixForPower(20);
    }

    /*********************************************************
     * Instance Methods - Accessing
     *********************************************************/

    /**
     * Answer the PHP primitive value of the reciever.
     * We need to store our internal representation as a float to allow for
     * very large integers, but we never want to return a decimal number of
     * bytes.
     *
     * @return int
     *
     * @since 7/14/05
     */
    public function value()
    {
        return round(parent::value(), 0);
    }

    /**
     * Answer the value as a multiple of 2^$power.
     * Ex: for the number in kilo bytes (in computer-terms), $power = 10;.
     *
     * @param int $power 0 for bytes, 10 for kilobyes, 20 for megabytes, etc
     *
     * @return float
     *
     * @since 10/11/05
     */
    public function multipleOfPowerOf2(int $power)
    {
        return $this->value() / 2 ** $power;
    }

    /**
     * Answer the value in Kilo Bytes KB.
     *
     * @return int
     *
     * @since 10/11/05
     */
    public function kiloBytes()
    {
        return $this->multipleOfPowerOf2(10);
    }

    /**
     * Answer the value in Mega Bytes (MB).
     *
     * @return int
     *
     * @since 10/11/05
     */
    public function megaBytes()
    {
        return $this->multipleOfPowerOf2(20);
    }

    /*********************************************************
     * Instance Methods - Private
     *********************************************************/

    /**
     * Cast an input value so that it is of the appropriate storage type.
     */
    protected function cast($value)
    {
        return $value;
    }
}
