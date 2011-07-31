<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

abstract class Pkr_Auth_Adapter_Doctrine_EntityAbstract
{
    protected $login;

    abstract function verifyLogin($password);
}
