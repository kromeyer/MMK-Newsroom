<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

class Form_Element_SpamDetection extends \Zend_Form_Element_Text
{
    public function isValid($value, $context = null)
    {
        $this->setValue($value);

        return empty($value);
    }
}
