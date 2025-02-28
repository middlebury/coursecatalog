<?php

namespace App\Service\Osid;

/**
 * A helper to convert between object and string representations of Types.
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class TypeMap extends AbstractOsidIdentifierMap
{
    /**
     * Get and OSID type object from a string.
     *
     * @param string $idString
     *
     * @return osid_type_Type
     *
     * @since 4/21/09
     */
    public function fromString($idString)
    {
        try {
            return new \phpkit_type_URNInetType($idString);
        } catch (\osid_InvalidArgumentException $e) {
            if ($this->getIdAuthorityToShorten()) {
                return new \phpkit_type_Type('urn', $this->getIdAuthorityToShorten(), $idString);
            } else {
                throw $e;
            }
        }
    }

    /**
     * Answer a string representation of an OSID type object.
     *
     * @return string
     *
     * @since 4/21/09
     */
    public function toString(\osid_type_Type $type)
    {
        if ($this->getIdAuthorityToShorten()
                && 'urn' == strtolower($type->getIdentifierNamespace())
                && strtolower($type->getAuthority()) == $this->getIdAuthorityToShorten()) {
            return $type->getIdentifier();
        } else {
            return \phpkit_type_URNInetType::getInetURNString($type);
        }
    }
}
