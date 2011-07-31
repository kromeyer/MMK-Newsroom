<?php

/**
 * @author      Patrick Kromeyer
 * @license     please view LICENSE file
 */

namespace Markdown;

require_once realpath(dirname(__FILE__) . '/markdown.php');

class Adapter
{
    public function markdown($text)
    {
        return Markdown($text);
    }
}
