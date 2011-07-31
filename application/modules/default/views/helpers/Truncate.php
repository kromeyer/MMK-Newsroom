<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Zend_View_Helper_Truncate extends \Zend_View_Helper_Abstract
{
    public function truncate($string, $length = 100, $ending = '...')
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
