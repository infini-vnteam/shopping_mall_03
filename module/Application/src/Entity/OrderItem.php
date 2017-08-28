<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="order_items")
 */
class OrderItem
{
    /**
     * @ORM\OneToOne(targetEntity="\Application\Entity\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;
    /*
     * Returns associated product.
     * @return \Application\Entity\Product
     */
    public function getProduct() 
    {
        return $this->product;
    }
      
    /**
     * Sets associated product.
     * @param \Application\Entity\Product $product
     */
    public function setProduct($product) 
    {
        $this->product = $product;
    }

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Order", inversedBy="order_items")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    protected $order;
    /*
     * Returns associated order.
     * @return \Application\Entity\Order
     */
    public function getOrder() 
    {
        return $this->order;
    }
      
    /**
     * Sets associated order.
     * @param \Application\Entity\Order $order
     */
    public function setOrder($order) 
    {
        $this->order = $order;
        $order->addOrderItem($this);
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="quantity")
     */
    protected $quantity;

    /**
     * @ORM\Column(name="status")
     */
    protected $status;

    /**
     * @ORM\Column(name="cost")
     */
    protected $cost;
    /**
     * @ORM\Column(name="date_created")
     */
    protected $date_created;

    public function getDateCreated() 
    {
        return $this->date_created;
    }

    // Sets ID of this post.
    public function setDateCreated($date_created)
    {
        $this->date_created = $date_created;
    }
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

    public function getQuantity() 
    {
        return $this->quantity;
    }

    public function setQuantity($quantity) 
    {
        $this->quantity = $quantity;
    }

    public function getStatus() 
    {
        return $this->status;
    }

    public function setStatus($status) 
    {
        $this->status = $status;
    }

    public function getCost() 
    {
        return $this->cost;
    }

    public function setCost($cost) 
    {
        $this->cost = $cost;
    }
}
