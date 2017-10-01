<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\ProductMaster;
use Application\Entity\Address;

/**
 * @ORM\Entity
 * @ORM\Table(name="stores")
 */
class Store
{
    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\ProductMaster", mappedBy="store")
     */
    protected $product_masters;
    public function __construct() 
    {
        $this->product_masters = new ArrayCollection();
    }

    /**
     * Returns products for this store.
     * @return array
     */
    public function getProductMaster() 
    {
        return $this->product_masters;
    }
      
    /**
     * Adds a new product to this store.
     * @param $product
     */
    public function addProductMaster($product_master) 
    {
        $this->products_masters[] = $product_master;
    }

    /**
     * One Product has One Address.
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
