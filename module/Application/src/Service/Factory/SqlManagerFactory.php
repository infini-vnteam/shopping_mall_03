<?php
namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\SqlManager;

/**
 * This is the factory class for CategoryManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class SqlManagerFactory
{
    /**
     * This method creates the CategoryManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
                        
        return new SqlManager($entityManager);
    }
}
