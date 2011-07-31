<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

namespace Newsroom\Entity;

use Newsroom;

/**
 * @Entity (repositoryClass="Newsroom\Entity\Repository\FlickrRepository")
 * @Table (name="flickr")
 */

class Flickr extends Newsroom\EntityAbstract
{
    /**
     * @Id
     * @Column(type="string", name="flickr_username", length=30)
     */
    protected $username;

    /**
     * @Column(type="string", name="flickr_apikey", length=90)
     */
    protected $apiKey;

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function toArray()
    {
        return array(
            'username'  => $this->getUsername(),
            'apiKey'    => $this->getApiKey()
        );
    }
}
