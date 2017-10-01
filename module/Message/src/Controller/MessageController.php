<?php
/**
 * Created by PhpStorm.
 * User: thuyn
 * Date: 21-Aug-17
 * Time: 1:31 PM
 */

namespace Message\Controller;


use Application\Entity\User;
use Doctrine\ORM\EntityManager;
use Message\Entity\Chatroom;
use Message\Entity\Message;
use Message\Service\MessageManager;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Validator\Authentication;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\Json;

class MessageController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Message manager.
     * @var MessageManager
     */
    private $messageManager;

    /**
     * Auth service
     * @var AuthenticationService
     */

    private $authService;

    /**
     * @var SessionManager
     */
    private $sessionManager;

    private $userManager;

    /**
     * Constructor.
     */
    public function __construct(
        $entityManager,
        $sessionManager,
        $userManager,
        MessageManager $messageManager,
        AuthenticationService $authService
    )
    {
        $this->entityManager = $entityManager;
        $this->messageManager = $messageManager;
        $this->authService = $authService;
        $this->sessionManager = $sessionManager;
        $this->userManager = $userManager;
    }

    public function sendMessageAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $post_data = $this->params()->fromPost();
            $now = (new \DateTime())->format('Y-m-d');
            $data = [
                'sender_id' => $post_data['sender_id'],
                'content' => $post_data['content'],
                'chatroom_id' => $post_data['chatroom_id'],
                'date_created' => $now,
            ];
            $message = $this->messageManager->create($data);
            $response->setContent(json_encode($message->getData()));
        }

        return $response;
    }

    public function getMessagesAction()
    {
        $user = $this->userManager->currentUser();
        $chatroom = $this->entityManager
            ->getRepository(Chatroom::class)
            ->findOneBy(['user' => $user->getId()]);
        $messages = $this->messageManager->messages($chatroom->getId());
        $response = $this->getResponse();
        $response->setContent(json_encode([
                'messages' => $messages,
                'chatroom_id' => $chatroom->getId(),
            ]
        ));
//        $response->setContent(json_encode($user));
        return $response;
    }

    public function getChatroomsAction()
    {
        $result = [];
        $chatrooms = $this->entityManager
            ->getRepository(Chatroom::class)
            ->findAll();
        foreach ($chatrooms as $chatroom) {
            if ($chatroom->getUser() == 1)
                continue;
            $msg = $this->entityManager
                ->getRepository(Message::class)
                ->findBy(['chatroom_id' => $chatroom->getId()], ['id' => 'ASC']);

            $messages = [];
            foreach ($msg as $item) {
                $messages[] = $item->getData();
            }

            $usr = $this->entityManager
                ->getRepository(User::class)
                ->find($chatroom->getUser());
            $user = $usr->getData();
            $cr = [
                'id' => $chatroom->getId(),
                'messages' => $messages,
                'user' => $user
            ];
            $result[] = $cr;
        }
        $response = $this->getResponse();
        $response->setContent(json_encode($result));

        return $response;
    }

    public function testAction()
    {
        $users = $this->entityManager
            ->getRepository(User::class)
            ->findBy(['role' => 1], ['id' => 'ASC']);
        foreach ($users as $user) {
            $chatroom = new Chatroom();
            $chatroom->setId($user->getId());
            $chatroom->setInitor(1);
            $chatroom->setUser($user->getId());
            $this->entityManager->persist($chatroom);
            $this->entityManager->flush();
        }
        $chatrooms = $this->entityManager->getRepository(Chatroom::class)->findAll();
        $response = $this->getResponse();
        $response->setContent(json_encode($chatrooms[0]->getData()));

        return $response;
    }
}