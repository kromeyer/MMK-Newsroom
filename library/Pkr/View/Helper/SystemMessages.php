<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Pkr_View_Helper_SystemMessages extends \Zend_View_Helper_Abstract
{
    protected $_session = null;

    public function __construct()
    {
        $this->_session = new \Zend_Session_Namespace('systemMessages');
    }

    public function systemMessages($type, $unset = true)
    {
        $returnMessage = null;

        if ($this->_session->$type)
        {
            $returnMessage = $this->_session->$type;
        }

        if ($unset)
        {
            unset($this->_session->$type);
        }

        return $returnMessage;
    }
}
