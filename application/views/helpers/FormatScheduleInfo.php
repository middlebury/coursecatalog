<?php
/**
 * A helper to format schedule info strings for nice output.
 * 
 * @copyright Copyright &copy; 2010, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 
class Catalog_View_Helper_FormatScheduleInfo
	extends Zend_View_Helper_Abstract
{
		
	/**
	 * Answer a safe HTML string for the schedule info passed
	 * 
	 * @param string $scheduleInfo
	 * @return string
	 */
	public function formatScheduleInfo ($scheduleInfo) {
		$scheduleInfo = nl2br($this->view->escape($scheduleInfo));
		$scheduleInfo = preg_replace('/\([^\)]+\)/', '<span style="white-space: nowrap">$0</span>', $scheduleInfo);
		return $scheduleInfo;
	}
	
}

