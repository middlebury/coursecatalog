<?php

namespace App\Service\Osid;

/**
 * A helper to convert between object and string representations of Ids.
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class IdMap extends AbstractOsidIdentifierMap
{
    /**
     * Get and OSID id object from a string.
     *
     * @param string $idString
     *
     * @return osid_id_Id
     *
     * @since 4/21/09
     */
    public function fromString($idString)
    {
        try {
            return new \phpkit_id_URNInetId($idString);
        } catch (\osid_InvalidArgumentException $e) {
            if ($this->getIdAuthorityToShorten()) {
                return new \phpkit_id_Id($this->getIdAuthorityToShorten(), 'urn', $idString);
            } else {
                throw $e;
            }
        }
    }

    /**
     * Answer a string representation of an OSID id object.
     *
     * @return string
     *
     * @since 4/21/09
     */
    public function toString(\osid_id_Id $id)
    {
        if ($this->getIdAuthorityToShorten()
                && 'urn' == strtolower($id->getIdentifierNamespace())
                && strtolower($id->getAuthority()) == $this->getIdAuthorityToShorten()) {
            return $id->getIdentifier();
        } else {
            return \phpkit_id_URNInetId::getInetURNString($id);
        }
    }

    /**
     * Get and OSID Type object from a string.
     *
     * @param string $typeString
     *
     * @return osid_type_Type
     *
     * @since 4/21/09
     */
    public function typeFromString($typeString)
    {
        try {
            return new \phpkit_type_URNInetType($typeString);
        } catch (\osid_InvalidArgumentException $e) {
            if ($this->getIdAuthorityToShorten()) {
                return new \phpkit_type_Type('urn', $this->getIdAuthorityToShorten(), $typeString);
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
    public function typeToString(\osid_type_Type $type)
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
