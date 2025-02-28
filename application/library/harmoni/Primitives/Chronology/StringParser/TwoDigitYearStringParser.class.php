<?php

/**
 * @since 5/24/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: TwoDigitYearStringParser.class.php,v 1.2 2006/06/26 12:55:08 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

require_once __DIR__.'/StringParser.class.php';
// require_once(dirname(__FILE__)."/RegexStringParser.class.php");

/**
 * This class implements a method for converting from a two-digit year, such as
 * '82' to the four-digit '1982' equivalent. Due to the lack of information
 * contained in a two-digit year, the only valid years are 1970-2069.
 *
 * This class extends RegexStringParser only as a matter of convenience as its
 * decendents at the time of this refactoring all were decendents of
 * RegexStringParser.
 *
 * @since 5/24/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: TwoDigitYearStringParser.class.php,v 1.2 2006/06/26 12:55:08 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
abstract class TwoDigitYearStringParser extends RegexStringParser
{
    /*********************************************************
     * Instance Methods - Setting (private
     *********************************************************/

    /**
     * Set the year.
     *
     * @return void
     *
     * @since 5/24/05
     */
    public function setYear(int $anInteger)
    {
        if ($anInteger > 70 && $anInteger < 100) {
            $this->year = (int) (1900 + $anInteger);
        } elseif ($anInteger < 100) {
            $this->year = (int) (2000 + $anInteger);
        } else {
            $this->year = $anInteger;
        }
    }
}
