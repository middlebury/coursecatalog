<?php
/**
 * @since 4/13/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * This interface defines the methods that a course Manager in this package must
 * implement to give needed access to its sessions.
 * 
 * @since 4/13/09
 * @package banner.course
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
interface banner_course_CourseManagerInterface 
	extends osid_course_CourseManager
{
		
	/**
	 * Answer the database connection
	 * 
	 * @return PDO
	 * @access public
	 * @since 4/13/09
	 */
	public function getDB ();
	
	/**
	 * Answer the Identifier authority to use.
	 * 
	 * @return string
	 * @access public
	 * @since 4/13/09
	 */
	public function getIdAuthority ();
}

?>