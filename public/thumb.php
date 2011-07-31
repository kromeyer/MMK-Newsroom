<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

try
{
    if (APPLICATION_ENV == 'production')
    {
        $maxAge = 60 * 60 * 24; // 24h
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT');
        header('Cache-Control: max-age=' . $maxAge . ', must-revalidate');
    }
    else
    {
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
    }

    $application = new Zend_Application(
        APPLICATION_ENV,
        APPLICATION_PATH . '/configs/application.ini'
    );

    $application->getBootstrap()->bootstrap('autoloader');
    $doctrine = $application->getBootstrap()->bootstrap('doctrine')->doctrine;
    $cache = $application->getBootstrap()->bootstrap('cache')->cache;

    $thumbmaker = \Pkr_Thumbmaker::get('Doctrine');
    $thumbmaker->set('cache',   $cache)
               ->set('doctrine',$doctrine)
               ->set('entity',  '\Newsroom\Entity\File')
               ->set('quality', 100)
               ->set('id',      $_GET['id'])
               ->set('method',  $_GET['method'])
               ->set('height',  empty($_GET['height']) ? 5000 : $_GET['height'])
               ->set('width',   empty($_GET['width'])  ? 5000 : $_GET['width'])
               ->dump();
}
catch (\Exception $e)
{
    header('HTTP/1.1 500 Internal Server Error');

    if (APPLICATION_ENV != 'production')
    {
        echo $e->getMessage();
    }
}
