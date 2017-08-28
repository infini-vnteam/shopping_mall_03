<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="messages")
 */
class Message
{
    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", inversedBy="messages")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    /*
     * Returns associated user.
     * @return \Application\Entity\User
     */
    public function getUser() 
    {
        return $this->user;
    }
      
    /**
     * Sets associated user.
     * @param \Application\Entity\User $user
     */
    public function setUser($user) 
    {
        $this->user = $user;
        $user->addMessage($this);
    }

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Chat_box", inversedBy="messages")
     * @ORM\JoinColumn(name="chat_box_id", referencedColumnName="id")
     */
    protected $chat_box;
    /*
     * Returns associated chat_box.
     * @return \Application\Entity\Chat_box
     */
    public function getChatBox() 
    {
        return $this->chat_box;
    }
      
    /**
     * Sets associated chat_box.
     * @param \Application\Entity\Chat_box $chat_box
     */
    public function setChatBox($chat_box) 
    {
        $this->chat_box = $chat_box;
        $chat_box->addMessage($this);
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="content")
     */
    protected $content;

    /**
     * @ORM\Column(name="sent_at")
     */
    protected $sent_at;

    /**
     * @ORM\Column(name="status")
     */
    protected $status;

    // Returns ID of this post.
    public function getId() 
    {
        return $this->id;
    }

    // Sets ID of this post.
    public function setId($id) 
    {
        $this->id = $id;
    }

    public function getContent() 
    {
        return $this->content;
    }

    public function setContent($content) 
    {
        $this->content = $content;
    }

    public function getSent_at() 
    {
        return $this->sent_at;
    }

    public function setSent_at($sent_at) 
    {
        $this->sent_at = $sent_at;
    }

    public function getStatus() 
    {
        return $this->status;
    }

    public function setStatus($status) 
    {
        $this->status = $status;
    }
}
