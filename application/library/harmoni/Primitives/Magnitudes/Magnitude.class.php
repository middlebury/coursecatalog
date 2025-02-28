<?php

/**
 * @since 5/4/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Magnitude.class.php,v 1.6 2007/10/10 22:58:34 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

require_once __DIR__.'/../Objects/SObject.class.php';

/**
 * Magnitude has methods for dealing with linearly ordered collections.
 *
 * Subclasses represent dates, times, and numbers.
 *
 * Example for interval-testing (answers a Boolean):
 *	$seven->between($five, $ten);
 *
 * No instance-variables.
 *
 * @since 5/4/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Magnitude.class.php,v 1.6 2007/10/10 22:58:34 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
abstract class Magnitude extends SObject
{
    /**
     * Test if this is less than aMagnitude.
     *
     * @return bool
     *
     * @since 5/4/05
     */
    abstract public function isLessThan(Magnitude $aMagnitude);

    /**
     * Test if this is greater than aMagnitude.
     *
     * @return bool
     *
     * @since 5/3/05
     */
    public function isGreaterThan(Magnitude $aMagnitude)
    {
        return $aMagnitude->isLessThan($this);
    }

    /**
     * Test if this is greater than aMagnitude.
     *
     * @return bool
     *
     * @since 5/3/05
     */
    public function isLessThanOrEqualTo(Magnitude $aMagnitude)
    {
        return !$this->isGreaterThan($aMagnitude);
    }

    /**
     * Test if this is greater than aMagnitude.
     *
     * @return bool
     *
     * @since 5/3/05
     */
    public function isGreaterThanOrEqualTo(Magnitude $aMagnitude)
    {
        return !$this->isLessThan($aMagnitude);
    }

    /**
     * Answer whether the receiver is less than or equal to the argument, max,
     * and greater than or equal to the argument, min.
     *
     * @return bool
     *
     * @since 5/4/05
     */
    public function isBetween(Magnitude $min, Magnitude $max)
    {
        return $this->isGreaterThanOrEqualTo($min) && $this->isLessThanOrEqualTo($max);
    }

    /**
     * Answer the receiver or the argument, whichever has the greater
     * magnitude.
     *
     * @return Magnitude
     *
     * @since 5/4/05
     */
    public function max(Magnitude $aMagnitude)
    {
        if ($this->isGreaterThan($aMagnitude)) {
            return $this;
        } else {
            return $aMagnitude;
        }
    }

    /**
     * Answer the receiver or the argument, whichever has the lesser
     * magnitude.
     *
     * @return Magnitude
     *
     * @since 5/4/05
     */
    public function min(Magnitude $aMagnitude)
    {
        if ($this->isLessThan($aMagnitude)) {
            return $this;
        } else {
            return $aMagnitude;
        }
    }
}
