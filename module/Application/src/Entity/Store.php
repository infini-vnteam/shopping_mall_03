<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\Product;
/**
 * @ORM\Entity
 * @ORM\Table(name="stores")
 */
class Store
{
    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Product", mappedBy="store")
     * @ORM\JoinColumn(name="id", referencedColumnName="store_id")
     */
    protected $products;
    public function __construct() 
    {
        $this->products = new ArrayCollection();
    }

    /**
     * Returns products for this store.
     * @return array
     */
    public function getProducts() 
    {
        return $this->products;
    }
      
    /**
     * Adds a new product to this store.
     * @param $product
     */
    public function addProduct($product) 
    {
        $this->products[] = $product;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\Column(name="address")
     */
    protected $address;

    /**
     * @ORM\Column(name="phone")
     */
    protected $phone;

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

    public function getPhone() 
    {
        return $this->phone;
    }

    public function setPhone($phone) 
    {
        $this->phone = $phone;
    }

    public function getDateCreated() 
    {
        return $this->date_created;
    }

    public function setDateCreated($date_created) 
    {
        $this->date_created = $date_created;
    }

}
