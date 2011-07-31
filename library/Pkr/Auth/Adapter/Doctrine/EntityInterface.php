<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

interface Pkr_Auth_Adapter_Doctrine_EntityInterface
{
    public function verifyLogin($password);
}
