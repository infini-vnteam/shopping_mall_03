<?php
/**
 * Created by PhpStorm.
 * User: isling
 * Date: 26/09/2017
 * Time: 21:33
 */

namespace ElasticSearch;

return [
    'service_manager' => [
        'factories' => [
            Service\ElasticSearchManager::class => Service\Factory\ElasticSearchManagerFactory::class,
            Service\ProductElasticSearchManager::class => Service\Factory\ElasticSearchManagerFactory::class,
        ]
    ],
];
