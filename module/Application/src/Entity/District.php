<?php
namespace Application\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="districts")
 */
class District
{
    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Address", mappedBy="district")
     * @ORM\JoinColumn(name="id", referencedColumnName="district_id")
     */
    protected $addresses;
    public function __construct() 
    {
        $this->addresses = new ArrayCollection();
    }

    /**
     * Returns addresses for this district.
     * @return array
     */
    public function getAddresses() 
    {
        return $this->addresses;
    }

    /**
     * Adds a new address to this district.
     * @param $address
     */
    public function addAddress($address) 
    {
        $this->addresses[] = $address;
    }

    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\Province", inversedBy="districts")
     * @ORM\JoinColumn(name="province_id", referencedColumnName="id")
     */
    protected $province;
       
    /*
     * Returns associated province.
     * @return \Application\Entity\Province
     */
    public function getProvince() 
    {
        return $this->province;
    }

    public function setProvince($province) 
    {
        $this->province = $province;
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
