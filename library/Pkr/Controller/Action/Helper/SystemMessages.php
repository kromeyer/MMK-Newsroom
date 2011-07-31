<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Pkr_Controller_Action_Helper_SystemMessages extends \Zend_Controller_Action_Helper_Abstract
{
    protected $_session = null;

    public function init()
    {
        $this->_session = new \Zend_Session_Namespace('systemMessages');
    }

    public function direct($type, $message)
    {
        $array = $this->_session->$type;
        $array[] = $message;
        $this->_session->$type = $array;
    }
}
