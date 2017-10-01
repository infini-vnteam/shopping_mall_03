<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="addresses")
 */
class Address
{
    /**
     * @ORM\ManyToOne(targetEntity="\Application\Entity\District", inversedBy="addresses")
     * @ORM\JoinColumn(name="district_id", referencedColumnName="id")
     */
    protected $district;
    
       
    /*
     * Returns associated district.
     * @return \Application\Entity\District
     */
    public function getDistrict() 
    {
        return $this->district;
    }

    public function setDistrict($district) 
    {
        $this->district = $district;
        $district->addAddress($this);
    }

    

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="address")
     */
    protected $address;

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

    public function getAddress() 
    {
        return $this->address;
    }

    public function setAddress($address) 
    {
        $this->address = $address;
    }

    public function getDateCreated() 
    {
        return $this->date_created;
    }

    public function setDateCreated($date_created) 
    {
        $this->date_created = $date_created;
    }
    public function getInfo()
    {
        return [
            'province' => $this->getDistrict()->getProvince()->getName(),
            'district' => $this->getDistrict()->getName(),
            'address' => $this->getAddress()
        ];
    }
}
