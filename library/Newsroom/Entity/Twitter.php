<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

namespace Newsroom\Entity;

use Newsroom;

/**
 * @Entity (repositoryClass="Newsroom\Entity\Repository\TwitterRepository")
 * @Table (name="twitter")
 */

class Twitter extends Newsroom\EntityAbstract
{
    /**
     * @Column(type="string", name="twitter_username", length=20)
     */
    protected $username;

    /**
     * @Id
     * @Column(type="string", name="twitter_key", length=255)
     */
    protected $consumerKey;

    /**
     * @Id
     * @Column(type="string", name="twitter_secret", length=255)
     */
    protected $consumerSecret;

    /**
     * @Column(type="text", name="twitter_token", nullable="true")
     */
    protected $accessToken;

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getConsumerKey()
    {
        return $this->consumerKey;
    }

    public function setConsumerKey($consumerKey)
    {
        $this->consumerKey = $consumerKey;
    }

    public function getConsumerSecret()
    {
        return $this->consumerSecret;
    }

    public function setConsumerSecret($consumerSecret)
    {
        $this->consumerSecret = $consumerSecret;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function toArray()
    {
        return array(
            'username'          => $this->getUsername(),
            'consumerKey'       => $this->getConsumerKey(),
            'consumerSecret'    => $this->getConsumerSecret(),
            'accessToken'       => $this->getAccessToken()
        );
    }
}
