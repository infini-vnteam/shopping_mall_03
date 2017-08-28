<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\Comment;
use Application\Entity\Keyword;
use Application\Entity\Product_image;
use Application\Entity\Rate;
/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\Application\Repository\ProductRepository")
 * @ORM\Table(name="products")
 */
class Product
{
     /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Comment", mappedBy="product")
     * @ORM\JoinColumn(name="id", referencedColumnName="product_id")
     */
    protected $comments;

    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\Keyword", inversedBy="products")
     * @ORM\JoinTable(name="product_keywords",
     *      joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="keyword_id", referencedColumnName="id")}
     *      )
     */
    protected $keywords;
      
    /**
     * Constructor.
     */

    private $mapColor = [
        '1' => 'White',
        '2' => 'Black',
        '3' => 'Blue',
        '4' => 'Yellow',
        '5' => 'Red',
        '6' => 'Green',
        '7' => 'Purple',
        '8' => 'Orange',
        '9' => 'Light blue',
        '10' => 'Sky blue',
        '11' => 'Gray',
    ];

    public function __construct() 
    {
        $this->comments = new ArrayCollection();
        $this->keywords = new ArrayCollection();
        $this->product_images = new ArrayCollection();                  
    }
      
    /**
     * Returns comments for this product.
     * @return array
     */
    public function getComments() 
    {
        return $this->comments;
    }
      
    /**
     * Adds a new comment to this product.
     * @param $comment
     */
    public function addComment($comment) 
    {
        $this->comments[] = $comment;
    }

    protected $product_images;
      
    /**
     * Returns product_images for this product.
     * @return array
     */
    public function getProductImages() 
    {
        return $this->product_images;
    }
      
    /**
     * Adds a new product_image to this product.
     * @param $product_image
     */
    public function addProductImage($product_image) 
    {
        $this->product_images[] = $product_image;
    }


    // Returns keywords for this product.
    public function getKeywords() 
    {
        return $this->keywords;
    }      
      
    // Adds a new keyword to this product.
    public function addKeyword($keyword) 
    {
        $this->keywords[] = $keyword;        
    }
      
    // Removes association between this product and the given keyword.
    public function removeKeywordAssociation($keyword) 
    {
        $this->keywords->removeElement($keyword);
    }

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Store", inversedBy="products")
     * @ORM\JoinColumn(name="store_id", referencedColumnName="id")
     */
    protected $store;
       
    /*
     * Returns associated store.
     * @return \Application\Entity\Store
     */
    public function getStore() 
    {
        return $this->store;
    }
      
    /**
     * Sets associated store.
     * @param \Application\Entity\Store $store
     */
    public function setStore($store) 
    {
        
        $this->store = $store;

        $store->addProduct($this);
    }

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;
       
    /*
     * Returns associated category.
     * @return \Application\Entity\Category
     */
    public function getCategory() 
    {
        return $this->category;
    }
      
    /**
     * Sets associated category.
     * @param \Application\Entity\Category $category
     */
    public function setCategory($category) 
    {
        $this->category = $category;
        $category->addProduct($this);
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
     * @ORM\Column(name="price")
     */
    protected $price;

    /**
     * @ORM\Column(name="intro")
     */
    protected $intro;

    /**
     * @ORM\Column(name="image")
     */
    protected $image;

    /**
     * @ORM\Column(name="description")
     */
    protected $description;

    /**
     * @ORM\Column(name="status")
     */
    protected $status = 0;

    /**
     * @ORM\Column(name="rate_avg")
     */
    protected $rate_avg = 0;

    /**
     * @ORM\Column(name="rate_count")
     */
    protected $rate_count = 0;

    /**
     * @ORM\Column(name="sale")
     */
    protected $sale = 0;

    /**
     * @ORM\Column(name="popular_level")
     */
    protected $popular_level = 0;

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
    * @ORM\Column(name="date_created")
    */
    protected $date_created;


    public function getPopular_level() 
    {
        return $this->popular_level;
    }

    // Sets ID of this product.
    public function setPopular_level($popular_level) 
    {
        $this->popular_level = $popular_level;
    }

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

    public function getPrice() 
    {
        return $this->price;
    }

    public function setPrice($price) 
    {
        $this->price = $price;
    }

    public function getIntro() 
    {
        return $this->intro;
    }

    public function setIntro($intro) 
    {
        $this->intro = $intro;
    }

    public function getImage() 
    {
        return $this->image;
    }

    public function setImage($image) 
    {
        $this->image = $image;
    }

    public function getDescription() 
    {
        return $this->description;
    }

    public function setDescription($description) 
    {
        $this->description = $description;
    }

    public function getStatus() 
    {
        return $this->status;
    }

    public function setStatus($status) 
    {
        $this->status = $status;
    }

    public function getRateAvg() 
    {
        return $this->rate_avg;
    }

    public function setRateAvg($rate_avg) 
    {
        $this->rate_avg = $rate_avg;
    }

    public function getRate_count() 
    {
        return $this->rate_count;
    }

    public function setRate_count($rate_count) 
    {
        $this->rate_count = $rate_count;
    }

    public function getSale() 
    {
        return $this->sale;
    }

    public function setSale($sale) 
    {
        $this->sale = $sale;
    }

    public function getColor() 
    {
        return $this->color;
    }

    public function getColorText()
    {
        return $this->mapColor[$this->color];
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

    public function getDateCreated() 
    {
        return $this->date_created;
    }

    // Sets ID of this product.
    public function setDateCreated($date_created) 
    {
        $this->date_created = $date_created;
    }

    public function getSalePrice() {
        $price = $this->getPrice() * (1 - $this->getSale()/100.0);
        return round($price);
    }
}
