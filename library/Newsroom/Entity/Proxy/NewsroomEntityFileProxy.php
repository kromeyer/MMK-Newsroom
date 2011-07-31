<?php

namespace Newsroom\Entity\Proxy;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class NewsroomEntityFileProxy extends \Newsroom\Entity\File implements \Doctrine\ORM\Proxy\Proxy
{
    private $_entityPersister;
    private $_identifier;
    public $__isInitialized__ = false;
    public function __construct($entityPersister, $identifier)
    {
        $this->_entityPersister = $entityPersister;
        $this->_identifier = $identifier;
    }
    private function _load()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            if ($this->_entityPersister->load($this->_identifier, $this) === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            unset($this->_entityPersister, $this->_identifier);
        }
    }

    
    public function getId()
    {
        $this->_load();
        return parent::getId();
    }

    public function setId($id)
    {
        $this->_load();
        return parent::setId($id);
    }

    public function getName()
    {
        $this->_load();
        return parent::getName();
    }

    public function setName($name)
    {
        $this->_load();
        return parent::setName($name);
    }

    public function getMimetype()
    {
        $this->_load();
        return parent::getMimetype();
    }

    public function setMimetype($mimetype)
    {
        $this->_load();
        return parent::setMimetype($mimetype);
    }

    public function getData()
    {
        $this->_load();
        return parent::getData();
    }

    public function setData($data)
    {
        $this->_load();
        return parent::setData($data);
    }

    public function getSize()
    {
        $this->_load();
        return parent::getSize();
    }

    public function setSize($size)
    {
        $this->_load();
        return parent::setSize($size);
    }

    public function getCreate($format = 'd.m.Y')
    {
        $this->_load();
        return parent::getCreate($format);
    }

    public function setCreate($create)
    {
        $this->_load();
        return parent::setCreate($create);
    }

    public function getUpdate($format = 'd.m.Y')
    {
        $this->_load();
        return parent::getUpdate($format);
    }

    public function setUpdate($update)
    {
        $this->_load();
        return parent::setUpdate($update);
    }

    public function toArray()
    {
        $this->_load();
        return parent::toArray();
    }

    public function __get($name)
    {
        $this->_load();
        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        $this->_load();
        return parent::__set($name, $value);
    }

    public function setFromArray(array $values)
    {
        $this->_load();
        return parent::setFromArray($values);
    }


    public function __sleep()
    {
        return array('__isInitialized__', 'id', 'name', 'mimetype', 'data', 'size', 'create', 'update');
    }

    public function __clone()
    {
        if (!$this->__isInitialized__ && $this->_entityPersister) {
            $this->__isInitialized__ = true;
            $class = $this->_entityPersister->getClassMetadata();
            $original = $this->_entityPersister->load($this->_identifier);
            if ($original === null) {
                throw new \Doctrine\ORM\EntityNotFoundException();
            }
            foreach ($class->reflFields AS $field => $reflProperty) {
                $reflProperty->setValue($this, $reflProperty->getValue($original));
            }
            unset($this->_entityPersister, $this->_identifier);
        }
        
    }
}