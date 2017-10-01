<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\Product;
use Application\Entity\Category;

/**
 * This is the custom repository class for Post entity.
 */
class ProductRepository extends EntityRepository
{
    /**
     * Retrieves all published posts in descending date order.
     * @return Query
     */
    public function findAll()
    {
    	return $this->findBy(['status' => Product::STATUS_PUBLISHED], ['date_created' => 'DESC']);
    }

    public function find($id)
    {
    	return $this->findOneBy(['id' => $id, 'status' => Product::STATUS_PUBLISHED]);
    }

    public function findOneById($id)
    {
        return $this->findOneBy(['id' => $id, 'status' => Product::STATUS_PUBLISHED]);
    }
}
