<?php
/**
 * A helper to format schedule info strings for nice output.
 *
 * @copyright Copyright &copy; 2010, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalog_View_Helper_GetAvailabilityLink
	extends Zend_View_Helper_Abstract
{

	/**
	 * Answer a safe HTML string for the schedule info passed
	 *
	 * @param string $scheduleInfo
	 * @return string
	 */
	public function getAvailabilityLink (osid_course_CourseOffering $courseOffering) {
		$config = Zend_Registry::getInstance()->config;
		$bannerIdRecordType = new phpkit_type_URNInetType('urn:inet:middlebury.edu:record:banner_identifiers');
		if (!empty($config->catalog->banner_web_url) && $courseOffering->hasRecordType($bannerIdRecordType)) {
			$bannerIdRecord = $courseOffering->getCourseOfferingRecord($bannerIdRecordType);
			return "<a href=\"".$config->catalog->banner_web_url."?term_in=".$bannerIdRecord->getTermCode()."&crn_in=".$bannerIdRecord->getCourseReferenceNumber()."\" target='_blank' class='availability_link'>View availability, prerequisites, and other requirements.</a>";
		}
		return null;
	}

}
