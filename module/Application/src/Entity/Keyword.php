<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\Product;
use Doctrine\Common\Collections\Criteria;
/**
 * @ORM\Entity
 * @ORM\Table(name="keywords")
 */
class Keyword
{
    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\Product", mappedBy="keywords")
     */
    protected $products;
      
    // Constructor.
    public function __construct() 
    {        
        $this->products = new ArrayCollection();        
    }
    
    // Returns products associated with this keyword.
    public function getproducts() 
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('status', Product::STATUS_PUBLISHED));
        
        return $this->products->matching($criteria);
    }
      
    // Adds a product into collection of products related to this keyword.
    public function addproduct($product) 
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
     * @ORM\Column(name="keyword")
     */
    protected $keyword;

    /**
     * @ORM\Column(name="date_created")
     */
    protected $date_created;

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

    public function getKeyword() 
    {
        return $this->keyword;
    }

    public function setKeyword($keyword) 
    {
        $this->keyword = $keyword;
    }

    public function getDateCreated($date_created) 
    {
        $this->date_created;
    }

    public function setDateCreated($date_created) 
    {
        $this->date_created = $date_created;
    }
}
