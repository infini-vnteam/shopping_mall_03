<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Notification;
use Application\Entity\Comment;
use Application\Entity\Order;
use Application\Entity\Rate;
use Application\Entity\Message;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User
{
    const STATUS_ACTIVE       = 1; // Active user.
    const STATUS_RETIRED      = 2; // Retired user.

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Notification", mappedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id")
     */
    protected $notifications;
    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Comment", mappedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id")
     */
    protected $comments;
    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Order", mappedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id")
     * @ORM\OrderBy({"status" = "ASC"})
     */
    protected $orders;
    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Message", mappedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id")
     */
    protected $messages;

    /**
     * Constructor.
     */
    public function __construct() 
    {
        $this->notifications = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->orders = new ArrayCollection();  
        $this->messages = new ArrayCollection();                  
    }

    /**
     * Returns comments for this user.
     * @return array
     */
    public function getComments() 
    {
        return $this->comments;
    }
      
    /**
     * Adds a new comment to this user.
     * @param $comment
     */
    public function addComment($comment) 
    {
        $this->comments[] = $comment;
    }

    /**
     * Returns notifications for this user.
     * @return array
     */
    public function getNotifications() 
    {
        return $this->notifications;
    }
      
    /**
     * Adds a new notification to this user.
     * @param $notification
     */
    public function addNotification($notification) 
    {
        $this->notifications[] = $notification;
    }

    /**
     * Returns orders for this user.
     * @return array
     */
    public function getOrders() 
    {
        return $this->orders;
    }
      
    /**
     * Adds a new order to this user.
     * @param $order
     */
    public function addOrder($order) 
    {
        $this->orders[] = $order;
    }

    /**
     * Returns messages for this user.
     * @return array
     */
    public function getMessages() 
    {
        return $this->messages;
    }
      
    /**
     * Adds a new message to this user.
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
     * @ORM\Column(name="email")
     */
    protected $email;

    /**
     * @ORM\Column(name="password")
     */
    protected $password;

    /**
     * @ORM\Column(name="role")
     */
    protected $role;

    /**
     * @ORM\Column(name="phone")
     */
    protected $phone;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\Column(name="address")
     */
    protected $address;

    /**
     * @ORM\Column(name="status")
     */
    protected $status;

    /**
     * @ORM\Column(name="token")
     */
    protected $token;

    /**
     * @ORM\Column(name="date_created")
     */
    protected $dateCreated;

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

    public function getEmail() 
    {
        return $this->email;
    }

    public function setEmail($email) 
    {
        $this->email = $email;
    }

    public function getPassword() 
    {
        return $this->password;
    }

    public function setPassword($password) 
    {
        $this->password = $password;
    }

    public function getRole() 
    {
        return $this->role;
    }

    public function setRole($role) 
    {
        $this->role = $role;
    }

    public function getPhone() 
    {
        return $this->phone;
    }

    public function setPhone($phone) 
    {
        $this->phone = $phone;
    }

    public function getName() 
    {
        return $this->name;
    }

    public function setName($name) 
    {
        $this->name = $name;
    }

    public function getAddress() 
    {
        return $this->address;
    }

    public function setAddress($address) 
    {
        $this->address = $address;
    }

    public function getStatus() 
    {
        return $this->status;
    }

    /**
     * Returns possible statuses as array.
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_RETIRED => 'Retired'
        ];
    }

    /**
     * Returns user status as string.
     * @return string
     */
    public function getStatusAsString()
    {
        $list = self::getStatusList();
        if (isset($list[$this->status]))
            return $list[$this->status];

        return 'Unknown';
    }

    public function setStatus($status) 
    {
        $this->status = $status;
    }

    public function getToken() 
    {
        return $this->token;
    }

    public function setToken($token) 
    {
        $this->token = $token;
    }

    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    public function setDateCreated($date)
    {
        $this->dateCreated = $date;
    }
}
