<?php
/**
 * Created by PhpStorm.
 * User: thuyn
 * Date: 21-Aug-17
 * Time: 1:52 PM
 */

namespace Message\Service;


use Doctrine\ORM\EntityManager;
use Message\Entity\Message;
use Zend\Session\Container;

class MessageManager
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    private $messages;

    /**
     * Constructs the service.
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->messages = $entityManager->getRepository(Message::class)->findAll();
    }

    public function create($data)
    {
        $message = new Message();
        $message->setContent($data['content']);
        $message->setSenderId($data['sender_id']);
        $message->setChatroomId($data['chatroom_id']);
        $message->setDateCreated($data['date_created']);
        // Add the entity to the entity manager.
        $this->entityManager->persist($message);

        // Apply changes to database.
        $this->entityManager->flush();

        return $message;
    }

    public function all()
    {
        $contents = [];
        foreach ($this->messages as $message) {
            $contents[] = [
                'message' => $message->getContent(),
                'sender_id' => $message->getSenderId(),
            ];
        }

        return $contents;
    }

    public function messages($chatroom_id)
    {
        $contents = [];
        foreach ($this->messages as $message) {
            if ($message->getChatroomId() == $chatroom_id) {
                $contents[] = [
                    'content' => $message->getContent(),
                    'sender_id' => $message->getSenderId(),
                    'chatroom_id' => $message->getChatroomId(),
                ];
            }
        }

        return $contents;
    }

}