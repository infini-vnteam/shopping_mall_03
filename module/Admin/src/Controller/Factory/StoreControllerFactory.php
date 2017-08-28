<?php
/**
 * Created by PhpStorm.
 * User: vu truong

 */

namespace Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Service\StoreManager;
use Admin\Controller\StoreController;

/**
 * This is the factory for AdminController. Its purpose is to instantiate the
 * controller.
 */
class StoreControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->
            get('doctrine.entitymanager.orm_default');
        $storeManager = $container->get(StoreManager::class);
        // Instantiate the controller and inject dependencies
        
        return new StoreController($entityManager, $storeManager);
    }
}