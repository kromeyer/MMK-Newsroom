<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Controller_Plugin_Error extends \Zend_Controller_Plugin_ErrorHandler
{
    protected function _handleError(\Zend_Controller_Request_Abstract $request)
    {
        $frontController = \Zend_Controller_Front::getInstance();
        if ($frontController->getParam('noErrorHandler')) {
            return;
        }

        $response = $this->getResponse();

        if ($this->_isInsideErrorHandlerLoop) {
            $exceptions = $response->getException();
            if (count($exceptions) > $this->_exceptionCountAtFirstEncounter) {
                // Exception thrown by error handler; tell the front controller to throw it
                $frontController->throwExceptions(true);
                throw array_pop($exceptions);
            }
        }

        // check for an exception AND allow the error handler controller the option to forward
        if (($response->isException()) && (!$this->_isInsideErrorHandlerLoop)) {
            $this->_isInsideErrorHandlerLoop = true;

            // Get exception information
            $error            = new \ArrayObject(array(), \ArrayObject::ARRAY_AS_PROPS);
            $exceptions       = $response->getException();
            $exception        = $exceptions[0];

            // use http status codes
            switch (get_class($exception))
            {
                case 'Zend_Controller_Dispatcher_Exception':
                    $error->code = 404;
                    break;
                default:
                    $error->code = $exception->getCode();

            }

            $error->exception = $exception;

            // Keep a copy of the original request
            $error->request = clone $request;

            // get a count of the number of exceptions encountered
            $this->_exceptionCountAtFirstEncounter = count($exceptions);

            // Forward to the error handler
            $request->setParam('error', $error)
                    ->setModuleName($this->getErrorHandlerModule())
                    ->setControllerName($this->getErrorHandlerController())
                    ->setActionName($this->getErrorHandlerAction())
                    ->setDispatched(false);
        }
    }
}
