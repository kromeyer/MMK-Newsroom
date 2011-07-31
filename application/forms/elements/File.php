<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Form_Element_File extends \Zend_Form_Element_File
{
    /**
     * @var \Newsroom\Entity\Repository\CrudRepository
     */
    protected $_fileRepository = null;

    /**
     * @var \Newsroom\Entity\File
     */
    protected $_orginValue = null;

    public function __construct($spec, $options = null)
    {
        parent::__construct($spec, $options);

        $doctrine = \Zend_Controller_Front::getInstance()->getParam('bootstrap')
                                                         ->getResource('doctrine');

        $this->_fileRepository = $doctrine->getEntityManager()
                                          ->getRepository('\Newsroom\Entity\File');
    }

    public function getOrginValue()
    {
        return $this->_orginValue;
    }

    public function setOrginValue($orginValue)
    {
        $this->_orginValue = $orginValue;
        $this->_value = $this->_orginValue;

        if ($this->_orginValue)
        {
            $this->disabled = 'disabled';
        }
        else
        {
            unset ($this->disabled);
        }
    }

    public function getValue()
    {
        if ($this->isUploaded())
        {
            // new id, if i always fetch a new entity for every upload
            if ($this->_value === null)
            {
                $this->_value = $this->_fileRepository->fetchNew();
            }
            else
            {
                $this->_value->update   = new \DateTime();
            }

            $info = $this->getFileInfo();

            $this->_value->name     = $info[$this->_name]['name'];
            $this->_value->mimetype = $info[$this->_name]['type'];
            $this->_value->data     = file_get_contents($info[$this->_name]['tmp_name']);
            $this->_value->size     = $info[$this->_name]['size'];
        }
        else if (isset($_FILES[$this->getName()]))
        {
            $this->_value = null;
        }

        return $this->_value;
    }

    public function setValue($value)
    {
        $this->_value = $value;

        return $this;
    }

    public function isValid($value, $context = null)
    {
        if (!($this->_orginValue === null || isset($_FILES[$this->getName()])))
        {
            $this->_validated = true;
            return true;
        }

        return parent::isValid($value, $context);
    }

    public function render(Zend_View_Interface $view = null)
    {
        if ($this->_isPartialRendering) {
            return '';
        }

        if (null !== $view) {
            $this->setView($view);
        }

        $content = '';
        foreach ($this->getDecorators() as $decorator) {
            $decorator->setElement($this);
            $content = $decorator->render($content);
        }
        return $content;
    }
}
