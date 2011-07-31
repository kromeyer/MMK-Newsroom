<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

namespace Newsroom\Entity;

use Newsroom;

/**
 * @Entity (repositoryClass="Newsroom\Entity\Repository\FileRepository")
 * @Table (name="file")
 */

class File extends Newsroom\EntityAbstract
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", name="file_id")
     */
    protected $id;

    /**
     * @Column(type="string", name="file_name", length=255)
     */
    protected $name;

    /**
     * @Column(type="string", name="file_mimetype", length=90)
     */
    protected $mimetype;

    /**
     * @Column(type="text", name="file_data")
     */
    protected $data;

    /**
     * @Column(type="integer", name="file_size")
     */
    protected $size;

    /**
     * @Column(type="datetime", name="file_create")
     */
    protected $create;

    /**
     * @Column(type="datetime", name="file_update")
     */
    protected $update;

    public function __construct()
    {
        $this->create = new \DateTime();
        $this->update = $this->create;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getMimetype()
    {
        return $this->mimetype;
    }

    public function setMimetype($mimetype)
    {
        $this->mimetype = $mimetype;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }

    public function getCreate($format = 'd.m.Y')
    {
        return $this->create->format($format);
    }

    public function setCreate($create)
    {
        $this->create = $create;
    }

    public function getUpdate($format = 'd.m.Y')
    {
        return $this->update->format($format);
    }

    public function setUpdate($update)
    {
        $this->update = $update;
    }

    public function toArray()
    {
        return array(
            'id'        => $this->getId(),
            'name'      => $this->getName(),
            'mimetype'  => $this->getMimetype(),
            'data'      => $this->getData(),
            'size'      => $this->getSize(),
            'create'    => $this->getCreate(),
            'update'    => $this->getUpdate()
        );
    }
}
