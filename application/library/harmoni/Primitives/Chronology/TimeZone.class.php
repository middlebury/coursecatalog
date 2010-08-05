<?php
/**
 * @since 5/3/05
 * @package harmoni.primitives.chronology
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: TimeZone.class.php,v 1.5 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */ 

require_once(dirname(__FILE__)."/../Objects/SObject.class.php");

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
 * @package harmoni.primitives.chronology
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: TimeZone.class.php,v 1.5 2007/10/10 22:58:33 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class TimeZone 
	extends SObject
{

/*********************************************************
 * Class Methods - Instance Creation
 *********************************************************/	
 
 	/**
	 * Answer the default time zone - GMT
	 * 
	 * @return object TimeZone
	 * @access public
	 * @since 5/3/05
	 * @static
	 */
	static function defaultTimeZone () {
		$obj = TimeZone::offsetNameAbbreviation(
					Duration::withHours(0),
					'Greenwich Mean Time',
					'GMT');
		return $obj;
	}
	
	/**
	 * Create a new Timezone.
	 * 
	 * @param object Duration $aDuration
	 * @param string $aStringName
	 * @param string $aStringAbbriviation
	 * @return object TimeZone
	 * @access public
	 * @static
	 * @since 5/3/05
	 */
	static function offsetNameAbbreviation ( $aDuration, $aStringName = NULL, 
		$aStringAbbreviation = NULL) 
	{
		$obj = new TimeZone ($aDuration, $aStringName, $aStringAbbreviation );
		return $obj;
	}
	
	
/*********************************************************
 * Class Methods - Accessing
 *********************************************************/
	
	/**
	 * Return an Array of TimeZones
	 * 
	 * @return array
	 * @access public
	 * @since 5/3/05
	 * @static
	 */
	static function timeZones () {
		$array = array (
			TimeZone::offsetNameAbbreviation(
				Duration::withHours(0),
				'Universal Time',
				'UTC'),
			TimeZone::offsetNameAbbreviation(
				Duration::withHours(0),
				'Greenwich Mean Time',
				'GMT'),
			TimeZone::offsetNameAbbreviation(
				Duration::withHours(0),
				'British Summer Time',
				'BST'),
			TimeZone::offsetNameAbbreviation(
				Duration::withHours(-5),
				'Eastern Standard Time',
				'EST'),
			TimeZone::offsetNameAbbreviation(
				Duration::withHours(-4),
				'Eastern Daylight Time',
				'EDT'),
			TimeZone::offsetNameAbbreviation(
				Duration::withHours(-6),
				'Central Standard Time',
				'CST'),
			TimeZone::offsetNameAbbreviation(
				Duration::withHours(-5),
				'Central Daylight Time',
				'CDT'),
			TimeZone::offsetNameAbbreviation(
				Duration::withHours(-7),
				'Mountain Standard Time',
				'MST'),
			TimeZone::offsetNameAbbreviation(
				Duration::withHours(-6),
				'Mountain Daylight Time',
				'MDT'),
			TimeZone::offsetNameAbbreviation(
				Duration::withHours(-8),
				'Pacific Standard Time',
				'PST'),
			TimeZone::offsetNameAbbreviation(
				Duration::withHours(-7),
				'Pacific Daylight Time',
				'PDT'),
			
		);
		
		return $array;
	}
	
	
/*********************************************************
 * 	Instance Methods - private
 *********************************************************/
	
	/**
	 * Create a new Timezone.
	 * 
	 * @param object Duration $aDuration
	 * @param string $aStringName
	 * @param string $aStringAbbriviation
	 * @return object TimeZone
	 * @access private
	 * @since 5/3/05
	 */
	function TimeZone ( $aDuration, $aStringName, $aStringAbbreviation ) {
		$this->offset =$aDuration;
		$this->name = $aStringName;
		$this->abbreviation = $aStringAbbreviation;
	}
	
/*********************************************************
 * Instance Methods - Accessing
 *********************************************************/
	
	/**
	 * Return the offset of this TimeZone
	 * 
	 * @return object Duration
	 * @access public
	 * @since 5/3/05
	 */
	function offset () {
		return $this->offset;
	}
	
	/**
	 * Answer the abreviation
	 * 
	 * @return string
	 * @access public
	 * @since 5/10/05
	 */
	function abbreviation () {
		return $this->abbreviation;
	}
	
	/**
	 * Answer the name
	 * 
	 * @return string
	 * @access public
	 * @since 5/10/05
	 */
	function name () {
		return $this->name;
	}
	
	/**
	 * Answer a string version of this time zone
	 * 
	 * @return string
	 * @access public
	 * @since 10/15/08
	 */
	public function printableString () {
		if ($this->offset->isLessThan(Duration::withSeconds(0)))
			$string = '-';
		else
			$string = '+';
		
		$string .= str_pad(abs($this->offset->hours()), 2, '0', STR_PAD_LEFT);
		$string .=':'.str_pad(abs($this->offset->minutes()), 2, '0', STR_PAD_LEFT);
		
		return $string;
	}
}

?>