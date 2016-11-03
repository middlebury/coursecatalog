<?php

/**
 * A helper to answer a 24-hour time string from an integer number of seconds.
 *
 * @since 6/9/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalog_View_Helper_GetTimeString
	extends Zend_View_Helper_Abstract
{

	/**
	 * Answer a 24-hour time string from an integer number of seconds.
	 *
	 * @param integer $seconds
	 * @return string
	 * @access protected
	 * @since 6/10/09
	 */
	public function getTimeString ($seconds) {
		$hour = floor($seconds/3600);
		$minute = floor(($seconds - ($hour * 3600))/60);
		$hour = $hour % 24;

		if (!$hour)
			$string = 12;
		else if ($hour < 13)
			$string = $hour;
		else
			$string = $hour - 12;

		$string .= ':'.str_pad($minute, 2, '0', STR_PAD_LEFT);

		if ($hour < 13)
			$string .= ' am';
		else
			$string .= ' pm';

		return $string;
	}

}
