<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Bootstrap extends \Zend_Application_Bootstrap_Bootstrap
{
    public function __construct($application)
    {
        parent::__construct($application);

        $this->_executeResource('exceptionHandler');
        $this->_executeResource('errorHandler');
    }

    protected function _initApi()
    {
        $this->bootstrap('autoloader');
        $this->bootstrap('cache');
        $this->bootstrap('log');

        \Pkr_View_Helper_ApiAbstract::setCache($this->getResource('cache'));
        \Pkr_View_Helper_ApiAbstract::setLog($this->getResource('log'));
    }

    protected function _initAuth()
    {
        $this->bootstrap('autoloader');
        $this->bootstrap('doctrine');
        $this->bootstrap('session');

        $auth = \Zend_Auth::getInstance();
        $auth->setStorage(new \Zend_Auth_Storage_Session());

        $entityManager = $this->getResource('doctrine')->getEntityManager();
        $authAdapter = new \Pkr_Auth_Adapter_Doctrine(
            $entityManager->getRepository('\Newsroom\Entity\User')
        );

        return $authAdapter;
    }

    protected function _initAutoloader()
    {
        require_once APPLICATION_PATH . '/../library/Doctrine/Common/ClassLoader.php';

        $autoloader = \Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('Pkr_');

        $bisnaAutoloader = new \Doctrine\Common\ClassLoader('Bisna');
        $autoloader->pushAutoloader(array($bisnaAutoloader, 'loadClass'), 'Bisna');

        $markdownAutoloader = new \Doctrine\Common\ClassLoader('Markdown');
        $autoloader->pushAutoloader(array($markdownAutoloader, 'loadClass'), 'Markdown');

        $newsroomAutoloader = new \Doctrine\Common\ClassLoader('Newsroom');
        $autoloader->pushAutoloader(array($newsroomAutoloader, 'loadClass'), 'Newsroom');

        $resourceLoader = new \Zend_Loader_Autoloader_Resource(
            array(
                'basePath' => APPLICATION_PATH,
                'namespace' => '',
                'resourceTypes' => array(
                    'form' => array(
                        'path' => 'forms',
                        'namespace' => 'Form_'),
                    'helper' => array(
                        'path' => 'controllers/action/helper',
                        'namespace' => 'Controller_Action_Helper_'),
                    'plugin' => array(
                        'path' => 'controllers/plugins',
                        'namespace' => 'Controller_Plugin_')
                    //'validators' => array(
                    //    'path' => 'validators/',
                    //    'namespace' => 'Validate_')
                )
            )
        );

        \Zend_Controller_Action_HelperBroker::addHelper(new \Pkr_Controller_Action_Helper_EntityManager());
        \Zend_Controller_Action_HelperBroker::addHelper(new \Pkr_Controller_Action_Helper_SystemMessages());
        \Zend_Controller_Action_HelperBroker::addHelper(new \Pkr_Controller_Action_Helper_Truncate());
    }

    protected function _initCache()
    {
        $config = $this->getOption('configuration');

        return \Zend_Cache::factory('Core', 'File', $config['cache']['frontend'], $config['cache']['backend']);
    }

    protected function _initConfigForm()
    {
        $this->bootstrap('autoloader');

        $config = $this->getOption('configuration');

        return new \Zend_Config_Ini($config['form']['configFile']);
    }

    protected function _initDate()
    {
        $config = $this->getOption('configuration');

        date_default_timezone_set($config['date']['timezone']);
        setlocale(LC_ALL, $config['date']['locale']);

        \Zend_Locale::setDefault($config['date']['locale']);

        $date = new \Zend_Date();
        return $date;
    }

    protected function _initEntity()
    {
        $this->bootstrap('doctrine');

        $doctrine = $this->getResource('doctrine');
        \Newsroom\EntityAbstract::setEntityManager($doctrine->getEntityManager());
    }

    protected function _initErrorHandler()
    {
        $this->bootstrap('log');

        set_error_handler(array($this, 'errorHandler'));
    }

    protected function _initExceptionHandler()
    {
        $this->bootstrap('log');

        set_exception_handler(array($this, 'exceptionHandler'));
    }

    protected function _initLayout()
    {
        $this->bootstrap('autoloader');
        $this->bootstrap('frontController');

        \Zend_Layout::startMvc(array(
                'layoutPath' => APPLICATION_PATH . '/modules/default/layouts/scripts/',
                'layout' => 'layout'
        ));

        $layoutModulePlugin = new \Controller_Plugin_Layout();
        $layoutModulePlugin->registerModuleLayout('admin', APPLICATION_PATH . '/modules/admin/layouts/scripts/');

        \Zend_Controller_Front::getInstance()->registerPlugin($layoutModulePlugin);
    }

    protected function _initLog()
    {
        $this->bootstrap('mail');

        $config = $this->getOption('configuration');

        $writer = new \Zend_Log_Writer_Stream($config['log']['file']);

        require_once 'Pkr/Log/Formatter/Advanced.php';
        $formatter = new \Pkr_Log_Formatter_Advanced();
        $writer->setFormatter($formatter);

        $log = new \Zend_Log($writer);

        if (APPLICATION_ENV !== 'development')
        {
            $mail = new \Zend_Mail('UTF-8');
            $mail->setSubject($config['log']['mail']['subject'])
                 ->setFrom($config['log']['mail']['from'])
                 ->addTo($config['log']['mail']['to']);

            $writer = new \Zend_Log_Writer_Mail($mail);

            $formatter = new \Pkr_Log_Formatter_Advanced();
            $writer->setFormatter($formatter);

            $log->addWriter($writer);
        }

        return $log;
    }

    protected function _initRouter()
    {
        $this->bootstrap('frontController');

        $config = $this->getOption('configuration');
        $configRouter = new \Zend_Config_Ini($config['router']['configFile']);

        $router = \Zend_Controller_Front::getInstance()->getRouter();
        $router->addConfig($configRouter->routes);
    }

    protected function _initSession()
    {
        $config = $this->getOption('configuration');

        // TODO: write Session_SaveHandler_Doctrine
        // $this->bootstrap('doctrine');

        \Zend_Session::setOptions($config['session']);
        \Zend_Session::start();

        return new \Zend_Session_Namespace('default', true);
    }

    protected function _initView()
    {
        $config = $this->getOption('configuration');

        $view = new \Zend_View();

        $view->doctype($config['view']['doctype']);
        $view->setEncoding($config['view']['encoding']);

        $view->headTitle($config['view']['title'])
             ->setSeparator($config['view']['titleSeparator'])
             ->setDefaultAttachOrder(\Zend_View_Helper_Placeholder_Container_Abstract::PREPEND);

        $view->headMeta()->setName('keywords', $config['view']['keywords'])
                         ->setName('description', $config['view']['description']);

        $view->addHelperPath('Pkr/View/Helper/', 'Pkr_View_Helper_');
        $view->partialLoop()->setObjectKey('object');

        // add it to the ViewRenderer
        $viewRenderer = \Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);
        \Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

        return $view;
    }



    /////// error and exception handling
    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    public function exceptionHandler($exception)
    {
        $log = $this->getResource('log');
        $log->log(
                'Uncaught exception: ' . $exception->getMessage(),
                \Zend_Log::EMERG,
                array('trace' => $exception->getTraceAsString())
        );
    }
}
