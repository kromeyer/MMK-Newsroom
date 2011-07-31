<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Controller_Plugin_Mobile extends \Zend_Controller_Plugin_Abstract
{
    public function preDispatch(\Zend_Controller_Request_Abstract $request)
    {
        if ($request->getModuleName() != 'admin' && isset($_SERVER['HTTP_USER_AGENT']))
        {
            $isTablet = (bool) strpos($_SERVER['HTTP_USER_AGENT'], 'iPad'); // Apple iPad
            $isTablet = $isTablet || (bool) strpos($_SERVER['HTTP_USER_AGENT'], 'SCH-I800'); // Samsung Galaxy Tab

            if (!$isTablet
                && preg_match('~mobile~i', $_SERVER['HTTP_USER_AGENT']))
            {
                \Zend_Layout::getMvcInstance()->setViewSuffix('mobile.phtml');
                \Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer')->setViewSuffix('mobile.phtml');
            }
        }
    }
}
