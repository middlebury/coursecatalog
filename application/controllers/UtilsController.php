<?php

/** Zend_Controller_Action */
class UtilsController extends Zend_Controller_Action
{
    public function clearcacheAction()
    {
        $config = Zend_Registry::getInstance()->config;
        if (!isset($config->osid->apc->clear_cache_key) || !strlen(trim($config->osid->apc->clear_cache_key)) || '' == $config->osid->apc->clear_cache_key) {
            throw new Exception('osid.apc.clear_cache_key is not configured.');
        }
        if ($this->_getParam('key') != $config->osid->apc->clear_cache_key) {
            throw new InvalidArgumentException('key supplied does not match osid.apc.clear_cache_key');
        }

        apcu_clear_cache();

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $this->getResponse()->setHeader('Content-Type', 'text/plain');
        echo 'APC Cache Cleared.';
    }
}
