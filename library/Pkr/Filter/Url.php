<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Pkr_Filter_Url implements \Zend_Filter_Interface
{
    public function filter($value)
    {
        $value = strtolower($value);

        $replace = array (
                ' ' => '-',
                '_' => '-',
                'ä' => 'ae',
                'ü' => 'ue',
                'ö' => 'oe',
                'ß' => 'ss',
                '.' => '-'
        );

        $value = strtr($value, $replace);
        $value = preg_replace('~[^a-z0-9-\.]~is', '', $value);
        $value = preg_replace("~-+~is", '-', $value);
        $value = trim($value, '-');

        return $value;
    }
}
