<?php

trait Catalog_ApplicationTestTrait
{
    use banner_DatabaseTestTrait;

    public static function setUpBeforeClass(): void
    {
        self::setUpDatabase();

        Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH.'/controllers/helper', 'Helper');
        Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH.'/resources/Catalog/Action/Helper', 'Catalog_Action_Helper');
        Zend_Controller_Action_HelperBroker::addPath(APPLICATION_PATH.'/resources/Auth/Action/Helper', 'Auth_Action_Helper');

        $registry = Zend_Registry::getInstance();
        $registry->config = new Zend_Config_Ini(dirname(__FILE__).'/frontend_config.ini', 'development');
    }

    /**
     * Answer the configuration path to be used when running tests.
     */
    public function getTestConfigPath()
    {
        return dirname(__FILE__).'/../apc/configuration.plist';
    }
}
