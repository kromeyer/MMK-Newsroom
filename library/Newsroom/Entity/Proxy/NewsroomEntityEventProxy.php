<?php

namespace Newsroom\Entity\Proxy;

/**
 * THIS CLASS WAS GENERATED BY THE DOCTRINE ORM. DO NOT EDIT THIS FILE.
 */
class NewsroomEntityEventProxy extends \Newsroom\Entity\Event implements \Doctrine\ORM\Proxy\Proxy
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

    public function getHeadline()
    {
        $this->_load();
        return parent::getHeadline();
    }

    public function setHeadline($headline)
    {
        $this->_load();
        return parent::setHeadline($headline);
    }

    public function getUrl()
    {
        $this->_load();
        return parent::getUrl();
    }

    public function getDate($format = 'd.m.Y')
    {
        $this->_load();
        return parent::getDate($format);
    }

    public function setDate($date)
    {
        $this->_load();
        return parent::setDate($date);
    }

    public function getTime($format = 'H:i')
    {
        $this->_load();
        return parent::getTime($format);
    }

    public function setTime($time)
    {
        $this->_load();
        return parent::setTime($time);
    }

    public function getLocation()
    {
        $this->_load();
        return parent::getLocation();
    }

    public function setLocation($location)
    {
        $this->_load();
        return parent::setLocation($location);
    }

    public function getContent()
    {
        $this->_load();
        return parent::getContent();
    }

    public function setContent($content)
    {
        $this->_load();
        return parent::setContent($content);
    }

    public function getImage()
    {
        $this->_load();
        return parent::getImage();
    }

    public function setImage($image)
    {
        $this->_load();
        return parent::setImage($image);
    }

    public function getTags()
    {
        $this->_load();
        return parent::getTags();
    }

    public function setTags($tags)
    {
        $this->_load();
        return parent::setTags($tags);
    }

    public function getComments()
    {
        $this->_load();
        return parent::getComments();
    }

    public function getUser()
    {
        $this->_load();
        return parent::getUser();
    }

    public function setUser($user)
    {
        $this->_load();
        return parent::setUser($user);
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
        return array('__isInitialized__', 'id', 'headline', 'url', 'date', 'time', 'location', 'content', 'image', 'tags', 'comments', 'user', 'create', 'update');
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