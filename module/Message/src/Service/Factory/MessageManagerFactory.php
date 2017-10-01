<?php
/**
 * Created by PhpStorm.
 * User: thuyn
 * Date: 21-Aug-17
 * Time: 2:38 PM
 */

namespace Message\Service\Factory;


use Message\Service\MessageManager;
use Psr\Container\ContainerInterface;

class MessageManagerFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new MessageManager($entityManager);
    }
}