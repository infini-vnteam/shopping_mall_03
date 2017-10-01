<?php

namespace Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Controller\IndexController;
use Admin\Service\SaleProgramManager;
use Admin\Service\StatisticManager;
use ElasticSearch\Service\ElasticSearchManager;
use ElasticSearch\Service\ProductElasticSearchManager;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $saleProgramManager = $container->get(SaleProgramManager::class);
        $statisticManager = $container->get(StatisticManager::class);
        $elasticSearchManager = $container->get(ElasticSearchManager::class);
        $productElasticSearchManager = $container->get(ProductElasticSearchManager::class);

        return new IndexController(
            $entityManager,
            $saleProgramManager,
            $statisticManager,
            $elasticSearchManager,
            $productElasticSearchManager
        );
    }
}
