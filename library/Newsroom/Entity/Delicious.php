<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

namespace Newsroom\Entity;

use Newsroom;

/**
 * @Entity (repositoryClass="Newsroom\Entity\Repository\DeliciousRepository")
 * @Table (name="delicious")
 */

class Delicious extends Newsroom\EntityAbstract
{
    /**
     * @Id
     * @Column(type="string", name="delicious_username", length=20)
     */
    protected $username;

    /**
     * @Column(type="string", name="delicious_password", length=30)
     */
    protected $password;

    /**
     * @Column(type="string", name="delicious_filter", length=30)
     */
    protected $filter;

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getFilter()
    {
        return $this->filter;
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }

    public function toArray()
    {
        return array(
            'username'  => $this->getUsername(),
            'password'  => $this->getPassword(),
            'filter'    => $this->getFilter()
        );
    }
}
