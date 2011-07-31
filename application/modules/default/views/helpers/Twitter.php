<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Zend_View_Helper_Twitter extends \Pkr_View_Helper_ApiAbstract
{
    protected $_twitter = null;

    public function __construct()
    {
        $doctrine = \Zend_Controller_Front::getInstance()->getParam('bootstrap')
                                                         ->getResource('doctrine');
        $entityManager = $doctrine->getEntityManager();
        $entity = $entityManager->getRepository('\Newsroom\Entity\Twitter')->fetchEntity();

        if ($entity && $entity->username && $entity->accessToken)
        {
            $this->_twitter = new \Zend_Service_Twitter(array(
                                                'username'    => $entity->username,
                                                'accessToken' => unserialize($entity->accessToken)
            ));
        }
    }

    public function __call($method, $params)
    {
        $cacheKey = 'twitter' . ucfirst($method) . md5(serialize($params));

        if (self::$_cache && $xml = self::$_cache->load($cacheKey))
        {
            $xml = new \SimpleXMLElement($xml);
        }
        else
        {
            try
            {
                $xml = call_user_func_array(array($this->_twitter, $method), $params);

                if (self::$_cache)
                {
                    self::$_cache->save($xml->asXML, $cacheKey);
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

    /**
     * @author      snipe
     * @license     CC BY-NC-SA 3.0
     * @link        http://www.snipe.net/2009/09/php-twitter-clickable-links
     */
    public function clickableLinks($ret)
    {
        $ret = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);
        $ret = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
        $ret = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" target=\"_blank\">@\\1</a>", $ret);
        $ret = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" target=\"_blank\">#\\1</a>", $ret);

        return $ret;
    }

    public function twitter()
    {
        if ($this->_twitter)
        {
            return $this;
        }

        return null;
    }
}
