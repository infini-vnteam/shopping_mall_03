<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Activity;
use Application\Entity\Comment;
use Application\Entity\Order;
use Application\Entity\Review;
use Application\Entity\Message;
use Application\Entity\Address;
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
     * @ORM\OneToMany(targetEntity="\Application\Entity\Activity", mappedBy="sender")
     * @ORM\JoinColumn(name="id", referencedColumnName="sender_id")
     * @ORM\OrderBy({"date_created"="DESC"})
     */
    protected $activities;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Activity", mappedBy="receiver")
     * @ORM\JoinColumn(name="id", referencedColumnName="receiver_id")
     * @ORM\OrderBy({"date_created"="DESC"})
     */
    protected $notifications;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Comment", mappedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id")
     * @ORM\OrderBy({"date_created"="DESC"})
     */
    protected $comments;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Review", mappedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id")
     */
    protected $reviews;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Order", mappedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id")
     * @ORM\OrderBy({"status"="ASC"})
     */
    protected $orders;
    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Message", mappedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id")
     */
    protected $messages;

    /**
    * One Product has One Address.
    * @ORM\OneToOne(targetEntity="\Application\Entity\Address")
    * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
    */
    protected $address;
    /**
     * Constructor.
     */
    public function __construct() 
    {
        $this->notifications = new ArrayCollection();
        $this->activities = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->orders = new ArrayCollection();  
        $this->messages = new ArrayCollection();                  
    }

    public function getAddress() 
    {
        return $this->address;
    }

    public function setAddress($address) 
    {
        $this->address = $address;
    }

    public function getFullAddress()
    {
        if ($this->getAddress() != null) {
            $a = $this->getAddress()->getAddress();
            $d = $this->getAddress()->getDistrict()->getName();
            $p = $this->getAddress()->getDistrict()->getProvince()->getName();

            $format = '%s, %s District %s Province';
            return sprintf($format, $a, $d, $p);
        } else return null;
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

    public function getReviews() 
    {
        return $this->reviews;
    }
      
    /**
     * Adds a new comment to this user.
     * @param $comment
     */
    public function addReview($review) 
    {
        $this->reviews[] = $review;
    }

    /**
     * Returns notifications for this user.
     * @return array
     */
    public function getNotifications() 
    {
        return $this->notifications;
    }

    public function getUnreadNotifications() 
    {
        $unread = [];
        foreach ($this->notifications as $notify) {
            if ($notify->getStatus() == Activity::STATUS_UNREAD) {
                array_push($unread, $notify);
            }
        }
        $unread_notify = new ArrayCollection($unread);

        return $unread_notify;
    }

      
    /**
     * Adds a new notification to this user.
     * @param $notification
     */
    public function addNotification($notification) 
    {
        $this->notifications[] = $notification;
    }

    public function getActivities() 
    {
        return $this->activities;
    }

    public function addActivity($activity) 
    {
        return $this->activities[] = $activity;
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
        return $this->date_created;
    }

    public function setDateCreated($date)
    {
        $this->date_created = $date;
    }

    public function getPay() 
    {
        $pay = 0;
        $orders = $this->getOrders();
        
        foreach ($orders as $order) {
            if($order->getStatus() == Order::STATUS_COMPLETED) {
                $pay = $pay + $order->getCost();
            }
        }
        return $pay;
    }

    public function getData()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'role' => $this->role,
            'phone' => $this->phone,
            'status' => $this->status,
            'email' => $this->email,
            'address' => $this->address,
        ];
    }

    public function getActivitiesByDay($index1, $index2)
    {
        $activities = $this->getActivities();
        $activities_by_day = [];

        foreach ($activities as $a) {
            if (!isset($activities_by_day[$a->getDateCreated()]))
                $activities_by_day[$a->getDateCreated()] = [];
            $item['time'] = $a->getTimeCreated();
            $item['icon-class'] = $a->getIconClass();
            $item['content'] = $a->getContent();

            array_push($activities_by_day[$a->getDateCreated()], $item);
        }

        $activities_by_day = array_slice($activities_by_day, $index1, $index2);
        
        return $activities_by_day;
    }
}
