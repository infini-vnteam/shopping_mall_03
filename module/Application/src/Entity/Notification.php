<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="notifications")
 */
class Notification
{
    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", inversedBy="notifications")
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
        $user->addNotification($this);
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="type")
     */
    protected $type;

    /**
     * @ORM\Column(name="target_id")
     */
    protected $target_id;

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

    public function getType() 
    {
        return $this->type;
    }

    public function setType($type) 
    {
        $this->type = $type;
    }

    public function getTarget_id() 
    {
        return $this->target_id;
    }

    public function setTarget_id($target_id) 
    {
        $this->target_id = $target_id;
    }
}
