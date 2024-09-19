<?php
/**
 * @since 4/14/09
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
if (!defined('BASE_PATH')) {
    define('BASE_PATH', realpath(__DIR__.'/../'));
    define('APPLICATION_PATH', BASE_PATH.'/application');
    set_include_path(
        APPLICATION_PATH.'/library'
        .\PATH_SEPARATOR.APPLICATION_PATH.'/resources'
        .\PATH_SEPARATOR.BASE_PATH.'/library/osid-phpkit'
        .\PATH_SEPARATOR.BASE_PATH.'/library/ZendFramework/library'
        .\PATH_SEPARATOR.BASE_PATH.'/library/fsmparser'
        .\PATH_SEPARATOR.BASE_PATH.'/library/phpcas/source'
        .\PATH_SEPARATOR.BASE_PATH.'/library/lazy_sessions'
        .\PATH_SEPARATOR.get_include_path()
    );
}

require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);

require_once BASE_PATH.'/vendor/autoload.php';
