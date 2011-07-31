<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Default_ErrorController extends \Zend_Controller_Action
{
    public function errorAction()
    {
        $error = $this->_getParam('error');

        if (!$error) {
            $this->view->message = 'You have reached the error page';
            return;
        }

        switch ($error->code)
        {
            case 403:
                $this->getResponse()->setHttpResponseCode(403);
                $priority = \Zend_Log::NOTICE;
                $this->view->message = 'Forbidden';
                break;
            case 404:
                $this->getResponse()->setHttpResponseCode(404);
                $priority = \Zend_Log::NOTICE;
                $this->view->message = 'Not Found';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $priority = \Zend_Log::CRIT;
                $this->view->message = 'Application error';
        }

        // Log exception, if logger available
        if ($log = $this->getLog())
        {
            $log->log(
                    $error->exception->getMessage(),
                    $priority,
                    array('trace' => $error->exception->getTraceAsString())
            );
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true)
        {
            $this->view->exception = $error->exception;
        }

        $this->view->request = $error->request;
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }
}
