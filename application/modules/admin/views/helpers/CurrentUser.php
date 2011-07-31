<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Zend_View_Helper_CurrentUser extends \Zend_View_Helper_Abstract
{
    protected $_user = null;

    public function __construct()
    {
        if (\Zend_Auth::getInstance()->hasIdentity())
        {
            $this->_user = \Zend_Auth::getInstance()->getIdentity();
        }
    }

    public function currentUser()
    {
        return $this->_user;
    }
}
