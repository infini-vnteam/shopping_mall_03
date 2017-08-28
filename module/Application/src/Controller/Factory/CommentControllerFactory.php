<?php

namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\CommentController;
use Application\Service\ProductManager;

/**
 * This is the factory for UserController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class CommentControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $sessionContainer = $container->get('UserLogin');
        $productManager = $container->get(ProductManager::class);

        // Instantiate the controller and inject dependencies
        return new CommentController($entityManager, $sessionContainer, $productManager);
    }
}
