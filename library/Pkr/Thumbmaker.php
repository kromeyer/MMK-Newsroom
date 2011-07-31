<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

define('PATH_THUMBMAKER', realpath(dirname(__FILE__) . '/Thumbmaker'));

class Pkr_Thumbmaker
{
    static protected $_objects = array();

    static public function get($class)
    {
        if (!is_string($class) || !trim($class))
        {
            throw new \Pkr_Thumbmaker_Exception('no valid class name');
        }

        if (!isset(Pkr_Thumbmaker::$_objects[$class]))
        {
            $file = PATH_THUMBMAKER . '/' . $class . '.php';

            if (!file_exists($file))
            {
                throw new Pkr_Thumbmaker_Exception("file '$file' do not exist");
            }

            require_once $file;

            $className = 'Pkr_Thumbmaker_' . $class;

            Pkr_Thumbmaker::$_objects[$class] = new $className();
        }

        return Pkr_Thumbmaker::$_objects[$class];
    }
}

class Pkr_Thumbmaker_Exception extends \Exception
{
}
