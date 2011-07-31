<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Pkr_Thumbmaker_Doctrine extends \Pkr_Thumbmaker_Abstract
{
    protected function _setInputData()
    {
        if (!isset($this->_config['doctrine']))
        {
            throw new \Pkr_Thumbmaker_Doctrine_Exception('doctrine missing');
        }

        if (!isset($this->_config['entity']))
        {
            throw new \Pkr_Thumbmaker_Doctrine_Exception('entity class name missing');
        }

        if (!isset($this->_config['id']))
        {
            throw new \Pkr_Thumbmaker_Doctrine_Exception('id missing');
        }

        $entity = $this->_config['doctrine']->getEntityManager()->find(
            $this->_config['entity'],
            $this->_config['id']
        );

        if (!$entity instanceof $this->_config['entity'])
        {
            throw new \Pkr_Thumbmaker_Doctrine_Exception('instanceof ' . $this->_config['entity'] . ' failed');
        }

        $this->_imageMimeType     = $entity->mimetype;
        $this->_imageResource     = imagecreatefromstring($entity->data);
        $this->_imageWidth        = imagesx($this->_imageResource);
        $this->_imageHeight       = imagesy($this->_imageResource);
        $this->_imageLastModified = $entity->update;
    }
}

class Pkr_Thumbmaker_Doctrine_Exception extends \Pkr_Thumbmaker_Abstract_Exception
{
}
