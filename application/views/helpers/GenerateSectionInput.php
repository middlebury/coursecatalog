<?php

/**
 * A helper to generate form inputs for modifying config values.
 *
 * @since 1/4/17
 *
 * @copyright Copyright &copy; 2017, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Zend_View_Helper_GenerateSectionInput extends Zend_View_Helper_Abstract
{
    /**
     * Generate an input field for a section based on its type.
     *
     * @param string $type
     * @param string $value
     *
     * @return string
     *
     * @since 1/4/17
     */
    public function generateSectionInput($type, $value)
    {
        switch ($type) {
        }

        return 'Invalid section type!';
    }
}
