<?php

namespace Admin\Helper;

use Zend\View\Helper\AbstractHelper;

class TrunCate extends AbstractHelper
{
    public function __invoke($str, $length)
    {
        if (strlen($str) > $length) {
            $str = $str . " ";
            $str = substr($str, 0, $length);
            $str = $str . "...";
        }
        return $str;
    }
}