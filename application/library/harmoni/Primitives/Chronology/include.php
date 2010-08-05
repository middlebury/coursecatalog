<?php
/**
 * Classes can be included speparately, this is just a shortcut for including them
 * all.
 *
 * @since 5/27/05
 * @package harmoni.primitives.chronology
 * 
 * @copyright Copyright &copy; 2005, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id: include.php,v 1.2 2006/06/26 12:55:07 adamfranco Exp $
 *
 * @link http://harmoni.sourceforge.net/
 * @author Adam Franco <adam AT adamfranco DOT com> <afranco AT middlebury DOT edu>
 */ 

require_once(dirname(__FILE__).'/ChronologyConstants.class.php');
require_once(dirname(__FILE__).'/Date.class.php');
require_once(dirname(__FILE__).'/DateAndTime.class.php');
require_once(dirname(__FILE__).'/Duration.class.php');
require_once(dirname(__FILE__).'/Month.class.php');
require_once(dirname(__FILE__).'/Schedule.class.php');
require_once(dirname(__FILE__).'/Time.class.php');
require_once(dirname(__FILE__).'/Timespan.class.php');
require_once(dirname(__FILE__).'/TimeStamp.class.php');
require_once(dirname(__FILE__).'/TimeZone.class.php');
require_once(dirname(__FILE__).'/Week.class.php');
require_once(dirname(__FILE__).'/Year.class.php');

?>