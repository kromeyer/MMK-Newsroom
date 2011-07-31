<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Form_TagForm extends \Zend_Form
{
    /**
     * @var \Zend_Config
     */
    protected $_config = null;

    /**
     * @var \Newsroom\Entity\Repository\CrudRepository
     */
    protected $_tagRepository = null;

    public function __construct(\Zend_Config $options)
    {
        if (!$options instanceof \Zend_Config)
        {
            throw new \Exception('instanceof \Zend_Config failed');
        }

        parent::__construct($options);

        $this->_config = $options;

        $doctrine = \Zend_Controller_Front::getInstance()->getParam('bootstrap')
                                                         ->getResource('doctrine');
        $this->_tagRepository = $doctrine->getEntityManager()
                                         ->getRepository('\Newsroom\Entity\Tag');

        $this->_addTags();
    }

    protected function _addTags()
    {
        $tags = $this->_tagRepository->fetchEntities();
        $group = current($this->getDisplayGroups());

        foreach ($tags as $tag)
        {
            $element = new \Zend_Form_Element_Checkbox(
                'tag_' . $tag->id,
                array(
                    'class' => 'checkbox',
                    'label' => $tag->name
                )
            );
            $element->setDecorators(
                $this->_config->elementDecorators->toArray()
            );

            $this->addElement($element);
            $group->addElement($element);
        }
    }

    public function setDefaults(array $defaults)
    {
        foreach ($defaults['tags'] as $tag)
        {
            $defaults['tag_' . $tag->id] = '1';
        }
        unset($defaults['tags']);

        return parent::setDefaults($defaults);
    }

    public function getValues($suppressArrayNotation = false)
    {
        $values = parent::getValues($suppressArrayNotation);

        $tags = array();
        foreach ($values as $key => $value)
        {
            if (preg_match('~^tag_(\d+)$~', $key, $matches))
            {
                if ($value == 1)
                {
                    $tags[] = $this->_tagRepository->fetchEntity($matches[1]);
                }
                unset($values[$key]);
            }
        }
        $values['tags'] = $tags;

        return $values;
    }
}
