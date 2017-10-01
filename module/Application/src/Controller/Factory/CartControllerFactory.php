<?php

namespace Application\Controller\Factory;

use Application\Service\CartManager;
use Application\Service\OrderManager;
use Application\Service\MailManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\CartController;

/**
 * This is the factory for CartController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class CartControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $cartManager = $container->get(CartManager::class);
        $orderManager = $container->get(OrderManager::class);
        $mailManager = $container->get(MailManager::class);
        // Instantiate the controller and inject dependencies
        return new CartController($entityManager, $cartManager, $orderManager, $mailManager);
    }
}
