<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Pkr_Controller_Action_Helper_Truncate extends \Zend_Controller_Action_Helper_Abstract
{
    public function direct($string, $length = 100, $ending = '...')
    {
        $pattern = '~(^.{1,' . $length . '}\s)?~';
        preg_match($pattern, $string, $matches);

        if (isset($matches[1]))
        {
            return $matches[1] . $ending;
        }

        return $string;
    }
}
