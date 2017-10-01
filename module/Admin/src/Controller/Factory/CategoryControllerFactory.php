<?php

namespace Admin\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Admin\Controller\CategoryController;
use Admin\Service\CategoryManager;
use Admin\Form\CategoryForm;
/**
 * This is the factory for AdminController. Its purpose is to instantiate the
 * controller.
 */
class CategoryControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container,	$requestedName, array $options = null)
	{
		$entityManager = $container->get('doctrine.entitymanager.orm_default');
		$categoryManager = $container->get(CategoryManager::class);
		//$form = $container->get(CategoryForm::class);
		return new CategoryController($entityManager, $categoryManager);
	}
}
