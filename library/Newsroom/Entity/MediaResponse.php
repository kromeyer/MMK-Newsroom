<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

namespace Newsroom\Entity;

use Newsroom;

/**
 * @Entity (repositoryClass="Newsroom\Entity\Repository\MediaResponseRepository")
 * @Table (name="media_response")
 */

class MediaResponse extends Newsroom\EntityAbstract
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", name="media_response_id")
     */
    protected $id;

    /**
     * @Column(type="string", name="media_response_title", length=90)
     */
    protected $title;

    /**
     * @OneToOne(targetEntity="File", cascade={"persist", "remove"}, orphanRemoval=true)
     * @JoinColumn(name="image_id", referencedColumnName="file_id")
     */
    protected $image;

    /**
     * @OneToOne(targetEntity="File", cascade={"persist", "remove"}, orphanRemoval=true)
     * @JoinColumn(name="file_id", referencedColumnName="file_id")
     */
    protected $file;

    /**
     * @ManyToMany(targetEntity="Tag", inversedBy="mediaResponses")
     * @JoinTable(
     *  name="media_response_tag",
     *  joinColumns={
     *      @JoinColumn(name="media_response_id", referencedColumnName="media_response_id")
     *  },
     *  inverseJoinColumns={
     *      @JoinColumn(name="tag_id", referencedColumnName="tag_id")
     *  }
     * )
     */
    protected $tags;

    /**
     * @Column(type="datetime", name="media_response_create")
     */
    protected $create;

    /**
     * @Column(type="datetime", name="media_response_update")
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

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
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
            'title'     => $this->getTitle(),
            'image'     => $this->getImage(),
            'file'      => $this->getFile(),
            'tags'      => $this->getTags(),
            'create'    => $this->getCreate(),
            'update'    => $this->getUpdate()
        );
    }
}
