<?php
/**
 * @since 11/5/09
 * @package 
 * 
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */ 

require_once('CAS.php');

class Default_Resource_Cas
	extends Zend_Application_Resource_ResourceAbstract
{
	public function init()
	{
		$options = $this->getOptions();
		
		if (!isset($options['host']))
			throw new Exception('Configuration Error: no cas.host');
		if (!isset($options['port']))
			throw new Exception('Configuration Error: no cas.port');
		if (!isset($options['path']))
			throw new Exception('Configuration Error: no cas.path');
		
		phpCAS::client(CAS_VERSION_2_0, $options['host'], intval($options['port']), $options['path'], false);
		if (isset($options['sever_cert']))
			phpCAS::setCasServerCACert($options['sever_cert']);
		else
			phpCAS::setNoCasServerValidation();
		
	}
}