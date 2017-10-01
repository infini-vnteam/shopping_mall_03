<?php
/**
 * Created by PhpStorm.
 * User: thuyn
 * Date: 21-Aug-17
 * Time: 1:40 PM
 */

namespace Message\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="messages")
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="content")
     */
    protected $content;

    /**
     * @ORM\Column(name="sender_id")
     */
    protected $sender_id;

    /**
     * @ORM\Column(name="chatroom_id")
     */
    protected $chatroom_id;

    /**
     * @ORM\Column(name="date_created")
     */
    protected $dateCreated;


    /**
     * Returns message ID.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets message ID.
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns content.
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets content.
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return integer
     */
    public function getSenderId()
    {
        return $this->sender_id;
    }

    /**
     * @param integer $sender_id
     */
    public function setSenderId($sender_id)
    {
        $this->sender_id = $sender_id;
    }

    /**
     * @return integer
     */
    public function getChatroomId()
    {
        return $this->chatroom_id;
    }

    /**
     * @param integer $chatroom_id
     */
    public function setChatroomId($chatroom_id)
    {
        $this->chatroom_id = $chatroom_id;
    }

    /**
     * Returns the date of user creation.
     * @return string
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Sets the date when this user was created.
     * @param string $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    public function getData()
    {
        return [
            'sender_id' => $this->sender_id,
            'content' => $this->content
            ];
    }
}