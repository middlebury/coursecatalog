<?php

/** Zend_Controller_Action */
class AuthController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->loginAction();
    }

    public function loginAction()
    {
        if ($this->_helper->auth()->login($this->_getParam('return'))) {
            if ($this->_getParam('return')) {
                $this->_redirect($this->_getParam('return'), ['prependBase' => false, 'exit' => true]);
            } else {
                $this->_redirect('/', ['prependBase' => true, 'exit' => true]);
            }
        }
    }

    public function logoutAction()
    {
        $this->_helper->auth()->logout($this->_getParam('return'));

        if ($this->_getParam('return')) {
            $this->_redirect($this->_getParam('return'), ['prependBase' => false, 'exit' => true]);
        } else {
            $this->_redirect('/', ['prependBase' => true, 'exit' => true]);
        }
    }
}
