<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Sale;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\Common\Collections\Criteria;

/**
 * @ORM\Entity
 * @ORM\Table(name="sale_programs")
 */
class SaleProgram
{
    //constant for status varriable
    const ACTIVE = 0; //in sale's time 
    const PENDING = 1; //waiting for time up to date start
    const DONE = 2; //after sale's time (date end)
    const CANCEL = 3; //cancel sale program
    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\Product", inversedBy="sale_programs")
     * @ORM\JoinTable(name="sales",
     *      joinColumns={@ORM\JoinColumn(name="sale_program_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")}
     *      )
     */
    protected $products;
    
    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Sale", mappedBy="sale_program")
     * @ORM\JoinColumn(name="id", referencedColumnName="sale_program_id")
     */
    protected $sales;
    public function __construct() 
    {
        $this->sales = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    public function getProducts() 
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('status', Product::STATUS_PUBLISHED));
        
        return $this->products->matching($criteria);
    }      
      
    // Adds a new product to this product.
    public function addProduct($product) 
    {
        $this->products[] = $product;        
    }
    
    public function removeProductAssociation($product) 
    {
        $this->products->removeElement($product);
        for ($i = 0;$i < count($this->sales);$i++) {
            $sale = $this->sales[$i];
            if($sale->getProduct()->getId() == $product->getId()) break;
        }
        $product->removeSale($sale);
    }

    /**
     * Returns products for this category.
     * @return array
     */
    public function getSales() 
    {
        return $this->sales;
    }
      
    public function addSale($sale) 
    {
        $this->sales[] = $sale;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="Name")
     */
    protected $name;

    /**
     * @ORM\Column(name="date_start", type="datetime")
     */
    protected $date_start;

    /**
     * @ORM\Column(name="date_end", type="datetime")
     */
    protected $date_end;

    /**
     * @ORM\Column(name="date_created")
     */
    protected $date_created;
    
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

    public function getName() 
    {
        return $this->name;
    }

    public function setName($name) 
    {
        $this->name = $name;
    }

    public function getDateStart() 
    {
        return $this->date_start->format('d-m-Y');
    }

    public function setDateStart($date_start) 
    {
        $date_start = str_replace('/', '-', $date_start);
        $date_start = new \DateTime($date_start);
        $this->date_start = $date_start;
    }

    public function getDateEnd() 
    {
        return $this->date_end->format('d-m-Y');
    }

    public function setDateEnd($date_end) 
    {
        $date_end = str_replace('/', '-', $date_end);
        $date_end = new \DateTime($date_end);
        $this->date_end = $date_end;
    }

    public function getStatus() 
    {
        return $this->status;
    }

    public function setStatus() 
    {
        $this->status = $this->getCurrentStatus();
    }

    public function getDateCreated() 
    {
        return $this->date_created;
    }

    public function setDateCreated($date_created) 
    {
        $this->date_created = $date_created;
    }

    public function getCurrentStatus()
    {
        $currentDate = date('d-m-Y');
        $currentDate = strtotime($currentDate);
        $date_start = strtotime($this->getDateStart());
        $date_end = strtotime($this->getDateEnd());
        
        if ($currentDate < $date_start)
            return SaleProgram::PENDING;
        elseif ($currentDate <= $date_end)
            return SaleProgram::ACTIVE;
        if ($currentDate > $date_end)
            return SaleProgram::DONE;
    }

    // $sale_array['product_id'] =  Sale's value of this product
    public function getSaleArray()
    {
        $sales = $this->getSales();
        foreach ($sales as $s) {
            $sale_array[$s->getProduct()->getId()] = $s->getSale();
        }
        return $sale_array;
    }

    public function getStatusOfStatus()
    {
        $status_of_status = "";

        if ($this->getStatus() == 1 && $this->getCurrentStatus() == 0) {
            $status_of_status = "(Need to Active)";
        }
        if ($this->getStatus() == 0 && $this->getCurrentStatus() == 2) {
            $status_of_status = "(Expired)";
        }

        return $status_of_status;
    }

    public function getStatusInWord($type)
    {
        $status_in_word = ["Active", "Pending", "Done", "Cancel"];
        return $status_in_word[$type];
    }
}
