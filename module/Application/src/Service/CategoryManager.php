<?php
namespace Application\Service;

use Application\Entity\Category;
use Zend\Filter\StaticFilter;
use Appliaction\Entity\Product;

class CategoryManager 
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
      
    public function categories_for_nav_bar()
    {
        $main_categories = $this->entityManager->getRepository(Category::class)->findBy(['parent_id' => '0']);

        foreach($main_categories as $main_cate)
        {
            $child_categories = $this->entityManager->
                getRepository(Category::class)->findBy(['parent_id' => $main_cate->getId()]);
            $child_categories_for_select = [];
            foreach($child_categories as $child_cate)
            {
                array_push($child_categories_for_select, $child_cate->getName());
            }
            $categories_for_nav_bar[$main_cate->getName()] = $child_categories_for_select;
        }
        return $categories_for_nav_bar;
    }
    public function mainCategories()
    {
        return $this->entityManager->getRepository(Category::class)->findBy(['parent_id' => null]);
      
    }

    public function arrCate(&$arr , $categoryId)
    {
        array_push($arr,(int)$categoryId);
        $cates = $this->entityManager->getRepository(Category::class)->findAll();
        foreach ( $cates as $cate) {
            if($cate->getParentId() == $categoryId) $this->arrCate($arr,$cate->getId());
        }
        return $arr;
    }
    public function arrCateTree()
    {   
        $arr = [];
        $mainCates = $this->entityManager->getRepository(Category::class)->findBy(['parent_id' => null]);  
        foreach ( $mainCates as $cate ){
            $arr[$cate->getId()] = $this->entityManager->getRepository(Category::class)
                                        ->findBy(['parent_id' => $cate->getId()]);
        }
       
        return $arr;
    }
    public function getAllCategories()
    {
        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        $categories_name = [];

        foreach ($categories as $c) {
            array_push($categories_name, strtolower($c->getName()));
        }

        return $categories_name;
    }
}
