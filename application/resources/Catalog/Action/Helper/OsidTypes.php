<?php

/**
 * A helper to provide access to type information.
 *
 * @since 6/9/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalog_Action_Helper_OsidTypes extends Catalog_Action_Helper_AbstractOsidIdentifier
{
    /**
     * Answer the course offering genus types that should be searched by default
     * for a catalog.
     *
     * @return array of osid_type_Types
     *
     * @since 6/16/09
     */
    public function getDefaultGenusTypes()
    {
        $types = [];
        $config = Zend_Registry::getInstance()->config;
        $typeStrings = $config->catalog->default_offering_genus_types_to_search;
        if (!$typeStrings) {
            return [];
        }

        foreach ($typeStrings as $typeString) {
            $types[] = new phpkit_type_URNInetType($typeString);
        }

        return $types;
    }
}
