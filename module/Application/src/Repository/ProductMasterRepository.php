<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\ProductMaster;


/**
 * This is the custom repository class for Post entity.
 */
class ProductMasterRepository extends EntityRepository
{
    /**
     * Retrieves all published posts in descending date order.
     * @return Query
     */

    public function findSizebyProductId($product_id)
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('pm.size_id')
            ->from(ProductMaster::class, 'pm')
            ->join('pm.product', 'p')
            ->where('p.id = :product_id')
            ->setParameter('product_id', $product_id)
            ->distinct(true);

        return $queryBuilder->getQuery()->getResult();
    }

    public function findSizebyProductIdColorId($product_id,$color_id)
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('pm.size_id')
            ->from(ProductMaster::class, 'pm')
            ->join('pm.product', 'p')
            ->where('p.id = :product_id')
            ->andWhere('pm.color_id = :color_id')
            ->setParameters(array('product_id' => $product_id,'color_id' => $color_id))
            ->distinct(true);

        return $queryBuilder->getQuery()->getResult();
    }

}
