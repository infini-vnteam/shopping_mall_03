<?php
/**
 * Created by PhpStorm.
 * User: thuyn
 * Date: 21-Aug-17
 * Time: 1:33 PM
 */

namespace Message\Controller\Factory;


use Application\Service\UserManager;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Message\Controller\MessageController;
use Message\Service\MessageManager;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Session\SessionManager;

class MessageControllerFactory implements FactoryInterface
{

    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $messageManager = $container->get(MessageManager::class);
        $sessionManager = $container->get(SessionManager::class);
        $authService = $container->get(\Zend\Authentication\AuthenticationService::class);
        $userManager = $container->get(UserManager::class);

        // Instantiate the controller and inject dependencies
        return new MessageController($entityManager, $sessionManager, $userManager, $messageManager, $authService);
    }
}