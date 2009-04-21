<?php

/** Zend_Controller_Action */
class IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
    	$this->_forward('index', 'catalogs');
    }
}
