<?php
/**
 * @since 4/9/09
 * @package phpkit.configuration
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

/**
 * This class provides helper methods for common interactions with configuration
 * 
 * @since 4/9/09
 * @package phpkit.configuration
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
class phpkit_configuration_ConfigUtil {
		
	/**
	 * Get a single-valued value from a ValueLookupSession
	 * 
	 * @param osid_configuration_ValueLookupSession $session
	 * @param osid_id_Id $valueId
	 * @param osid_type_Type $valueType
	 * @return mixed
	 * @access public
	 * @since 4/9/09
	 * @static
	 */
	public static function getSingleValuedValue (osid_configuration_ValueLookupSession $session, osid_id_Id $valueId, osid_type_Type $valueType) {
		$values = $session->getValues($valueId);
		if (count($values) != 1)
			throw new osid_ConfigurationErrorException("'".phpkit_id_URNInetId::getInetURNString($valueId)."' must be specified once and only once. Found ".count($values)." values." );
		
		$value = $values[0];
		
		if (!$value->implementsValueType($valueType))
			throw new osid_ConfigurationErrorException("'".phpkit_id_URNInetId::getInetURNString($valueId)."' must be a ".phpkit_URNInetId::getInetURNString($valueType));
		
		return $value->getValue($valueType);
	}
	
}

?>