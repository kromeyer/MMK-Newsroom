<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Admin_IndexController extends \Zend_Controller_Action
{
    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    }

    public function loginAction()
    {
        $configForm = $this->getInvokeArg('bootstrap')->getResource('configForm');
        $loginForm = new \Zend_Form($configForm->login);

        if ($this->getRequest()->isPost())
        {
            if ($loginForm->isValid($_POST))
            {
                try
                {
                    $auth = $this->getInvokeArg('bootstrap')->auth;
                    $auth->setIdentity($loginForm->getValue('login'))
                         ->setCredential($loginForm->getValue('password'));

                    $result = \Zend_Auth::getInstance()->authenticate($auth);

                    if ($result->isValid())
                    {
                        $this->_redirect('/admin');
                    }
                    else
                    {
                        $this->_helper->systemMessages('error', 'Anmeldung verweigert');
                    }
                }
                catch (\Exception $e)
                {
                    $log = $this->getInvokeArg('bootstrap')->log;
                    $log->log(
                            $e->getMessage(),
                            \Zend_Log::ERR,
                            array('trace' => $e->getTraceAsString())
                    );

                    $this->_helper->systemMessages('error', 'Fehler bei der Anmeldung');
                }
            }
        }

        $loginForm->setAction('/login');
        $this->view->form = $loginForm;
    }

    public function logoutAction()
    {
        \Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect('/login');
    }
}
