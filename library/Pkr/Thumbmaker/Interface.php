<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

interface Pkr_Thumbmaker_Interface
{
    public function get($key);

    public function set($key, $value);

    public function dump();
}
