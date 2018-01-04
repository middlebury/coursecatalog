<?php

/**
 * A helper to generate form inputs for modifying config values.
 *
 * @since 1/4/17
 *
 * @copyright Copyright &copy; 2017, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Zend_View_Helper_GenerateSectionInput
	extends Zend_View_Helper_Abstract
{

	/**
	 * Generate an input field for a section based on its type.
	 *
	 * @param string $type
	 * @param string $value
	 * @return string
	 * @access public
	 * @since 1/4/17
	 */
	public function generateSectionInput ($type, $value) {
		switch ($type) {
			case "h1":
			case "h2":
			case "page_content":
				return "<input value='" . $value . "'></input>";
			case "custom_text":
				return "<textarea value='" . $value . "'>" . $value . "</textarea>";
			case "course_list":
				return "
				<select name='course-list-select'>
					<optgroup label='Subjects'>
					</optgroup>
					<optgroup label='Departments'>
					</optgroup>
				</select>";
		}

		return "Invalid section type!";
	}
}
