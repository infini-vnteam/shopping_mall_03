<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\Product;
use Application\Entity\ProductColorImage;
use Application\Entity\Store;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\Application\Repository\ProductMasterRepository")
 * @ORM\Table(name="product_masters")
 */
class ProductMaster
{   
    // constant of Size id 
    const S = 1;
    const M = 2;
    const L = 3;
    const XL = 4;
    const XXL = 5;

    // constant of Color id
    const WHITE = 1;
    const BLACK = 2;
    const YELLOW = 3;
    const RED = 4;
    const GREEN = 5;
    const PURPLE = 6;
    const ORANGE = 7;
    const BLUE = 8;
    const GREY = 9;

    private $list_color = [
                ProductMaster::WHITE => 'white',
                ProductMaster::BLACK => 'black',
                ProductMaster::YELLOW => 'yellow',
                ProductMaster::RED => 'red',
                ProductMaster::GREEN => 'green',
                ProductMaster::PURPLE => 'purple',
                ProductMaster::ORANGE => 'orange',
                ProductMaster::BLUE => 'blue',
                ProductMaster::GREY => 'grey',
                ];
    private $list_size = [
                ProductMaster::S => 'S',
                ProductMaster::M => 'M',
                ProductMaster::L => 'L',
                ProductMaster::XL => 'XL',
                ProductMaster::XXL => 'XXL',
                ];
    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Product", inversedBy="product_masters")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\OrderItem", mappedBy="product_master")
     * @ORM\JoinColumn(name="id", referencedColumnName="product_master_id")
     */
    protected $order_items;


    public function __construct() 
    {
        $this->order_items = new ArrayCollection();
    }
       
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
        $product->addProductMaster($this);
    } 

    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\Store", inversedBy="product_masters")
     * @ORM\JoinTable(name="store_products",
     *      joinColumns={@ORM\JoinColumn(name="product_master_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="store_id", referencedColumnName="id")}
     *      )
     */
    protected $stores; 

    public function getStore() 
    { 
        return $this->stores;
    }
      
    public function setStore($store) 
    {
        $this->stores[] = $store;
    }

    public function getOrderItems() 
    { 
        return $this->order_items;
    }
      
    public function addOrderItem($order_item) 
    {
        $this->order_items[] = $order_item;
    }   
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    
    /**
     * @ORM\Column(name="size_id")
     */
    protected $size_id;

    /**
     * @ORM\Column(name="color_id")
     */
    protected $color_id;

    // Returns ID of this product.
    public function getId() 
    {
        return $this->id;
    }

    // Sets ID of this product.
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

    public function setColorId($color_id) 
    {
        $this->color_id = $color_id;
    }

    public function getColorId() 
    {
        return $this->color_id;
    }

    public function getColorInWord()
    {
        return $this->list_color[$this->getColorId()];
    }

    public function getSizeId() 
    {
        return $this->size_id;
    }

    public function setSizeId($size_id) 
    {
        $this->size_id = $size_id;
    }

    public function getSizeInWord()
    {
        return $this->list_size[$this->getSizeId()];
    }

    public function findOrderByMonth() {
        $currentMonth = mktime(0, 0, 0, date("m"), 1, date("Y"));
        //$string = (string)date("Y") . '-' . (string)date("m") . '-' . '00';
        $data = date("Y-m-d h:i:sa", $currentMonth);
    
        $criteria = Criteria::create()
            ->where(Criteria::expr()->gt("date_created", $data));
            
        return $this->order_items->matching($criteria);
    }

}
