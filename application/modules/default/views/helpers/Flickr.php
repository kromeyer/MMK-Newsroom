<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Zend_View_Helper_Flickr extends \Pkr_View_Helper_ApiAbstract
{
    protected $_defaultOptions = null;
    protected $_flickr = null;

    public function __construct()
    {
        $bootstrap = \Zend_Controller_Front::getInstance()->getParam('bootstrap');

        $entityManager = $bootstrap->getResource('doctrine')->getEntityManager();
        $entity = $entityManager->getRepository('\Newsroom\Entity\Flickr')->fetchEntity();

        if ($entity && $entity->apiKey && $entity->username)
        {
            $this->_flickr = new \Zend_Rest_Client('http://api.flickr.com');
            $this->_defaultOptions = array (
                'api_key' => $entity->apiKey
            );

            if (strchr($entity->username, '@'))
            {
                $options = array (
                    'method'     => 'flickr.people.findByEmail',
                    'find_email' => $entity->username
                );
            }
            else
            {
                $options = array (
                    'method'    => 'flickr.people.findByUsername',
                    'username'  => $entity->username
                );
            }

            $options = array_merge(
                $this->_defaultOptions,
                $options
            );

            $xml = $this->_restGet($options);

            if ($xml && isset($xml->user['id']))
            {
                $this->_defaultOptions['user_id'] = (string) $xml->user['id'];
            }
        }
    }

    protected function _restGet(array $options = null)
    {
        $cacheKey = str_replace('.', '', $options['method']);

        if (self::$_cache && $xml = self::$_cache->load($cacheKey))
        {
            $xml = new \SimpleXMLElement($xml);
        }
        else
        {
            try
            {
                $response = $this->_flickr->restGet('/services/rest/', $options);
                $xml = new \SimpleXMLElement($response->getBody());

                if ($xml['stat'] == 'fail')
                {
                    throw new \Exception((string) $xml->err['msg']);
                }

                if (self::$_cache)
                {
                    self::$_cache->save($response->getBody(), $cacheKey);
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

        return $xml;
    }

    public function peopleGetId()
    {
        return isset($this->_defaultOptions['user_id']) ? $this->_defaultOptions['user_id'] : null;
    }

    public function peopleGetPublicPhotos(array $options = null)
    {
        $options = array_merge(
            $this->_defaultOptions,
            array (
                'method'    => 'flickr.people.getPublicPhotos',
                'per_page'  => 10,
                'page'      => 1,
                'extras'    => 'license, date_upload, date_taken, owner_name, icon_server'
            ),
            $options
        );

        return $this->_restGet($options);
    }

    public function flickr()
    {
        if ($this->_flickr && isset($this->_defaultOptions['user_id']))
        {
            return $this;
        }

        return null;
    }
}
