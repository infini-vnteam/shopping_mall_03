<?php
namespace Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Controller\SaleProgramController;
use Admin\Service\SaleProgramManager;

class SaleProgramControllerFactory implements FactoryInterface
{
    public function __invoke(
    	ContainerInterface $container,
    	$requestedName,
    	array $options = null
    	)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $saleProgramManager = $container->get(SaleProgramManager::class);
		
        return new SaleProgramController($entityManager, $saleProgramManager);
    }
}
