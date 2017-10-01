<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Product;
use Application\Entity\SaleProgram;
/**
 * @ORM\Entity
 * @ORM\Table(name="sales")
 */
class Sale
{
    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\SaleProgram", inversedBy="sales")
     * @ORM\JoinColumn(name="sale_program_id", referencedColumnName="id")
     */
    protected $sale_program;
       
    /*
     * Returns associated saleprogram.
     * @return \Application\Entity\SaleProgram
     */
    public function getSaleProgram() 
    {
        return $this->sale_program;
    }
      
    /**
     * Sets associated category.
     * @param \Application\Entity\SaleProgram $sale_program
     */
    public function setSaleProgram($sale_program) 
    {
        $this->sale_program = $sale_program;
        $sale_program->addSale($this);
    }

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Product", inversedBy="sales")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;
       
    /*
     * Returns associated saleprogram.
     * @return \Application\Entity\Product
     */
    public function getProduct() 
    {
        return $this->product;
    }
      
    /**
     * Sets associated category.
     * @param \Application\Entity\Product $product
     */
    public function setProduct($product) 
    {
        $this->product = $product;
        $product->addSale($this);
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="date_created")
     */
    protected $date_created;
    
    /**
    * @ORM\Column(name="sale")
    */
    protected $sale;

    
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

    public function getSale() 
    {
        return $this->sale;
    }

    // Sets ID of this post.
    public function setSale($sale) 
    {
        $this->sale = $sale;
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
