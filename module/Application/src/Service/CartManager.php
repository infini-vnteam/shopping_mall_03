<?php

namespace Application\Service;

use Zend\Session\Container;

/**
 * This service is responsible for adding/editing users
 * and changing user password.
 */
class CartManager
{
    /**
     * Doctrine entity manager.
     * @var
     */
    private $entityManager;

    private $sessionContainer;

    /**
     * Constructs the service.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
        $this->sessionContainer = new Container('UserLogin');
    }


    public function isLogin()
    {
        if (isset($this->sessionContainer->id)) {
            return true;
        }
        return false;
    }

    /**
     * This method updates data of an existing user.
     */
    public function updateUser($user, $data)
    {
        // Do not allow to change user email if another user with such email already exits.
        if ($user->getEmail() != $data['email'] && $this->checkUserExists($data['email'])) {
            throw new \Exception("Another user with email address " . $data['email'] . " already exists");
        }

        // $user->setEmail($data['email']);
        $user->setName($data['full_name']);
        // $user->setStatus($data['status']);
        $user->setAddress($data['address']);
        $user->setPhone($data['phone']);

        // Apply changes to database.
        $this->entityManager->flush();

        return true;
    }
    
}
