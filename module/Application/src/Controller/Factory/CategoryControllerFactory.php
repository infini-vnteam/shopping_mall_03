<?php

namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\CategoryController;
use Application\Service\CategoryManager;
/**
 * This is the factory for UserController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class CategoryControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $sessionContainer = $container->get('UserLogin');
        $categoryManager = $container->get(CategoryManager::class);
        // Instantiate the controller and inject dependencies
        return new CategoryController($entityManager, $sessionContainer, $categoryManager);
    }
}
