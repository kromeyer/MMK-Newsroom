<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Pkr_Log_Formatter_Advanced extends \Zend_Log_Formatter_Simple
{
    public function __construct()
    {
        $format  = '';
        $format .= 'Timestamp:          %timestamp%' . PHP_EOL;
        $format .= 'Priority:           %priorityName% (%priority%)' . PHP_EOL;
        $format .= 'Message:            %message%' . PHP_EOL;
        $format .= PHP_EOL;

        $format .= 'Hostname:           ' . php_uname('n') . PHP_EOL;
        $format .= 'APPLICATION_ENV:    ' . APPLICATION_ENV . PHP_EOL;
        $format .= 'APPLICATION_PATH:   ' . APPLICATION_PATH. PHP_EOL;
        $format .= PHP_EOL;

        $format .= 'Trace:' . PHP_EOL;
        $format .= '%trace%' . PHP_EOL;
        $format .= PHP_EOL;

        $format .= '$_SERVER:' . PHP_EOL;
        $format .= var_export($_SERVER, true) . PHP_EOL;
        $format .= PHP_EOL;

        $format .= '$_REQUEST:' . PHP_EOL;
        $format .= var_export($_REQUEST, true) . PHP_EOL;
        $format .= PHP_EOL;

        $format .= '$_GET:' . PHP_EOL;
        $format .= var_export($_GET, true) . PHP_EOL;
        $format .= PHP_EOL;

        $format .= '$_POST:' . PHP_EOL;
        $format .= var_export($_POST, true) . PHP_EOL;
        $format .= PHP_EOL;

        $format .= '$_COOKIE:' . PHP_EOL;
        $format .= var_export($_COOKIE, true) . PHP_EOL;
        $format .= PHP_EOL;

        $format .= '$_ENV:' . PHP_EOL;
        $format .= var_export($_ENV, true) . PHP_EOL;
        $format .= PHP_EOL;

        parent::__construct($format);
    }
}
