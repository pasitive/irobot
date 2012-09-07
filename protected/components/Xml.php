<?php
/**
 * Created by JetBrains PhpStorm.
 * User: denisboldinov
 * Date: 10/19/11
 * Time: 4:03 PM
 * To change this template use File | Settings | File Templates.
 */

class Xml extends DOMDocument
{
    public function __construct()
    {
        parent::__construct('1.0', 'utf-8');
        $this->init();
    }

    protected function init()
    {
        $this->preserveWhiteSpace = false;
        $this->formatOutput = true;
    }
}
