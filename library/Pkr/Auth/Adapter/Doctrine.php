<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Pkr_Auth_Adapter_Doctrine implements \Zend_Auth_Adapter_Interface
{
    protected $_entity = null;
    protected $_entityRepository = null;
    protected $_identity = null;
    protected $_credential = null;

    public function __construct(\Doctrine\ORM\EntityRepository $repository)
    {
        $this->_entityRepository = $repository;
    }

    protected function _createQuery()
    {
        $qb = $this->_entityRepository->createQueryBuilder('u');
        $qb->add('where', 'u.login = :login')
           ->setParameter('login', $this->_identity);

        return $qb->getQuery();
    }

    public function setIdentity($value)
    {
        $this->_identity = $value;
        return $this;
    }

    public function setCredential($value)
    {
        $this->_credential = $value;
        return $this;
    }

    /**
     * @throws Pkr_Auth_Adapter_Doctrine_Exception if authentication cannot be performed
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {
        $result = $this->_createQuery()->getResult();

        if (count($result) < 1)
        {
            return new \Zend_Auth_Result(
                \Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,
                null,
                array('A record with the supplied identity could not be found.')
            );
        }
        elseif (count($result) > 1)
        {
            return new \Zend_Auth_Result(
                \Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS,
                null,
                array('More than one record matches the supplied identity.')
            );
        }
        else
        {
            $this->_entity = current($result);

            if (!($this->_entity instanceof \Pkr_Auth_Adapter_Doctrine_EntityAbstract
                  || $this->_entity instanceof \Pkr_Auth_Adapter_Doctrine_EntityInterface))
            {
                throw new \Pkr_Auth_Adapter_Doctrine_Exception(
                    'instanceof Pkr_Auth_Adapter_Doctrine_EntityAbstract or Pkr_Auth_Adapter_Doctrine_EntityInterface failed'
                );
            }

            if (!$this->_entity->verifyLogin($this->_credential))
            {
                return new \Zend_Auth_Result(
                    \Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,
                    null,
                    array('Supplied credential is invalid.')
                );
            }
        }

        return new \Zend_Auth_Result(
            \Zend_Auth_Result::SUCCESS,
            $this->_entity,
            array('Authentication successful.')
        );
    }

    public function getEntity()
    {
        return $this->_entity;
    }
}

class Pkr_Auth_Adapter_Doctrine_Exception extends \Zend_Auth_Adapter_Exception
{
}
