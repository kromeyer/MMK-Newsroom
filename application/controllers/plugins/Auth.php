<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Controller_Plugin_Auth extends \Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(\Zend_Controller_Request_Abstract $request)
    {
        if (\Zend_Auth::getInstance()->hasIdentity())
        {
            /* logged in */
            if ($this->_request->getModuleName() == 'admin'
                && $this->_request->getControllerName() == 'index'
                && $this->_request->getActionName() == 'login')
            {
                header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin');

                $this->_request->setModuleName('admin');
                $this->_request->setControllerName('index');
                $this->_request->setActionName('index');
            }
        }
        else
        {
            /* not logged in */
            if ($this->_request->getModuleName() == 'admin'
                && ($this->_request->getControllerName() != 'index'
                    || $this->_request->getActionName() != 'login'))
            {
                header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login');

                $this->_request->setModuleName('admin');
                $this->_request->setControllerName('index');
                $this->_request->setActionName('login');
            }
        }
    }
}
