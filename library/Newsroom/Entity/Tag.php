<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

namespace Newsroom\Entity;

use Newsroom;

/**
 * @Entity (repositoryClass="Newsroom\Entity\Repository\TagRepository")
 * @Table (name="tag")
 */

class Tag extends Newsroom\EntityAbstract
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", name="tag_id")
     */
    protected $id;

    /**
     * @Column(type="string", name="tag_name", length=50)
     */
    protected $name;

    /**
     * @ManyToMany(targetEntity="News", mappedBy="tags")
     */
    protected $news;

    /**
     * @ManyToMany(targetEntity="Event", mappedBy="tags")
     */
    protected $events;

    /**
     * @ManyToMany(targetEntity="Announcement", mappedBy="tags")
     */
    protected $announcements;

    /**
     * @ManyToMany(targetEntity="Material", mappedBy="tags")
     */
    protected $materials;

    /**
     * @ManyToMany(targetEntity="MediaResponse", mappedBy="tags")
     */
    protected $mediaResponses;

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

    public function getNews()
    {
        return $this->news;
    }

    public function getEvents()
    {
        return $this->events;
    }

    public function getAnnouncements()
    {
        return $this->announcements;
    }

    public function getMaterials()
    {
        return $this->materials;
    }

    public function getMediaResponses()
    {
        return $this->mediaResponses;
    }

    public function toArray()
    {
        return array(
            'id'             => $this->getId(),
            'name'           => $this->getName(),
            'news'           => $this->getNews(),
            'events'         => $this->getEvents(),
            'announcements'  => $this->getAnnouncements(),
            'materials'      => $this->getMaterials(),
            'mediaResponses' => $this->getMediaResponses()
        );
    }

    public function __toString()
    {
        return $this->getName();
    }
}
