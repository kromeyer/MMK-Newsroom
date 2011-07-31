<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Pkr_Controller_Action_Helper_EntityManager extends \Zend_Controller_Action_Helper_Abstract
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_entityManager = null;

    public function init()
    {
        $doctrine = \Zend_Controller_Front::getInstance()->getParam('bootstrap')
                                                         ->getResource('doctrine');

        $this->_entityManager = $doctrine->getEntityManager();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function direct()
    {
        return $this->_entityManager;
    }
}
