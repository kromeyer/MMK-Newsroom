<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

namespace Newsroom\Entity;

use Newsroom;

/**
 * @Entity (repositoryClass="Newsroom\Entity\Repository\YoutubeRepository")
 * @Table (name="youtube")
 */

class Youtube extends Newsroom\EntityAbstract
{
    /**
     * @Id
     * @Column(type="string", name="youtube_username", length=20)
     */
    protected $username;

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function toArray()
    {
        return array(
            'username'  => $this->getUsername()
        );
    }
}
