<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Application\Entity\ProductMaster;
use Application\Entity\Order;
/**
 * @ORM\Entity
 * @ORM\Table(name="order_items")
 */
class OrderItem
{   
    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\ProductMaster")
     * @ORM\JoinColumn(name="product_master_id", referencedColumnName="id")
     */
    protected $product_master;
    /*
     * Returns associated product.
     * @return \Application\Entity\ProductMaster
     */
    public function getProductMaster() 
    {
        return $this->product_master;
    }   
      
    /**
     * Sets associated product.
     * @param \Application\Entity\Product $product
     */
    public function setProductMaster($product_master) 
    {
        $this->product_master = $product_master;
        $product_master->addOrderItem($this);
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
    protected $status = 1;

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
    public function getInfo()
    {
        $pM = $this->getProductMaster();
        return [
            'id' => $this->getId(),
            'name' => $pM->getProduct()->getName(),
            'quantity' => $this->getQuantity(),
            'color' => $pM->getColorInWord(),
            'size' => $pM->getSizeInWord(),
            'price' => (int)($this->getCost()/$this->getQuantity()),
            'total' => $this->getCost(),
        ];
    }
    
}
