<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\Message;
/**
 * @ORM\Entity
 * @ORM\Table(name="chat_boxs")
 */
class Chat_box
{
    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Message", mappedBy="chat_boxs")
     * @ORM\JoinColumn(name="id", referencedColumnName="chat_box_id")
     */
    protected $messages;
    public function __construct() 
    {
        $this->messages = new ArrayCollection();
    }

    /**
     * Returns messages for this chat_box.
     * @return array
     */
    public function getMessages() 
    {
        return $this->messages;
    }
      
    /**
     * Adds a new message to this chat_box.
     * @param $message
     */
    public function addMessage($message) 
    {
        $this->messages[] = $message;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

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

    public function getStatus() 
    {
        return $this->status;
    }

    public function setStatus($status) 
    {
        $this->status = $status;
    }
}
