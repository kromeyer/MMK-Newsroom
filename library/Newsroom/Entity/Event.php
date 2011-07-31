<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

namespace Newsroom\Entity;

use Newsroom;

/**
 * @Entity (repositoryClass="Newsroom\Entity\Repository\EventRepository")
 * @Table (name="event")
 */

class Event extends Newsroom\EntityAbstract
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", name="event_id")
     */
    protected $id;

    /**
     * @Column(type="string", name="event_headline", length=90)
     */
    protected $headline;

    /**
     * @Column(type="string", name="event_url", length=90)
     */
    protected $url;

    /**
     * @Column(type="date", name="event_date")
     */
    protected $date;

    /**
     * @Column(type="time", name="event_time")
     */
    protected $time;

    /**
     * @Column(type="string", name="event_location", length=90)
     */
    protected $location;

    /**
     * @Column(type="text", name="event_content")
     */
    protected $content;

    /**
     * @OneToOne(targetEntity="File", cascade={"persist", "remove"}, orphanRemoval=true)
     * @JoinColumn(name="image_id", referencedColumnName="file_id", nullable=true)
     */
    protected $image;

    /**
     * @ManyToMany(targetEntity="Tag", inversedBy="events")
     * @JoinTable(
     *  name="event_tag",
     *  joinColumns={
     *      @JoinColumn(name="event_id", referencedColumnName="event_id")
     *  },
     *  inverseJoinColumns={
     *      @JoinColumn(name="tag_id", referencedColumnName="tag_id")
     *  }
     * )
     */
    protected $tags;

    /**
     * @OneToMany(targetEntity="Comment", mappedBy="event", cascade={"persist", "remove"})
     */
    protected $comments;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="user_id")
     */
    protected $user;

    /**
     * @Column(type="datetime", name="event_create")
     */
    protected $create;

    /**
     * @Column(type="datetime", name="event_update")
     */
    protected $update;

    public function __construct()
    {
        $this->create = new \DateTime();
        $this->update = $this->create;
    }

    protected function _buildUrl($string)
    {
        $filter = new \Pkr_Filter_Url();

        return $filter->filter($string);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getHeadline()
    {
        return $this->headline;
    }

    public function setHeadline($headline)
    {
        $this->headline = $headline;
        $this->url = $this->_buildUrl($headline);
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getDate($format = 'd.m.Y')
    {
        return $this->date->format($format);
    }

    public function setDate($date)
    {
        $this->date = new \DateTime($date);
    }

    public function getTime($format = 'H:i')
    {
        return $this->time->format($format);
    }

    public function setTime($time)
    {
        $this->time = new \DateTime($time);
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
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
            'headline'  => $this->getHeadline(),
            'url'       => $this->getUrl(),
            'date'      => $this->getDate(),
            'time'      => $this->getTime(),
            'location'  => $this->getLocation(),
            'content'   => $this->getContent(),
            'image'     => $this->getImage(),
            'tags'      => $this->getTags(),
            'comments'  => $this->getComments(),
            'create'    => $this->getCreate(),
            'update'    => $this->getUpdate()
        );
    }
}
