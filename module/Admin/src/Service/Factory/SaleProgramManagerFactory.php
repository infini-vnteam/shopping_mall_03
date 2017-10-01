<?php
namespace Admin\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Service\SaleProgramManager;

/**
 * This is the factory for PostManager. Its purpose is to instantiate the
 * service.
 */
class SaleProgramManagerFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        array $options = null
        )
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        // Instantiate the service and inject dependencies
        return new SaleProgramManager($entityManager);
    }
}
