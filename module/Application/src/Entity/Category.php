<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\Product;
/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 */
class Category
{
    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Product", mappedBy="category")
     * @ORM\JoinColumn(name="id", referencedColumnName="product_id")
     */
    protected $products;
    public function __construct() 
    {
        $this->products = new ArrayCollection();
    }

    /**
     * Returns products for this category.
     * @return array
     */
    public function getProducts() 
    {
        return $this->products;
    }
      
    /**
     * Adds a new product to this category.
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
     * @ORM\Column(name="alias")
     */
    protected $alias;

    /**
     * @ORM\Column(name="description")
     */
    protected $description;

    /**
     * @ORM\Column(name="parent_id")
     */
    protected $parent_id = 0;

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

    public function getAlias() 
    {
        return $this->alias;
    }

    public function setAlias($alias) 
    {
        $this->alias = $alias;
    }

    public function getDescription() 
    {
        return $this->description;
    }

    public function setDescription($description) 
    {
        $this->description = $description;
    }

    public function getParentId() 
    {
        return $this->parent_id;
    }

    public function setParentId($parent_id) 
    {
        $this->parent_id = $parent_id;
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
