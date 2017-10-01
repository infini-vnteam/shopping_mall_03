<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\ProductColorImage;
use Application\Entity\Product;


/**
 * This is the custom repository class for Post entity.
 */
class ProductColorImageRepository extends EntityRepository
{

    public function findProductsByCategory(
        $arr, 
        $sort = -1, 
        $color_id = -1, 
        $price_gte = -1, 
        $price_lte = -1
        )
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('pci')
            ->from(ProductColorImage::class, 'pci')
            ->join('pci.product', 'p')
            ->join('p.category', 'c')
            ->where('c.id IN(:arr)')
            ->andWhere('p.status = :public');
            $parameters['arr'] = $arr;
            $parameters['public'] = Product::STATUS_PUBLISHED; 
            
        if ($sort != -1) {
            switch ($sort) {
            case 'htl':
                $queryBuilder->orderBy('p.current_price', 'DESC');  
                break;
            case 'lth':
                $queryBuilder->orderBy('p.current_price', 'ASC');  
                break; 
            case 'new':
                $queryBuilder->orderBy('pci.date_created', 'DESC');  
                break;
            case 'sell':
                $queryBuilder->orderBy('pci.count_sell', 'DESC');  
                break;
            default:
                
                break;
            }; 
        }
        if ($color_id != -1) {
            $queryBuilder->andWhere('pci.color_id = :color_id');
            $parameters['color_id'] = $color_id;    
        }
        if ($price_lte != -1 && $price_gte != -1) {
            $queryBuilder->andWhere($queryBuilder->expr()->between('p.current_price', ':price_gte', ':price_lte'));
            $parameters['price_lte'] = $price_lte;
            $parameters['price_gte'] = $price_gte;

        } else if ($price_lte != -1 && $price_gte == -1) {
            $queryBuilder->andWhere($queryBuilder->expr()->lte('p.current_price', ':price_lte'));
            $parameters['price_lte'] = $price_lte;

        } else if ($price_lte == -1 && $price_gte != -1) {
            $queryBuilder->andWhere($queryBuilder->expr()->gte('p.current_price', ':price_gte'));
            $parameters['price_gte'] = $price_gte;

        } else {

        }
        $queryBuilder->setParameters($parameters);
        
        return $queryBuilder->getQuery();
    }


}