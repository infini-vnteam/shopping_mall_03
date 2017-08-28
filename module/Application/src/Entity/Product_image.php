<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\OrderItem;
/**
 * @ORM\Entity
 * @ORM\Table(name="product_images")
 */
class Product_image
{
    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\OrderItem", mappedBy="product_images")
     * @ORM\JoinColumn(name="id", referencedColumnName="product_image_id")
     */
    protected $order_items;
    public function __construct() 
    {
        $this->order_items = new ArrayCollection();
    }

    /**
     * Returns orderitems for this product_image.
     * @return array
     */
    public function getOrderItems() 
    {
        return $this->order_items;
    }
      
    /**
     * Adds a new orderitem to this product_image.
     * @param $orderitem
     */
    public function addOrderItem($order_item) 
    {
        $this->order_items[] = $order_item;
    }

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Product", inversedBy="product_images")
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
    public function setproduct($product) 
    {
        $this->product = $product;
        $product->addProductImage($this);
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="image")
     */
    protected $image;

    /**
     * @ORM\Column(name="color")
     */
    protected $color;

    /**
     * @ORM\Column(name="quantity")
     */
    protected $quantity;

    /**
     * @ORM\Column(name="size")
     */
    protected $size;

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

    public function getImage() 
    {
        return $this->image;
    }

    public function setImage($image) 
    {
        $this->image = $image;
    }

    public function getColor() 
    {
        return $this->color;
    }

    public function setColor($color) 
    {
        $this->color = $color;
    }

    public function getQuantity() 
    {
        return $this->quantity;
    }

    public function setQuantity($quantity) 
    {
        $this->quantity = $quantity;
    }

    public function getSize() 
    {
        return $this->size;
    }

    public function setSize($size) 
    {
        $this->size = $size;
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
