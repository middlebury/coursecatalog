<?php
/**
 * @since 5/2/05
 * @package harmoni.primitives.chronology
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Duration.class.php,v 1.6 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */ 
 
require_once(dirname(__FILE__)."/ChronologyConstants.class.php");
require_once(dirname(__FILE__)."/../Magnitudes/Magnitude.class.php");

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
 * @package harmoni.primitives.chronology
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Duration.class.php,v 1.6 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class Duration 
	extends Magnitude
{
	
/*********************************************************
 * Class methods - Instance Creation
 *********************************************************/
 	
 	/**
 	 * Formatted as per ANSI 5.8.2.16: [-]D:HH:MM:SS[.S]
 	 * 
 	 * @param string $aString
 	 * @return object Duration
 	 * @access public
 	 * @since 5/13/05
 	 * @static
 	 */
 	static function fromString ( $aString ) {
 		$parser = new ANSI58216StringParser ($aString);
 		
		if (!is_string($aString) || !preg_match('/[^\W]/', $aString) || !$parser) {
 			$null = null;
 			return $null;
			// die("'".$aString."' is not in a valid format.");
		}
		
		$obj = Duration::withDaysHoursMinutesSeconds(
					$parser->day(), $parser->hour(), $parser->minute(), $parser->second());
		return $obj;
 	}
 	
	/**
	 * Create a new instance of days...
	 * 
	 * @param integer $days
	 * @return object Duration
	 * @access public
	 * @static
	 * @since 5/3/05
	 */
	static function withDays ( $days ) {
		$obj = Duration::withDaysHoursMinutesSeconds ( $days, 0, 0, 0 );
		return $obj;
	}
	
	/**
	 * Create a new instance with.
	 * 
	 * @param integer $days
	 * @param integer $hours
	 * @param integer $minutes
	 * @param integer $seconds
	 * @return object Duration
	 * @access public
	 * @static
	 * @since 5/3/05
	 */
	static function withDaysHoursMinutesSeconds ( $days, $hours, $minutes, $seconds ) {
		$obj = new Duration (
			  ($days * ChronologyConstants::SecondsInDay())
			+ ($hours * ChronologyConstants::SecondsInHour())
			+ ($minutes * ChronologyConstants::SecondsInMinute())
			+ $seconds);
		
		return $obj;
	}
	
	/**
	 * Create a new Duration of hours...
	 * 
	 * @param integer $hours
	 * @return object Duration
	 * @access public
	 * @static
	 * @since 5/3/05
	 */
	static function withHours ( $hours ) {
		$obj = Duration::withDaysHoursMinutesSeconds ( 0, $hours, 0, 0 );
		return $obj;
	}
	
	/**
	 * Create a new instance of minutes...
	 * 
	 * @param integer $minutes
	 * @return object Duration
	 * @access public
	 * @static
	 * @since 5/3/05
	 */
	static function withMinutes ( $minutes ) {
		$obj = Duration::withDaysHoursMinutesSeconds ( 0, 0, $minutes, 0 );
		return $obj;
	}
	
	/**
	 * Create a new instance. aMonth is an Integer or a String
	 * 
	 * @param string $anIntOrStrMonth
	 * @return object Duration
	 * @access public
	 * @since 5/13/05
	 * @static
	 */
	static function withMonth ( $anIntOrStrMonth ) {
		$currentYear = Year::current();
		$month = Month::withMonthYear($anIntOrStrMonth, $currentYear->startYear());
		$obj =$month->duration();
		return $obj;
	}
	
	/**
	 * Create a new instance of seconds...
	 * 
	 * @param integer $seconds
	 * @return object Duration
	 * @access public
	 * @static
	 * @since 5/3/05
	 */
	static function withSeconds ( $seconds ) {
		$obj = Duration::withDaysHoursMinutesSeconds ( 0, 0, 0, $seconds );
		return $obj;
	}
	
	/**
	 * Create a new instance of a number of weeks
	 * 
	 * @param float $aNumber
	 * @return object Duration
	 * @access public
	 * @since 5/13/05
	 * @static
	 */
	static function withWeeks ( $aNumber ) {
		$obj = Duration::withDaysHoursMinutesSeconds(($aNumber * 7), 0, 0, 0);
		return $obj;
	}
	
	/**
 	 * Create a new Duration of zero length
 	 * 
 	 * @return object Duration
 	 * @access public
 	 * @since 5/5/05
 	 * @static
 	 */
 	static function zero () {
 		$obj = Duration::withDays(0);
		return $obj;
 	}
	
	
/*********************************************************
 * 	Instance methods - Private
 *********************************************************/
	
	/**
	 * Initialize this Duration.
	 * 
	 * @param integer seconds
	 * @return object Duration
	 * @access private
	 * @since 5/3/05
	 */
	function __construct ($seconds = 0) {
		$this->seconds = $seconds;
	}
	
	/**
	 * Answer an array {days. seconds. nanoSeconds}. Used by DateAndTime and Time
	 * 
	 * @return array
	 * @access private
	 * @since 5/2/05
	 */
	function ticks () {
		return array(
			$this->days(),
			(($this->hours() * 3600) + ($this->minutes() * 60) + floor($this->seconds()))
		);
			
	}
	
/*********************************************************
 * Instance methods - Accessing
 *********************************************************/
	
	/**
	 * Answer the number of days the receiver represents.
	 * 
	 * @return integer
	 * @access public
	 * @since 5/3/05
	 */
	function days () {
		if ($this->isPositive())
			return floor($this->seconds/ChronologyConstants::SecondsInDay());
		else {
			return 0 - floor(abs($this->seconds)/ChronologyConstants::SecondsInDay());
		}
	}
	
	/**
	 * Answer the number of hours the receiver represents.
	 * 
	 * @return integer
	 * @access public
	 * @since 5/3/05
	 */
	function hours () {		
		// Above 2^31 seconds, (amost exactly 100 years), PHP converts the
		// variable from an integer to a float to allow it to grow larger.
		// While addition and subraction work fine with floats, float modulos 
		// and divisions loose precision. This precision loss does not affect
		// the proper value of days up to the maximum duration tested, 50billion
		// years.
		if (abs($this->seconds) > pow(2, 31)) {
			$remainderDuration =$this->minus(Duration::withDays($this->days()));
			return $remainderDuration->hours();
		} else {
			if (!$this->isNegative())
				return floor(
					($this->seconds % ChronologyConstants::SecondsInDay()) 
					/ ChronologyConstants::SecondsInHour());
			else
				return 0 - floor(
					(abs($this->seconds) % ChronologyConstants::SecondsInDay()) 
					/ ChronologyConstants::SecondsInHour());
		}
	}
	
	/**
	 * Answer the number of minutes the receiver represents.
	 * 
	 * @return integer
	 * @access public
	 * @since 5/3/05
	 */
	function minutes () {
		// Above 2^31 seconds, (amost exactly 100 years), PHP converts the
		// variable from an integer to a float to allow it to grow larger.
		// While addition and subraction work fine with floats, float modulos 
		// and divisions loose precision. This precision loss does not affect
		// the proper value of days up to the maximum duration tested, 50billion
		// years.
		if (abs($this->seconds) > pow(2, 31)) {
			$remainderDuration =$this->minus(Duration::withDays($this->days()));
			return $remainderDuration->minutes();
		} else {
			if (!$this->isNegative())
				return floor(
					($this->seconds % ChronologyConstants::SecondsInHour()) 
					/ ChronologyConstants::SecondsInMinute());
			else
				return 0 - floor(
					(abs($this->seconds) % ChronologyConstants::SecondsInHour()) 
					/ ChronologyConstants::SecondsInMinute());
		}
	}
	
	/**
	 * Format as per ANSI 5.8.2.16: [-]D:HH:MM:SS[.S]
	 * 
	 * @return string
	 * @access public
	 * @since 5/3/05
	 */
	function printableString () {		
		$result = '';
		
		if ($this->isNegative())
			$result .= '-';
		
		$result .= abs($this->days()).':';
		$result .= str_pad(abs($this->hours()), 2, '0', STR_PAD_LEFT).':';
		$result .= str_pad(abs($this->minutes()), 2, '0', STR_PAD_LEFT).':';
		$result .= str_pad(abs($this->seconds()), 2, '0', STR_PAD_LEFT);
		
		return $result;
	}
	
	/**
	 * Answer the number of seconds the receiver represents.
	 * 
	 * @return integer
	 * @access public
	 * @since 5/3/05
	 */
	function seconds () {
		// Above 2^31 seconds, (amost exactly 100 years), PHP converts the
		// variable from an integer to a float to allow it to grow larger.
		// While addition and subraction work fine with floats, float modulos 
		// and divisions loose precision. This precision loss does not affect
		// the proper value of days up to the maximum duration tested, 50billion
		// years.
		if (abs($this->seconds) > pow(2, 31)) {
			$remainderDuration =$this->minus(Duration::withDays($this->days()));
			return $remainderDuration->seconds();
		} else {
			if ($this->isPositive())
				return floor($this->seconds % ChronologyConstants::SecondsInMinute());
			else
				return 0 - floor(
					abs($this->seconds) % ChronologyConstants::SecondsInMinute());
		}
	}
	
/*********************************************************
 * Instance methods - Comparing/Testing
 *********************************************************/
	
	/**
	 * Return true if this Duration is negative.
	 * 
	 * @return boolean
	 * @access public
	 * @since 5/3/05
	 */
	function isNegative () {
		return ($this->asSeconds() < 0);
	}
	
	/**
	 * Return true if this Duration is positive.
	 * 
	 * @return boolean
	 * @access public
	 * @since 5/3/05
	 */
	function isPositive () {
		return !($this->isNegative());
	}
	
	/**
	 * Test if this Duration is equal to aDuration.
	 * 
	 * @param object Duration $aDuration
	 * @return boolean
	 * @access public
	 * @since 5/3/05
	 */
	function isEqualTo ( $aDuration ) {
		return ($this->asSeconds() == $aDuration->asSeconds());
	}
	
	/**
	 * Test if this Duration is less than aDuration.
	 * 
	 * @param object Duration $aDuration
	 * @return boolean
	 * @access public
	 * @since 5/3/05
	 */
	function isLessThan ( $aDuration ) {
		return ($this->asSeconds() < $aDuration->asSeconds());
	}
	
/*********************************************************
 * Instance methods - Operations
 *********************************************************/
	
	/**
	 * Return the absolute value of this duration.
	 * 
	 * @return object Duration
	 * @access public
	 * @since 5/3/05
	 */
	function abs () {
		$obj = new Duration (abs($this->seconds));
		return $obj;
	}
	
	/**
	 * Divide a Duration. Operand is a Duration or a Number
	 * 
	 * @param object Duration $aDuration
	 * @return object Duration The result
	 * @access public
	 * @since 5/12/05
	 */
	function dividedBy ( $operand ) {
		if (is_numeric($operand)) {
			$obj = new Duration (intval($this->asSeconds() / $operand));
			return $obj;
		} else {
			$denominator =$operand->asDuration();
			$obj = new Duration (intval($this->asSeconds() / $denominator->asSeconds()));
			return $obj;
		}
	}
		
	/**
	 * Subtract a Duration.
	 * 
	 * @param object Duration $aDuration
	 * @return object Duration The result
	 * @access public
	 * @since 5/3/05
	 */
	function minus ( $aDuration ) {
		$obj =$this->plus($aDuration->negated());
		return $obj;
	}
	
	/**
	 * Multiply a Duration. Operand is a Duration or a Number
	 * 
	 * @param object Duration $aDuration
	 * @return object Duration The result
	 * @access public
	 * @since 5/12/05
	 */
	function multipliedBy ( $operand ) {
		if (is_numeric($operand)) {
			$obj = new Duration (intval($this->asSeconds() * $operand));
			return $obj;
		} else {
			$duration =$operand->asDuration();
			$obj = new Duration (intval($this->asSeconds() * $duration->asSeconds()));
			return $obj;
		}
	}
	
	/**
	 * Return the negative of this duration
	 * 
	 * @return object Duration
	 * @access public
	 * @since 5/10/05
	 */
	function negated () {
		$obj = new Duration(0 - $this->seconds);
		return $obj;
	}
	
	/**
	 * Add a Duration.
	 * 
	 * @param object Duration $aDuration
	 * @return object Duration The result.
	 * @access public
	 * @since 5/3/05
	 */
	function plus ( $aDuration ) {
		$obj = new Duration ($this->asSeconds() + $aDuration->asSeconds());
		return $obj;
	}
	
	/**
	 * Round to a Duration.
	 * 
	 * @param object Duration $aDuration
	 * @return object Duration The result.
	 * @access public
	 * @since 5/3/05
	 */
	function roundTo ( $aDuration ) {
		$obj = new Duration (
			intval(
				round(
					$this->asSeconds() / $aDuration->asSeconds())) 
			* $aDuration->asSeconds());
		return $obj;
	}
	
	/**
	 * Truncate. 
	 * e.g. if the receiver is 5 minutes, 37 seconds, and aDuration is 2 minutes, 
	 * answer 4 minutes.
	 * 
	 * @param object Duration $aDuration
	 * @return object Duration
	 * @access public
	 * @since 5/13/05
	 */
	function truncateTo ( $aDuration ) {
		$obj = new Duration (
			intval($this->asSeconds() / $aDuration->asSeconds())
			* $aDuration->asSeconds());
		return $obj;
	}
	
	
/*********************************************************
 * Instance methods - Converting
 *********************************************************/
	
	/**
	 * Answer the duration in seconds.
	 * 
	 * @return integer
	 * @access public
	 * @since 5/3/05
	 */
	function asSeconds () {
		return $this->seconds;
	}
	
	/**
	 * Answer a Duration that represents this object.
	 * 
	 * @return object Duration
	 * @access public
	 * @since 5/4/05
	 */
	function asDuration () {
		return $this;
	}
}

// Require the StringParser instead of the ANSI58216StringParser directly so
// as to make sure that all classes are included in the appropriate order.
require_once(dirname(__FILE__)."/StringParser/StringParser.class.php");


?>