<?php
namespace Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Controller\OrderController;
use Admin\Service\OrderManager;

class OrderControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container,	$requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $orderManager = $container->get(OrderManager::class);
		
        return new OrderController($entityManager, $orderManager);
    }
}
