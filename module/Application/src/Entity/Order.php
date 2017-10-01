<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\OrderItem;
use Application\Entity\User;
/**
 * @ORM\Entity
 * @ORM\Table(name="orders")
 */
class Order
{
    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\OrderItem", mappedBy="order")
     * @ORM\JoinColumn(name="id", referencedColumnName="order_id")
     */
    protected $order_items;
    public function __construct() 
    {
        $this->order_items = new ArrayCollection();
    }

    /**
     * Returns orderitems for this order.
     * @return array
     */
    public function getOrderItems() 
    {
        return $this->order_items;
    }
      
    /**
     * Adds a new orderitem to this order.
     * @param $orderitem
     */
    public function addOrderItem($order_item) 
    {
        $this->order_items[] = $order_item;
    }
    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", inversedBy="orders")
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
        $user->addOrder($this);
    }

    /**
    * One Order has One Address.
    * @ORM\OneToOne(targetEntity="\Application\Entity\Address")
    * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
    */
    protected $address;

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

            $format = '%s, %s District, %s Province';
            return sprintf($format, $a, $d, $p);
        } else return null;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    // Post status constants.
    const STATUS_PENDING = 1; //Pending for admin accept ship
    const STATUS_SHIPPING = 2; // start shipping
    const STATUS_COMPLETED = 3; // order successfully

    /**
     * @ORM\Column(name="status")
     */
    protected $status;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\Column(name="phone")
     */
    protected $phone;

    /**
     * @ORM\Column(name="email")
     */
    protected $email;

    /**
     * @ORM\Column(name="cost")
     */
    protected $cost;

    /**
     * @ORM\Column(name="number_of_items")
     */
    protected $number_of_items;

    /**
     * @ORM\Column(name="completed_at")
     */
    protected $completed_at;

    /**
     * @ORM\Column(name="ship_at")
     */
    protected $ship_at;

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

    public function getStatus() 
    {
        return $this->status;
    }

    public function setStatus($status) 
    {
        $this->status = $status;
        if($status == 3) {
            $arr = $this->getProductCI();
            foreach ($arr as $key => $pCI) {
                $pCI->addCountSell();
            }
        }
    }

    public function getName() 
    {
        return $this->name;
    }

    public function setName($name) 
    {
        $this->name = $name;
    }

    public function getPhone() 
    {
        return $this->phone;
    }

    public function setPhone($phone) 
    {
        $this->phone = $phone;
    }

    public function getEmail() 
    {
        return $this->email;
    }

    public function setEmail($email) 
    {
        $this->email = $email;
    }


    public function getCost() 
    {
        return $this->cost;
    }

    public function setCost($cost) 
    {
        $this->cost = $cost;
    }

    public function getNumberOfItems() 
    {
        return $this->number_of_items;
    }

    public function setNumberOfItems($number_of_items) 
    {
        $this->number_of_items = $number_of_items;
    }

    public function getCompletedAt() 
    {
        return $this->completed_at;
    }

    public function setCompletedAt($completed_at) 
    {
        $this->completed_at = $completed_at;
    }

    public function getShipAt() 
    {
        return $this->ship_at;
    }

    public function setShipAt($ship_at) 
    {
        $this->ship_at = $ship_at;
    }

    public function getDateCreated() 
    {
        return $this->date_created;
    }

    public function setDateCreated($date_created) 
    {
        $this->date_created = $date_created;
    }

    public function getProductCI()
    {
        $arr = [];
        foreach ($this->order_items as $item) {
            $productMaster = $item->getProductMaster();
            $product = $productMaster->getProduct();
            $color_id = $productMaster->getColorId();
            foreach ($product->getProductColorImages() as $pCI) {
                
                if($pCI->getColorId() == $color_id) {
                    $arr[$pCI->getId()] = $pCI; 
                }
            }
        }
        
        return $arr;
    }
}
