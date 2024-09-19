<?php

/**
 * A helper to provide access to the CourseManager OSID and OSID configuration.
 *
 * @since 6/9/10
 *
 * @copyright Copyright &copy; 2009, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 */
abstract class Catalog_Action_Helper_AbstractOsidIdentifier extends Zend_Controller_Action_Helper_Abstract
{
    private static $idAuthorityToShorten;

    /**
     * Answer the id-authority for whom ids should be shortened.
     *
     * @return mixed string or false if none should be shortened
     *
     * @since 6/16/09
     */
    protected function getIdAuthorityToShorten()
    {
        if (!isset(self::$idAuthorityToShorten)) {
            $config = Zend_Registry::getInstance()->config;
            $authority = strval($config->catalog->shorten_ids_for_authority);
            if (strlen($authority)) {
                self::$idAuthorityToShorten = $authority;
            } else {
                self::$idAuthorityToShorten = false;
            }
        }

        return self::$idAuthorityToShorten;
    }
}
