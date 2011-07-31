<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Controller_Plugin_Layout extends \Zend_Controller_Plugin_Abstract
{
    protected $_moduleLayouts;

    public function registerModuleLayout($module, $layoutPath, $layout=null)
    {
        $this->_moduleLayouts[$module] = array(
                'layoutPath' => $layoutPath,
                'layout' => $layout
        );
    }

    public function preDispatch(\Zend_Controller_Request_Abstract $request)
    {
        if (isset($this->_moduleLayouts[$request->getModuleName()]))
        {
            $config = $this->_moduleLayouts[$request->getModuleName()];
            $layout = \Zend_Layout::getMvcInstance();

            if ($layout->getMvcEnabled())
            {
                $layout->setLayoutPath($config['layoutPath']);

                if ($config['layout'] !== null)
                {
                    $layout->setLayout($config['layout']);
                }
            }
        }
    }
}
