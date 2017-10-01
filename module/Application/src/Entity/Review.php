<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\User;
use Application\Entity\Product;

/**
 * @ORM\Entity
 * @ORM\Table(name="reviews")
 */
class Review
{
    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", inversedBy="reviews")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    /*
     * Returns associated user.
     * @return \Application\Entity\User
     */
    public function getUser() 
    {
        return $this->user;
    }
      
    /**
     * Sets associated user.
     * @param \Application\Entity\User $user
     */
    public function setUser($user) 
    {
        $this->user = $user;
        $user->addReview($this);
    }
    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Product", inversedBy="reviews")
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
        $product->addReview($this);
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="content")
     */
    protected $content;

    /**
     * @ORM\Column(name="rate")
     */
    protected $rate;

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

    public function getContent() 
    {
        return $this->content;
    }

    public function setContent($content) 
    {
        $this->content = $content;
    }

    public function getRate() 
    {
        return $this->rate;
    }

    public function setRate($rate) 
    {
        $this->rate = $rate;
    }

    public function getDateCreated() 
    {
        return $this->date_created;
    }

    public function setDateCreated($date_created) 
    {
        $this->date_created = $date_created;
    }

    public function getInfoReview()
    {
        $item['id'] = $this->getId();
        $item['rate'] = $this->getRate();
        $item['user_name'] = $this->getUser()->getName();
        if ($this->getUser()->getAddress() != null ) {
            $item['province'] = $this->getUser()->getAddress()
                ->getDistrict()->getProvince()->getName();
        } else $item['province'] = null;
        $item['content'] = $this->getContent();
        $item['date_created'] = $this->getDateCreated();

        return $item;
    }
    
}
