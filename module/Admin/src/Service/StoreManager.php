<?php
namespace Admin\Service;

use Application\Entity\Store;
use Zend\Filter\StaticFilter;

// The UserManager service is responsible for adding new posts.
class StoreManager
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    // Constructor is used to inject dependencies into the service.
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
  
    // This method adds a new store.
    public function addNewStore($data)
    {
        // Create new Store entity.

        $store = new Store();      
        $store->setName($data['name']);
        $store->setAddress($data['address']);
        $store->setPhone($data['phone']);
        $store->setDateCreated($data['date_created']);
        // Add the entity to entity manager.
        $this->entityManager->persist($store);
        // Apply changes to database.
        $this->entityManager->flush();
    }
    
    public function updateStore($store,$data)
    {
        $store->setName($data['name']);
        $store->setAddress($data['address']);
        $store->setPhone($data['phone']);
        // Apply changes to database.
        $this->entityManager->flush();
    }

    public function removeStore($store)
    {      
        $this->entityManager->remove($store);   
        $this->entityManager->flush();

    }

    public function stores_for_select()
    {
        $stores = $this->entityManager->getRepository(Store::class)->findAll();
        $stores_for_select[0] = '--Select--';
        foreach($stores as $store)
        {
            $stores_for_select[$store->getID()] = $store->getName();
        }
        return $stores_for_select;
    }
}
