<?php

namespace App\Service\Osid;

/**
 * A helper to provide access to type information.
 *
 * @since 6/9/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class TypeHelper
{
    /**
     * Set up the service.
     *
     * @param array $defaultOfferingGenusTypesToSearch
     *                                                 The default genus types to search
     */
    public function __construct(
        private array $defaultOfferingGenusTypesToSearch,
    ) {
    }

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
        foreach ($this->defaultOfferingGenusTypesToSearch as $typeString) {
            $types[] = new \phpkit_type_URNInetType($typeString);
        }

        return $types;
    }
}
