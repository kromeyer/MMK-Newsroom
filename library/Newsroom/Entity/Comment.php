<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

namespace Newsroom\Entity;

use Newsroom;

/**
 * @Entity (repositoryClass="Newsroom\Entity\Repository\CommentRepository")
 * @Table (name="comment")
 */

class Comment extends Newsroom\EntityAbstract
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", name="comment_id")
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="Event", inversedBy="comments")
     * @JoinColumn(name="event_id", referencedColumnName="event_id")
     */
    protected $event;

    /**
     * @ManyToOne(targetEntity="News", inversedBy="comments")
     * @JoinColumn(name="news_id", referencedColumnName="news_id")
     */
    protected $news;

    /**
     * @Column(type="string", name="comment_name", length=30)
     */
    protected $name;

    /**
     * @Column(type="string", name="comment_email", length=30)
     */
    protected $email;

    /**
     * @Column(type="text", name="comment_content")
     */
    protected $content;

    /**
     * @Column(type="datetime", name="comment_create")
     */
    protected $create;

    public function __construct()
    {
        $this->create = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function setEvent($event)
    {
        $this->event = $event;
    }

    public function getNews()
    {
        return $this->news;
    }

    public function setNews($news)
    {
        $this->news = $news;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getCreate($format = 'd.m.Y')
    {
        return $this->create->format($format);
    }

    public function setCreate($create)
    {
        $this->create = $create;
    }

    public function toArray()
    {
        return array(
            'id'        => $this->getId(),
            'news'      => $this->getNews(),
            'event'     => $this->getEvent(),
            'name'      => $this->getName(),
            'email'     => $this->getEmail(),
            'content'   => $this->getContent(),
            'create'    => $this->getCreate()
        );
    }
}
