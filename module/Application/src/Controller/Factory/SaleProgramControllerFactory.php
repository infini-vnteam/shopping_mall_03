<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Controller\SaleProgramController;

class SaleProgramControllerFactory implements FactoryInterface
{
    public function __invoke(
    	ContainerInterface $container,
    	$requestedName,
    	array $options = null
    	)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
		
        return new SaleProgramController($entityManager);
    }
}
