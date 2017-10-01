<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\Image;
use Application\Entity\Product;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="\Application\Repository\ProductColorImageRepository")
 * @ORM\Table(name="product_color_images")
 */
class ProductColorImage
{
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
    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Product", inversedBy="product_color_images")
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
    public function setProduct($product) 
    {
        $this->product = $product;
        $product->addProductColorImage($this);
    }
     /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Image", mappedBy="product_color_image")
     * @ORM\JoinColumn(name="id", referencedColumnName="product_color_image_id")
     */
    protected $images;
    public function __construct() 
    {
        $this->images = new ArrayCollection();
    }

    /**
     * Returns products for this category.
     * @return array
     */
    public function getImages() 
    {
        return $this->images;
    }
      
    /**
     * Adds a new product to this category.
     * @param $image
     */
    public function addImage($image) 
    {
        $this->images[] = $image;
    }
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="color_id")
     */
    protected $color_id;

    /**
     * @ORM\Column(name="count_sell")
     */
    protected $count_sell = 0;
    /**
     * @ORM\Column(name="date_created")
     */
    protected $date_created;

    public function getCountSell() 
    {
        return $this->count_sell;
    }

    public function addCountSell() 
    {
        $this->count_sell = $this->count_sell + 1;
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

    public function getColorId() 
    {
        return $this->color_id;
    }

    public function setColorId($color_id) 
    {
        $this->color_id = $color_id;
    }

    public function getDateCreated() 
    {
        return $this->date_created;
    }

    public function setDateCreated($date_created) 
    {
        $this->date_created = $date_created;
    }


    public function getColorInWord()
    {
        return $this->list_color[$this->getColorId()];

    }
    public function getMainImage() 
    {
        $images = $this->getImages();
        
        foreach ($images as $image) {
            if($image->getType() == 1)
                return $image->getImage();
        }

    }

}
