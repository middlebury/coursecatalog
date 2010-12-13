<?php
/**
 * @since 5/4/05
 * @package harmoni.primitives.chronology
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Week.class.php,v 1.7 2007/10/30 16:34:28 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */ 
 
require_once(dirname(__FILE__)."/Timespan.class.php");

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
 * @package harmoni.primitives.chronology
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: Week.class.php,v 1.7 2007/10/30 16:34:28 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */
class Week 
	extends Timespan
{

/*********************************************************
 * Class Methods
 *********************************************************/
 
	/**
	 * Return the index of a string Day.
	 * 
	 * @param string $aNameString
	 * @return integer
	 * @access public
	 * @since 5/4/05
	 */
	static function indexOfDay ( $aNameString ) {
		foreach (ChronologyConstants::DayNames() as $i => $name) {
			if (preg_match("/$aNameString.*/i", $name))
				return $i;
		}
		
		$errorString = $aNameString ." is not a recognized day name.";
		if (function_exists('throwError'))
			throwError(new Error($errorString));
		else
			die ($errorString);
	}
	
	/**
	 * Return the name of the day at index.
	 * 
	 * @param integer $anInteger
	 * @return string
	 * @access public
	 * @since 5/4/05
	 */
	static function nameOfDay ( $anInteger ) {
		$names = ChronologyConstants::DayNames();
		if ($names[$anInteger])
			return $names[$anInteger];
		
		$errorString = $anInteger ." is not a valid day index.";
		if (function_exists('throwError'))
			throwError(new Error($errorString));
		else
			die ($errorString);
	}
	
	/**
	 * Answer the day at the start of the week
	 * 
	 * @return string
	 * @access public
	 * @since 5/20/05
	 */
	static function startDay () {
		$dayNames = ChronologyConstants::DayNames();
		return $dayNames[1];
	}
	
/*********************************************************
 * Class Methods - Instance Creation
 *
 * All static instance creation methods have an optional
 * $class parameter which is used to get around the limitations 
 * of not being	able to find the class of the object that 
 * recieved the initial method call rather than the one in
 * which it is implemented. These parameters SHOULD NOT BE
 * USED OUTSIDE OF THIS PACKAGE.
 *********************************************************/
 
 	/**
	 * Answer a new object that represents now.
	 * 
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object Week
	 * @access public
	 * @since 5/5/05
	 * @static
	 */
	static function current ( $class = 'Week' ) {
		$obj = parent::current($class);
		return $obj;
	}
	
	/**
	 * Answer a Month starting on the Squeak epoch: 1 January 1901
	 * 
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object Week
	 * @access public
	 * @since 5/5/05
	 * @static
	 */
	static function epoch ( $class = 'Week' ) {
		$obj = parent::epoch($class);
		return $obj;
	}
	
	/**
	 * Create a new object starting now, with zero duration
	 * 
	 * @param object DateAndTime $aDateAndTime
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object Week
	 * @access public
	 * @since 5/5/05
	 * @static
	 */
	static function starting ( $aDateAndTime, $class = 'Week' ) {
		$obj = parent::starting($aDateAndTime, $class);
		return $obj;
	}
	
	/**
	 * Create a new object with given start and end DateAndTimes
	 * 
	 * @param object DateAndTime $startDateAndTime
	 * @param object DateAndTime $endDateAndTime
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object Week
	 * @access public
	 * @since 5/11/05
	 * @static
	 */
	static function startingEnding ( $startDateAndTime, $endDateAndTime, 
		$class = 'Week' ) 
	{
		$obj = parent::startingEnding ( $startDateAndTime, $endDateAndTime, $class);
		return $obj;
	}
	
		
	/**
	 * Create a new object starting now, with a given duration. 
	 * Override - as each Week has a defined duration
	 * 
	 * @param object DateAndTime $aDateAndTime
	 * @param object Duration $aDuration
	 * @param optional string $class DO NOT USE OUTSIDE OF PACKAGE.
	 *		This parameter is used to get around the limitations of not being
	 *		able to find the class of the object that recieved the initial 
	 *		method call.
	 * @return object Week
	 * @access public
	 * @since 5/5/05
	 * @static
	 */
	static function startingDuration ( $aDateAndTime, $aDuration, $class = 'Week' ) {
		
		// Validate our passed class name.
		if (!(strtolower($class) == strtolower('Week')
			|| is_subclass_of(new $class, 'Week')))
		{
			die("Class, '$class', is not a subclass of 'Week'.");
		}
		
		$asDateAndTime =$aDateAndTime->asDateAndTime();
		$midnight =$asDateAndTime->atMidnight();
		$dayNames = ChronologyConstants::DayNames();
		$temp = $midnight->dayOfWeek() + 7 - array_search(Week::startDay(), $dayNames);
		$delta =  abs($temp - (intval($temp/7) * 7));
		
		$adjusted =$midnight->minus(Duration::withDays($delta));
		
		$obj = parent::startingDuration($adjusted, Duration::withWeeks(1), $class);
		
		return $obj;
	}

	
/*********************************************************
 * Instance Methods - Converting
 *********************************************************/
 	
 	/**
 	 * Answer the receiver as a Week
 	 * 
 	 * @return object Week
 	 * @access public
 	 * @since 5/23/05
 	 */
 	function asWeek () {
 		return $this;
 	}
}

?>