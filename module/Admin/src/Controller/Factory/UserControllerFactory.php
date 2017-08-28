<?php
/**
 * Created by PhpStorm.
 * User: devil

 */

namespace Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Controller\UserController;

/**
 * This is the factory for AdminController. Its purpose is to instantiate the
 * controller.
 */
class UserControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container,
		$requestedName, array $options = null)
	{
		$entityManager = $container->
			get('doctrine.entitymanager.orm_default');
        // Instantiate the controller and inject dependencies
		return new UserController($entityManager);
	}
}
