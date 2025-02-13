<?php

/**
 * @since 5/3/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: TimeZone.class.php,v 1.5 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

require_once __DIR__.'/../Objects/SObject.class.php';

/**
 * TimeZone is a simple class to colect the information identifying a UTC time zone.
 *
 * 	- offset		-	Duration	- the time zone's offset from UTC
 *  - abbreviation	-	String		- the abbreviated name for the time zone.
 *  - name			-	String		- the name of the time zone.
 *
 * TimeZone class >> timeZones() returns an array of the known time zones
 * TimeZone class >> defaultTimeZone() returns the default time zone (Grenwich Mean Time)
 * DateAndTime class >> localTimeZone() returns the local time zone.
 *
 * To create new TimeZone instances, <b>use one of the static instance-creation
 * methods</b>, NOT 'new TimeZone':
 *		- {@link defaultTimeZone TimeZone::defaultTimeZone()}
 *		- {@link defaultTimeZone TimeZone::defaultTimeZone()}
 *		- {@link offsetNameAbbreviation TimeZone::offsetNameAbbreviation($aDuration,
 *					$aStringName, $aStringAbbreviation)}
 *
 * @since 5/3/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: TimeZone.class.php,v 1.5 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class TimeZone extends SObject
{
    /*********************************************************
     * Class Methods - Instance Creation
     *********************************************************/

    /**
     * Answer the default time zone - GMT.
     *
     * @return TimeZone
     *
     * @since 5/3/05
     *
     * @static
     */
    public static function defaultTimeZone()
    {
        return static::offsetNameAbbreviation(
            Duration::withHours(0),
            'Greenwich Mean Time',
            'GMT');
    }

    /**
     * Create a new Timezone.
     *
     * @return TimeZone
     *
     * @static
     *
     * @since 5/3/05
     */
    public static function offsetNameAbbreviation(Duration $aDuration, ?string $aStringName = null,
        $aStringAbbreviation = null)
    {
        return new static($aDuration, $aStringName, $aStringAbbreviation);
    }

    /*********************************************************
     * Class Methods - Accessing
     *********************************************************/

    /**
     * Return an Array of TimeZones.
     *
     * @return array
     *
     * @since 5/3/05
     *
     * @static
     */
    public static function timeZones()
    {
        $array = [
            self::offsetNameAbbreviation(
                Duration::withHours(0),
                'Universal Time',
                'UTC'),
            self::offsetNameAbbreviation(
                Duration::withHours(0),
                'Greenwich Mean Time',
                'GMT'),
            self::offsetNameAbbreviation(
                Duration::withHours(0),
                'British Summer Time',
                'BST'),
            self::offsetNameAbbreviation(
                Duration::withHours(-5),
                'Eastern Standard Time',
                'EST'),
            self::offsetNameAbbreviation(
                Duration::withHours(-4),
                'Eastern Daylight Time',
                'EDT'),
            self::offsetNameAbbreviation(
                Duration::withHours(-6),
                'Central Standard Time',
                'CST'),
            self::offsetNameAbbreviation(
                Duration::withHours(-5),
                'Central Daylight Time',
                'CDT'),
            self::offsetNameAbbreviation(
                Duration::withHours(-7),
                'Mountain Standard Time',
                'MST'),
            self::offsetNameAbbreviation(
                Duration::withHours(-6),
                'Mountain Daylight Time',
                'MDT'),
            self::offsetNameAbbreviation(
                Duration::withHours(-8),
                'Pacific Standard Time',
                'PST'),
            self::offsetNameAbbreviation(
                Duration::withHours(-7),
                'Pacific Daylight Time',
                'PDT'),
        ];

        return $array;
    }

    /*********************************************************
     * 	Instance Methods - private
     *********************************************************/

    private $offset;
    private $name;
    private $abbreviation;

    /**
     * Create a new Timezone.
     *
     * @return TimeZone
     *
     * @since 5/3/05
     */
    public function __construct(Duration $aDuration, string $aStringName, string $aStringAbbreviation)
    {
        $this->offset = $aDuration;
        $this->name = $aStringName;
        $this->abbreviation = $aStringAbbreviation;
    }

    /*********************************************************
     * Instance Methods - Accessing
     *********************************************************/

    /**
     * Return the offset of this TimeZone.
     *
     * @return Duration
     *
     * @since 5/3/05
     */
    public function offset()
    {
        return $this->offset;
    }

    /**
     * Answer the abreviation.
     *
     * @return string
     *
     * @since 5/10/05
     */
    public function abbreviation()
    {
        return $this->abbreviation;
    }

    /**
     * Answer the name.
     *
     * @return string
     *
     * @since 5/10/05
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Answer a string version of this time zone.
     *
     * @return string
     *
     * @since 10/15/08
     */
    public function printableString()
    {
        if ($this->offset->isLessThan(Duration::withSeconds(0))) {
            $string = '-';
        } else {
            $string = '+';
        }

        $string .= str_pad(abs($this->offset->hours()), 2, '0', \STR_PAD_LEFT);
        $string .= ':'.str_pad(abs($this->offset->minutes()), 2, '0', \STR_PAD_LEFT);

        return $string;
    }
}
