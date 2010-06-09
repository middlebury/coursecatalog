<?php

/**
 * A helper to provide access to the CourseManager OSID and OSID configuration.
 * 
 * @since 6/9/10
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class Catalog_Action_Helper_OsidType
	extends Catalog_Action_Helper_AbstractOsidIdentifier
{
	
	/**
	 * Get and OSID type object from a string.
	 * 
	 * @param string $idString
	 * @return osid_type_Type
	 * @access public
	 * @since 4/21/09
	 */
	public function fromString ($idString) {
		try {
			return new phpkit_type_URNInetType($idString);
		} catch (osid_InvalidArgumentException $e) {
			if ($this->getIdAuthorityToShorten())
				return new phpkit_type_Type('urn', $this->getIdAuthorityToShorten(), $idString);
			else
				throw $e;
		}
	}
	
	/**
	 * Answer a string representation of an OSID type object
	 * 
	 * @param osid_type_Type $type
	 * @return string
	 * @access public
	 * @since 4/21/09
	 */
	public function toString (osid_type_Type $type) {
		if ($this->getIdAuthorityToShorten() 
				&& strtolower($type->getIdentifierNamespace()) == 'urn' 
				&& strtolower($type->getAuthority()) == $this->getIdAuthorityToShorten())
			return $type->getIdentifier();
		else
			return phpkit_type_URNInetType::getInetURNString($type);
	}
	
}

?>