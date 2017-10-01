<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\User;
use Doctrine\DBAL\Types\DateTimeType;

/**
 * @ORM\Entity
 * @ORM\Table(name="activities")
 */
class Activity
{
    const ORDER = 1;
    const COMMENT = 2;
    const REVIEW = 3;
    const ORDER_SHIP = 4;
    const ORDER_COMPLETE = 5;
    const REPLY = 6;
    
    const STATUS_UNREAD = 1;
    const STATUS_READ = 0;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", inversedBy="activities")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     */
    protected $sender;
    /*
     * Returns associated user.
     * @return \Application\Entity\User
     */
    public function getSender() 
    {
        return $this->sender;
    }
      
    /**
     * Sets associated user.
     * @param \Application\Entity\User $user
     */
    public function setSender($sender) 
    {
        $this->sender = $sender;
        $sender->addActivity($this);
    }

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", inversedBy="notifications")
     * @ORM\JoinColumn(name="receiver_id", referencedColumnName="id")
     */
    protected $receiver;
    /*
     * Returns associated user.
     * @return \Application\Entity\User
     */
    public function getReceiver() 
    {
        return $this->receiver;
    }
      
    /**
     * Sets associated user.
     * @param \Application\Entity\User $user
     */
    public function setReceiver($receiver) 
    {
        $this->receiver = $receiver;
        $receiver->addNotification($this);
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
     * @ORM\Column(name="status")
     */
    protected $status = 1;

    /**
     * @ORM\Column(name="date_created", type="datetime")
     */
    protected $date_created;

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

    public function getStatus() 
    {
        return $this->status;
    }

    public function setStatus($status) 
    {
        $this->status = $status;
    }



    // public function setTargetId($target_id) 
    // {
    //     $this->target_id = $target_id;
    // }

    public function getDateCreated() 
    {
        return $this->date_created->format('d-m-Y');
    }

    public function getTimeCreated() 
    {
        return $this->date_created->format('H:i:s');
    }

    public function setDateCreated($date_created) 
    {
        $date = new \DateTime($date_created);
        $this->date_created = $date;
    }

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Order")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    protected $order;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Review")
     * @ORM\JoinColumn(name="review_id", referencedColumnName="id")
     */
    protected $review;

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Comment")
     * @ORM\JoinColumn(name="comment_id", referencedColumnName="id")
     */
    protected $comment;

    public function getTarget()
    {

        switch ($this->type) {
            case 1:
            case 4:
            case 5:
                return $this->order;
            case 2:
                return $this->comment;
            case 3:
                return $this->review;
            case 6:
                return $this->comment;
            default:
                return null;
        }
    }

    public function getTargetId() 
    {
        return $this->getTarget()->getId();
    }

    public function setTarget($target)
    {
        switch ($this->type) {
            case 1:
            case 4:
            case 5:
                return $this->order = $target;
            case 2:
                return $this->comment = $target;
            case 3:
                return $this->review = $target;
            case 6:
                return $this->comment = $target;
            default:
                return null;
        }
    }

    public function getIconClass()
    {
        $iconClass = [
            1 => 'fa fa-truck bg-blue',
            2 => 'fa fa-comment bg-green',
            3 => 'fa fa-pencil bg-yellow',
            6 => 'fa fa-comment bg-green'
        ];

        return $iconClass[$this->type];
    }

    public function getContent()
    {
        $content = [];
        if($this->type != $this::ORDER){
            $product_id = $this->getTarget()->getProduct()->getId();
            $product_name = $this->getTarget()->getProduct()->getName();
        }
        switch ($this->type) {
            case $this::ORDER:
                $format = 'Create new <a href="/admin/orders/view/%d">order</a>';
                $content = sprintf($format, $this->getTargetId());
                break;
            case $this::REVIEW:
                $format = 'Create new review in product <a href="/product/view/%d">%s</a>';
                $content = sprintf($format, $product_id, $product_name);
                break;
            case $this::COMMENT:
                $format = 'Create new comment in product <a href="/product/view/%d">%s</a>';
                $content = sprintf($format, $product_id, $product_name);
                break;
            case $this::REPLY:
                $comment_owner_name =  $this->getTarget()->getUser()->getName();
                $format = 'Reply comment of <a>%s</a> in product <a href="/product/view/%d">%s</a>';
                $content = sprintf($format, $comment_owner_name, $product_id, $product_name);
                break;
        }

        return $content;
    }

    public function getNotiContent()
    {
        $content = [];
        
        switch ($this->type) {
            case $this::COMMENT:
                $format = '%s commented in product <a href="/product/view/%d">%s</a>';
                $content = sprintf($format, $this->getSender()->getName(),
                    $this->getTarget()->getProduct()->getId(), $this->getTarget()->getProduct()->getName());
                break;
            case $this::ORDER_SHIP:
                $format = 'Your order #%d has been shipped - check <a href="/order">here</a>';
                $content = sprintf($format, $this->getTargetID());
                break;
            case $this::ORDER_COMPLETE:
                $format = 'Your order #%d has been shipped successfully - check <a href="/order">here</a>';
                $content = sprintf($format, $this->getTargetID());
                break;
            case $this::REPLY:
                $format = '%s commented on your comment in product <a href="/product/view/%d">%s</a>';
                $content = sprintf($format, $this->getSender()->getName(),
                    $this->getTarget()->getProduct()->getId(), $this->getTarget()->getProduct()->getName());
                break;
        }

        return $content;
    }
}
