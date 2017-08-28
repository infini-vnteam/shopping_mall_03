<?php
/**
 * Created by PhpStorm.
 * User: devil

 */

namespace Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Service\ProductManager;
use Admin\Service\CategoryManager;
use Admin\Service\StoreManager;
use Admin\Controller\ProductController;

/**
 * This is the factory for AdminController. Its purpose is to instantiate the
 * controller.
 */
class ProductControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->
            get('doctrine.entitymanager.orm_default');
        $productManager = $container->get(ProductManager::class);
        $categoryManager = $container->get(CategoryManager::class);
        $storeManager = $container->get(StoreManager::class);
        // Instantiate the controller and inject dependencies
        return new ProductController($entityManager, $productManager, $categoryManager, $storeManager);
    }
}
