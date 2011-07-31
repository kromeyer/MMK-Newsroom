<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Zend_View_Helper_Youtube extends \Pkr_View_Helper_ApiAbstract
{
    protected $_youtube = null;
    protected $_username = null;

    public function __construct()
    {
        $bootstrap = \Zend_Controller_Front::getInstance()->getParam('bootstrap');

        $entityManager = $bootstrap->getResource('doctrine')->getEntityManager();
        $entity = $entityManager->getRepository('\Newsroom\Entity\Youtube')->fetchEntity();

        if ($entity && $entity->username)
        {
            $this->_youtube = new \Zend_Gdata_YouTube();
            $this->_username = $entity->username;
        }
    }

    public function __call($method, $params)
    {
        $cacheKey = 'youtube' . ucfirst($method) . md5(serialize($params));

        if (!self::$_cache || !$data = self::$_cache->load($cacheKey))
        {
            try
            {
                $data = call_user_func_array(array($this->_youtube, $method), $params);

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

    public function getUsername()
    {
        return $this->_username;
    }

    public function youtube()
    {
        if ($this->_youtube)
        {
            return $this;
        }

        return null;
    }
}
