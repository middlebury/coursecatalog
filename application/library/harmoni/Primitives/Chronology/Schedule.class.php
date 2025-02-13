<?php

/**
 * @since 5/25/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Schedule.class.php,v 1.5 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

require_once __DIR__.'/Timespan.class.php';

/**
 * I represent a powerful class for implementing recurring schedules.
 *
 * To create new Schedule instances, <b>use one of the static instance-creation
 * methods</b>, NOT 'new Schedule':
 *		- {@link current Schedule::current()}
 *		- {@link current Schedule::current()}
 *		- {@link epoch Schedule::epoch()}
 *		- {@link starting Schedule::starting($aDateAndTime)}
 *		- {@link startingDuration Schedule::startingDuration($aDateAndTime, $aDuration)}
 *		- {@link startingEnding Schedule::startingEnding($startDateAndTime, $endDateAndTime)}
 *
 * @since 5/25/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Schedule.class.php,v 1.5 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class Schedule extends Timespan
{
    /*********************************************************
     * Instance methods - Enumerating
     *********************************************************/

    /**
     * Return an array of the DateAndTimes scheduled between aStart and anEnd.
     *
     * @param object $aStart
     * @param object $anEnd
     *
     * @return array Of DateAndTime objects
     *
     * @since 5/25/05
     */
    public function between($aStart, $anEnd)
    {
        $results = [];
        $end = $anEnd->min($this->end());

        // iterate to the first element in the range
        $element = $this->start();
        $i = 0;
        while ($element->isLessThan($aStart)) {
            $element = $element->plus($this->schedule[$i]);
            ++$i;
            if ($i >= count($this->schedule)) {
                $i = 0;
            }
        }

        // Reset our schedule index to the first one.
        // This is the way it is implemented in Squeak, though I'm not sure why.
        $i = 0;

        // Collect the results
        while ($element->isLessThanOrEqualTo($anEnd)) {
            $results[] = $element;

            $element = $element->plus($this->schedule[$i]);
            ++$i;
            if ($i >= count($this->schedule)) {
                $i = 0;
            }
        }

        return $results;
    }

    /**
     * Answer the DateAndTimes scheduled over the reciever's entire duration.
     *
     * @return Of DateAndTime objects
     *
     * @since 5/25/05
     */
    public function dateAndTimes()
    {
        $obj = $this->between($this->start, $this->end());

        return $obj;
    }

    /**
     * Set the schedule.
     *
     * @param array $anArrayOfDurations
     *
     * @return void
     *
     * @since 5/25/05
     */
    public function setSchedule($anArrayOfDurations)
    {
        $this->schedule = $anArrayOfDurations;
    }

    /**
     * Get the schedule elements.
     *
     * @return array $anArrayOfDurations
     *
     * @since 5/25/05
     */
    public function getSchedule()
    {
        return $this->schedule;
    }
}
