<?php

/**
 * A simple Blob data type.
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Blob.class.php,v 1.9 2007/10/10 22:58:33 adamfranco Exp $
 */
class Blob extends HarmoniString
{
    public function __construct($string = '')
    {
        $this->_string = $string;
    }

    public function value()
    {
        return $this->_string;
    }
}
