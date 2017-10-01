<?php
namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="provinces")
 */
class Province
{
    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\District", mappedBy="province")
     * @ORM\JoinColumn(name="id", referencedColumnName="province_id")
     */
    protected $districts;
    public function __construct() 
    {
        $this->districts = new ArrayCollection();
    }

    /**
     * Returns districts for this province.
     * @return array
     */
    public function getDistricts() 
    {
        return $this->districts;
    }
    public function addDistricts($district) 
    {
        $this->districts[] = $district;
        $district->setProvince($this);
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
}
