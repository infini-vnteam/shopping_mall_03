<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Application\Entity\Product;
use Application\Entity\User;
/**
 * @ORM\Entity
 * @ORM\Table(name="comments")
 */
class Comment
{
    /**
     * One Comment has Many Comments.
     * @ORM\OneToMany(targetEntity="\Application\Entity\Comment", mappedBy="parent")
     */
    private $childrens;

    /**
     * Many Comments have One Comment.
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Comment", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;
    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Product", mappedBy="category")
     * @ORM\JoinColumn(name="id", referencedColumnName="category_id")
     */
    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    public function __construct() 
    {
        $this->childrens = new ArrayCollection();
    }

    public function getChildrens() 
    {
        return $this->childrens;
    }

    public function addChildrens($children) 
    {
        $this->childrens[] = $children;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
        $parent->addChildrens($this);
    }
    
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
        $user->addComment($this);
    }
    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Product", inversedBy="comments")
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
        $product->addComment($this);
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
     * @ORM\Column(name="status")
     */
    protected $status = 1;

    /**
     * @ORM\Column(name="date_created")
     */
    protected $date_created;

    /**
     * @ORM\Column(name="parent_id")
     */
    protected $parent_id = 0;
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


    public function getStatus() 
    {
        return $this->status;
    }

    public function setStatus($status) 
    {
        $this->status = $status;
    }

    public function getDateCreated() 
    {
        return $this->date_created;
    }

    public function setDateCreated($date_created) 
    {
        $this->date_created = $date_created;
    }
    public function getParentId()
    {
        return $this->parent_id;
    }
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }

    public function getInfoComment()
    {
        $item['id'] = $this->getId();
        $item['user_id'] = $this->getUser()->getId();
        $item['user_name'] = $this->getUser()->getName();
        $item['content'] = $this->getContent();
        $item['replies'] = [];
        foreach ($this->getChildrens() as $reply) {
            $item_reply['id'] = $reply->getId();
            $item_reply['user_id'] = $reply->getUser()->getId();
            $item_reply['user_name'] = $reply->getUser()->getName();
            $item_reply['content'] = $reply->getContent();
            array_push($item['replies'], $item_reply);
        }

        return $item;
    }
}
