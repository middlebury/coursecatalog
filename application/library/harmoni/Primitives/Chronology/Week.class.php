<?php

/**
 * @since 5/4/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Week.class.php,v 1.7 2007/10/30 16:34:28 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

require_once __DIR__.'/Timespan.class.php';

/**
 * I am a Timespan that represents a Week.
 *
 * To create new Week instances, <b>use one of the static instance-creation
 * methods</b>, NOT 'new Week':
 *		- {@link current Week::current()}
 *		- {@link current Week::current()}
 *		- {@link epoch Week::epoch()}
 *		- {@link starting Week::starting($aDateAndTime)}
 *		- {@link startingDuration Week::startingDuration($aDateAndTime, $aDuration)}
 *		- {@link startingEnding Week::startingEnding($startDateAndTime, $endDateAndTime)}
 *
 * @since 5/4/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Week.class.php,v 1.7 2007/10/30 16:34:28 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class Week extends Timespan
{
    /*********************************************************
     * Class Methods
     *********************************************************/

    /**
     * Return the index of a string Day.
     *
     * @return int
     *
     * @since 5/4/05
     */
    public static function indexOfDay(string $aNameString)
    {
        foreach (ChronologyConstants::DayNames() as $i => $name) {
            if (preg_match("/$aNameString.*/i", $name)) {
                return $i;
            }
        }

        throw new InvalidArgumentException($aNameString.' is not a recognized day name.');
    }

    /**
     * Return the name of the day at index.
     *
     * @return string
     *
     * @since 5/4/05
     */
    public static function nameOfDay(int $anInteger)
    {
        $names = ChronologyConstants::DayNames();
        if ($names[$anInteger]) {
            return $names[$anInteger];
        }

        throw new InvalidArgumentException($anInteger.' is not a valid day index.');
    }

    /**
     * Answer the day at the start of the week.
     *
     * @return string
     *
     * @since 5/20/05
     */
    public static function startDay()
    {
        $dayNames = ChronologyConstants::DayNames();

        return $dayNames[1];
    }

    /*********************************************************
     * Class Methods - Instance Creation
     *********************************************************/

    /**
     * Create a new object starting now, with a given duration.
     * Override - as each Week has a defined duration.
     *
     * @param DateAndTime $aDateAndTime
     *
     * @return Week
     *
     * @since 5/5/05
     *
     * @static
     */
    public static function startingDuration(AsDateAndTime $aDateAndTime, ?Duration $aDuration)
    {
        $asDateAndTime = $aDateAndTime->asDateAndTime();
        $midnight = $asDateAndTime->atMidnight();
        $dayNames = ChronologyConstants::DayNames();
        $temp = $midnight->dayOfWeek() + 7 - array_search(static::startDay(), $dayNames);
        $delta = abs($temp - ((int) ($temp / 7) * 7));

        $adjusted = $midnight->minus(Duration::withDays($delta));

        $week = new static();
        $week->setStart($adjusted);
        $week->setDuration(Duration::withWeeks(1));

        return $week;
    }

    /*********************************************************
     * Instance Methods - Converting
     *********************************************************/

    /**
     * Answer the receiver as a Week.
     *
     * @return Week
     *
     * @since 5/23/05
     */
    public function asWeek()
    {
        return $this;
    }
}
