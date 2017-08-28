<?php

namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\ProductController;
use Application\Service\ProductManager;

/**
 * This is the factory for UserController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class ProductControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $sessionContainer = $container->get('UserLogin');
        $productManager = $container->get(ProductManager::class);

        // Instantiate the controller and inject dependencies
        return new ProductController($entityManager, $sessionContainer, $productManager);
    }
}
