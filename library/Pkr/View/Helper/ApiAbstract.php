<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Pkr_View_Helper_ApiAbstract extends \Zend_View_Helper_Abstract
{
    /**
     * @var \Zend_Cache_Core
     */
    protected static $_cache = null;

    /**
     * @var \Zend_Log
     */
    protected static $_log = null;

    public static function setCache(\Zend_Cache_Core $cache)
    {
        self::$_cache = $cache;
    }

    public static function setLog(\Zend_Log $log)
    {
        self::$_log = $log;
    }
}
