<?php

namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\HomeController;
use Application\Service\CategoryManager;
use Application\Service\ProductManager;
use Application\Service\SqlManager;
use ElasticSearch\Service\ElasticSearchManager;

/**
 * This is the factory for HomeController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class HomeControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $categoryManager = $container->get(CategoryManager::class);
        $productManager = $container->get(ProductManager::class);
        $sqlManager = $container->get(SqlManager::class);
        $elasticSearchManager = $container->get(ElasticSearchManager::class);
        $sessionContainer = $container->get('UserLogin');
        // Instantiate the controller and inject dependencies
        return new HomeController(
            $entityManager,
            $categoryManager,
            $productManager,
            $sqlManager,
            $elasticSearchManager,
            $sessionContainer
        );
    }
}
