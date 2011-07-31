<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

namespace Newsroom\Entity;

use Newsroom;

/**
 * @Entity (repositoryClass="Newsroom\Entity\Repository\UserRepository")
 * @Table (name="user")
 */

class User extends Newsroom\EntityAbstract
        implements \Pkr_Auth_Adapter_Doctrine_EntityInterface
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", name="user_id")
     */
    protected $id;

    /**
     * @Column(type="string", name="user_login", length=90)
     */
    protected $login;

    /**
     * @Column(type="string", name="user_password", length=90)
     */
    protected $password;

    /**
     * @Column(type="string", name="user_title", length=30)
     */
    protected $title;

    /**
     * @Column(type="string", name="user_firstname", length=30)
     */
    protected $firstname;

    /**
     * @Column(type="string", name="user_lastname", length=30)
     */
    protected $lastname;

    /**
     * @Column(type="boolean", name="user_disabled")
     */
    protected $disabled = false;

    protected function _encrypt($value)
    {
        return \sha1($value);
    }

    public function verifyLogin($value)
    {
        return $this->password === $this->_encrypt($value)
               && $this->disabled === false;
    }

    public function setPassword($value)
    {
        if (!empty ($value))
        {
            $this->password = $this->_encrypt($value);
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    public function getDisabled()
    {
        return $this->disabled;
    }

    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
    }

    public function toArray()
    {
        return array(
            'id'        => $this->getId(),
            'login'     => $this->getLogin(),
            'title'     => $this->getTitle(),
            'firstname' => $this->getFirstname(),
            'lastname'  => $this->getLastname(),
            'disabled'  => $this->getDisabled()
        );
    }
}
