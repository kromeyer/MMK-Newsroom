<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Zend_View_Helper_Markdown extends \Zend_View_Helper_Abstract
{
    protected $_markdown = null;

    public function __construct()
    {
        $this->_markdown = new \Markdown\Adapter();
    }

    public function markdown($string)
    {
        return $this->_markdown->markdown($string);
    }
}
