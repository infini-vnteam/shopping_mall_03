<?php
/**
 * Created by PhpStorm.
 * User: isling
 * Date: 26/09/2017
 * Time: 21:33
 */

namespace ElasticSearch;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
