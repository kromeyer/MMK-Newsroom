<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

namespace Newsroom;

abstract class EntityAbstract
{
    protected static $_entityManager = null;

    public static function setEntityManager($entityManager)
    {
        self::$_entityManager = $entityManager;
    }

    public function __get($name)
    {
        $methodName = 'get' . ucfirst($name);

        if (!method_exists($this, $methodName))
        {
            throw new \Exception('"' . $methodName . '" does not exist');
        }

        return $this->$methodName();
    }

    public function __set($name, $value)
    {
        $methodName = 'set' . ucfirst($name);

        if (!method_exists($this, $methodName))
        {
            throw new \Exception('"' . $methodName . '" does not exist');
        }

        $this->$methodName($value);
    }

    public function setFromArray(array $values)
    {
        foreach ($values as $name => $value)
        {
            $this->__set($name, $value);
        }
    }

    abstract public function toArray();
}
