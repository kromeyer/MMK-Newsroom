<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Zend_View_Helper_Delicious extends \Pkr_View_Helper_ApiAbstract
{
    protected $_delicious = null;
    protected $_filter = null;

    public function __construct()
    {
        $bootstrap = \Zend_Controller_Front::getInstance()->getParam('bootstrap');

        $entityManager = $bootstrap->getResource('doctrine')->getEntityManager();
        $entity = $entityManager->getRepository('\Newsroom\Entity\Delicious')->fetchEntity();

        if ($entity && $entity->username && $entity->password)
        {
            $this->_delicious = new \Zend_Service_Delicious($entity->username, $entity->password);

            if ($entity->filter)
            {
                $this->_filter = $entity->filter;
            }
        }
    }

    public function __call($method, $params)
    {
        $cacheKey = 'delicious' . ucfirst($method) . md5(serialize($params));

        if (!self::$_cache || !$data = self::$_cache->load($cacheKey))
        {
            try
            {
                $data = call_user_func_array(array($this->_delicious, $method), $params);

                if (self::$_cache)
                {
                    self::$_cache->save($data, $cacheKey);
                }
            }
            catch (\Exception $e)
            {
                if (self::$_log)
                {
                    self::$_log->log(
                        $e->getMessage(),
                        \Zend_Log::ERR,
                        array('trace' => $e->getTraceAsString())
                    );
                }

                return null;
            }
        }

        return $data;
    }

    public function getFilter()
    {
        return $this->_filter;
    }

    public function delicious()
    {
        if ($this->_delicious)
        {
            return $this;
        }

        return null;
    }
}
