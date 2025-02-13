<?php

/**
 * @since 5/2/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Duration.class.php,v 1.6 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */

require_once __DIR__.'/ChronologyConstants.class.php';
require_once __DIR__.'/../Magnitudes/Magnitude.class.php';

/**
 * I represent a duration of time. I have been tested to support durations of
 * up to 4 billion (4,000,000,000) years with second precision and up to
 * 50 billion (50,000,000) years with hour precision. Durations beyond 50 billion
 * years have not been tested.
 *
 * To create new Duration instances, <b>use one of the static instance-creation
 * methods</b>, NOT 'new Duration':
 *		- {@link fromString Duration::fromString($aString)}
 *		- {@link fromString Duration::fromString($aString)}
 *		- {@link withDays Duration::withDays($days)}
 *		- {@link withDaysHoursMinutesSeconds Duration::withDaysHoursMinutesSeconds($days,
 *					$hours, $minutes, $seconds)}
 *		- {@link withHours Duration::withHours($hours)}
 *		- {@link withMinutes Duration::withMinutes($minutes)}
 *		- {@link withMonth Duration::withMonth($anIntOrStrMonth)}
 *		- {@link withSeconds Duration::withSeconds($seconds)}
 *		- {@link withWeeks Duration::withWeeks($weeks)}
 *		- {@link zero Duration::zero()}
 *
 * @since 5/2/05
 *
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Duration.class.php,v 1.6 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @see http://harmoni.sourceforge.net/
 *
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class Duration extends Magnitude
{
    /*********************************************************
     * Class methods - Instance Creation
     *********************************************************/

    /**
     * Formatted as per ANSI 5.8.2.16: [-]D:HH:MM:SS[.S].
     *
     * @param string $aString
     *
     * @return object Duration
     *
     * @since 5/13/05
     *
     * @static
     */
    public static function fromString($aString)
    {
        $parser = new ANSI58216StringParser($aString);

        if (!is_string($aString) || !preg_match('/[^\W]/', $aString) || !$parser) {
            return null;
        }

        return static::withDaysHoursMinutesSeconds($parser->day(), $parser->hour(), $parser->minute(), $parser->second());
    }

    /**
     * Create a new instance of days...
     *
     * @param int $days
     *
     * @return object Duration
     *
     * @static
     *
     * @since 5/3/05
     */
    public static function withDays($days)
    {
        return static::withDaysHoursMinutesSeconds($days, 0, 0, 0);
    }

    /**
     * Create a new instance with.
     *
     * @param int $days
     * @param int $hours
     * @param int $minutes
     * @param int $seconds
     *
     * @return object Duration
     *
     * @static
     *
     * @since 5/3/05
     */
    public static function withDaysHoursMinutesSeconds($days, $hours, $minutes, $seconds)
    {
        return new static(
            ($days * ChronologyConstants::SecondsInDay())
            + ($hours * ChronologyConstants::SecondsInHour())
            + ($minutes * ChronologyConstants::SecondsInMinute())
            + $seconds);
    }

    /**
     * Create a new Duration of hours...
     *
     * @param int $hours
     *
     * @return object Duration
     *
     * @static
     *
     * @since 5/3/05
     */
    public static function withHours($hours)
    {
        return static::withDaysHoursMinutesSeconds(0, $hours, 0, 0);
    }

    /**
     * Create a new instance of minutes...
     *
     * @param int $minutes
     *
     * @return object Duration
     *
     * @static
     *
     * @since 5/3/05
     */
    public static function withMinutes($minutes)
    {
        return static::withDaysHoursMinutesSeconds(0, 0, $minutes, 0);
    }

    /**
     * Create a new instance. aMonth is an Integer or a String.
     *
     * @param string $anIntOrStrMonth
     *
     * @return object Duration
     *
     * @since 5/13/05
     *
     * @static
     */
    public static function withMonth($anIntOrStrMonth)
    {
        $currentYear = Year::current();
        $month = Month::withMonthYear($anIntOrStrMonth, $currentYear->startYear());

        return $month->duration();
    }

    /**
     * Create a new instance of seconds...
     *
     * @param int $seconds
     *
     * @return object Duration
     *
     * @static
     *
     * @since 5/3/05
     */
    public static function withSeconds($seconds)
    {
        return static::withDaysHoursMinutesSeconds(0, 0, 0, $seconds);
    }

    /**
     * Create a new instance of a number of weeks.
     *
     * @param float $aNumber
     *
     * @return object Duration
     *
     * @since 5/13/05
     *
     * @static
     */
    public static function withWeeks($aNumber)
    {
        return static::withDaysHoursMinutesSeconds($aNumber * 7, 0, 0, 0);
    }

    /**
     * Create a new Duration of zero length.
     *
     * @return object Duration
     *
     * @since 5/5/05
     *
     * @static
     */
    public static function zero()
    {
        return static::withDays(0);
    }

    /*********************************************************
     * 	Instance methods - Private
     *********************************************************/
    private int $seconds;

    /**
     * Initialize this Duration.
     *
     * @param int seconds
     *
     * @return object Duration
     *
     * @since 5/3/05
     */
    public function __construct($seconds = 0)
    {
        $this->seconds = $seconds;
    }

    /**
     * Answer an array {days. seconds. nanoSeconds}. Used by DateAndTime and Time.
     *
     * @return array
     *
     * @since 5/2/05
     */
    public function ticks()
    {
        return [
            $this->days(),
            ($this->hours() * 3600) + ($this->minutes() * 60) + floor($this->seconds()),
        ];
    }

    /*********************************************************
     * Instance methods - Accessing
     *********************************************************/

    /**
     * Answer the number of days the receiver represents.
     *
     * @return int
     *
     * @since 5/3/05
     */
    public function days()
    {
        if ($this->isPositive()) {
            return floor($this->seconds / ChronologyConstants::SecondsInDay());
        } else {
            return 0 - floor(abs($this->seconds) / ChronologyConstants::SecondsInDay());
        }
    }

    /**
     * Answer the number of hours the receiver represents.
     *
     * @return int
     *
     * @since 5/3/05
     */
    public function hours()
    {
        // Above 2^31 seconds, (amost exactly 100 years), PHP converts the
        // variable from an integer to a float to allow it to grow larger.
        // While addition and subraction work fine with floats, float modulos
        // and divisions loose precision. This precision loss does not affect
        // the proper value of days up to the maximum duration tested, 50billion
        // years.
        if (abs($this->seconds) > 2 ** 31) {
            $remainderDuration = $this->minus(self::withDays($this->days()));

            return $remainderDuration->hours();
        } else {
            if (!$this->isNegative()) {
                return floor(
                    ($this->seconds % ChronologyConstants::SecondsInDay())
                    / ChronologyConstants::SecondsInHour());
            } else {
                return 0 - floor(
                    (abs($this->seconds) % ChronologyConstants::SecondsInDay())
                    / ChronologyConstants::SecondsInHour());
            }
        }
    }

    /**
     * Answer the number of minutes the receiver represents.
     *
     * @return int
     *
     * @since 5/3/05
     */
    public function minutes()
    {
        // Above 2^31 seconds, (amost exactly 100 years), PHP converts the
        // variable from an integer to a float to allow it to grow larger.
        // While addition and subraction work fine with floats, float modulos
        // and divisions loose precision. This precision loss does not affect
        // the proper value of days up to the maximum duration tested, 50billion
        // years.
        if (abs($this->seconds) > 2 ** 31) {
            $remainderDuration = $this->minus(self::withDays($this->days()));

            return $remainderDuration->minutes();
        } else {
            if (!$this->isNegative()) {
                return floor(
                    ($this->seconds % ChronologyConstants::SecondsInHour())
                    / ChronologyConstants::SecondsInMinute());
            } else {
                return 0 - floor(
                    (abs($this->seconds) % ChronologyConstants::SecondsInHour())
                    / ChronologyConstants::SecondsInMinute());
            }
        }
    }

    /**
     * Format as per ANSI 5.8.2.16: [-]D:HH:MM:SS[.S].
     *
     * @return string
     *
     * @since 5/3/05
     */
    public function printableString()
    {
        $result = '';

        if ($this->isNegative()) {
            $result .= '-';
        }

        $result .= abs($this->days()).':';
        $result .= str_pad(abs($this->hours()), 2, '0', \STR_PAD_LEFT).':';
        $result .= str_pad(abs($this->minutes()), 2, '0', \STR_PAD_LEFT).':';
        $result .= str_pad(abs($this->seconds()), 2, '0', \STR_PAD_LEFT);

        return $result;
    }

    /**
     * Answer the number of seconds the receiver represents.
     *
     * @return int
     *
     * @since 5/3/05
     */
    public function seconds()
    {
        // Above 2^31 seconds, (amost exactly 100 years), PHP converts the
        // variable from an integer to a float to allow it to grow larger.
        // While addition and subraction work fine with floats, float modulos
        // and divisions loose precision. This precision loss does not affect
        // the proper value of days up to the maximum duration tested, 50billion
        // years.
        if (abs($this->seconds) > 2 ** 31) {
            $remainderDuration = $this->minus(self::withDays($this->days()));

            return $remainderDuration->seconds();
        } else {
            if ($this->isPositive()) {
                return floor($this->seconds % ChronologyConstants::SecondsInMinute());
            } else {
                return 0 - floor(
                    abs($this->seconds) % ChronologyConstants::SecondsInMinute());
            }
        }
    }

    /*********************************************************
     * Instance methods - Comparing/Testing
     *********************************************************/

    /**
     * Return true if this Duration is negative.
     *
     * @return bool
     *
     * @since 5/3/05
     */
    public function isNegative()
    {
        return $this->asSeconds() < 0;
    }

    /**
     * Return true if this Duration is positive.
     *
     * @return bool
     *
     * @since 5/3/05
     */
    public function isPositive()
    {
        return !$this->isNegative();
    }

    /**
     * Test if this Duration is equal to aDuration.
     *
     * @param object Duration $aDuration
     *
     * @return bool
     *
     * @since 5/3/05
     */
    public function isEqualTo($aDuration)
    {
        return $this->asSeconds() == $aDuration->asSeconds();
    }

    /**
     * Test if this Duration is less than aDuration.
     *
     * @param object Duration $aDuration
     *
     * @return bool
     *
     * @since 5/3/05
     */
    public function isLessThan($aDuration)
    {
        return $this->asSeconds() < $aDuration->asSeconds();
    }

    /*********************************************************
     * Instance methods - Operations
     *********************************************************/

    /**
     * Return the absolute value of this duration.
     *
     * @return object Duration
     *
     * @since 5/3/05
     */
    public function abs()
    {
        $obj = new self(abs($this->seconds));

        return $obj;
    }

    /**
     * Divide a Duration. Operand is a Duration or a Number.
     *
     * @param object Duration $aDuration
     *
     * @return object Duration The result
     *
     * @since 5/12/05
     */
    public function dividedBy($operand)
    {
        if (is_numeric($operand)) {
            $obj = new self((int) ($this->asSeconds() / $operand));

            return $obj;
        } else {
            $denominator = $operand->asDuration();
            $obj = new self((int) ($this->asSeconds() / $denominator->asSeconds()));

            return $obj;
        }
    }

    /**
     * Subtract a Duration.
     *
     * @param object Duration $aDuration
     *
     * @return object Duration The result
     *
     * @since 5/3/05
     */
    public function minus($aDuration)
    {
        $obj = $this->plus($aDuration->negated());

        return $obj;
    }

    /**
     * Multiply a Duration. Operand is a Duration or a Number.
     *
     * @param object Duration $aDuration
     *
     * @return object Duration The result
     *
     * @since 5/12/05
     */
    public function multipliedBy($operand)
    {
        if (is_numeric($operand)) {
            $obj = new self((int) ($this->asSeconds() * $operand));

            return $obj;
        } else {
            $duration = $operand->asDuration();
            $obj = new self((int) ($this->asSeconds() * $duration->asSeconds()));

            return $obj;
        }
    }

    /**
     * Return the negative of this duration.
     *
     * @return object Duration
     *
     * @since 5/10/05
     */
    public function negated()
    {
        $obj = new self(0 - $this->seconds);

        return $obj;
    }

    /**
     * Add a Duration.
     *
     * @param object Duration $aDuration
     *
     * @return object duration The result
     *
     * @since 5/3/05
     */
    public function plus($aDuration)
    {
        $obj = new self($this->asSeconds() + $aDuration->asSeconds());

        return $obj;
    }

    /**
     * Round to a Duration.
     *
     * @param object Duration $aDuration
     *
     * @return object duration The result
     *
     * @since 5/3/05
     */
    public function roundTo($aDuration)
    {
        $obj = new self(
            (int)
                round(
                    $this->asSeconds() / $aDuration->asSeconds())
            * $aDuration->asSeconds());

        return $obj;
    }

    /**
     * Truncate.
     * e.g. if the receiver is 5 minutes, 37 seconds, and aDuration is 2 minutes,
     * answer 4 minutes.
     *
     * @param object Duration $aDuration
     *
     * @return object Duration
     *
     * @since 5/13/05
     */
    public function truncateTo($aDuration)
    {
        $obj = new self(
            (int) ($this->asSeconds() / $aDuration->asSeconds())
            * $aDuration->asSeconds());

        return $obj;
    }

    /*********************************************************
     * Instance methods - Converting
     *********************************************************/

    /**
     * Answer the duration in seconds.
     *
     * @return int
     *
     * @since 5/3/05
     */
    public function asSeconds()
    {
        return $this->seconds;
    }

    /**
     * Answer a Duration that represents this object.
     *
     * @return object Duration
     *
     * @since 5/4/05
     */
    public function asDuration()
    {
        return $this;
    }
}

// Require the StringParser instead of the ANSI58216StringParser directly so
// as to make sure that all classes are included in the appropriate order.
require_once __DIR__.'/StringParser/StringParser.class.php';
