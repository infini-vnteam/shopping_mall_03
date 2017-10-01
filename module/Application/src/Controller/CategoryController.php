<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Product;
use Application\Entity\ProductMaster;
use Application\Entity\ProductColorImage;
use Application\Entity\category;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
use Admin\Helper\TrunCate;
/**
 * 
 */
class CategoryController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var
     */
    private $color = [
        ProductMaster::WHITE => 'white',
        ProductMaster::BLACK => 'black',
        ProductMaster::YELLOW => 'yellow',
        ProductMaster::RED => 'red',
        ProductMaster::GREEN => 'green',
        ProductMaster::PURPLE => 'purple',
        ProductMaster::ORANGE => 'orange',
        ProductMaster::BLUE => 'blue',
        ProductMaster::GREY => 'grey',
        ];
    private $entityManager;


    private $sessionContainer;

    private $categoryManager;
    /**
     * Constructor.
     */
    public function __construct($entityManager, $sessionContainer, $categoryManager)
    {
        $this->entityManager = $entityManager;
        $this->sessionContainer = $sessionContainer;
        $this->categoryManager = $categoryManager;
    }

    /**
     * The "view" action displays a page .
     */
    public function viewAction()
    {   

        $categoryId = $this->params()->fromRoute('id', -1);
        $colorName = $this->params()->fromRoute('color', -1);
        $sort = $this->params()->fromQuery('sort', -1);
        $price_gte = $this->params()->fromQuery('price_gte', -1);
        $price_lte = $this->params()->fromQuery('price_lte', -1);
        $colorName = $this->params()->fromQuery('color', -1);
        $color_id = -1;
        if ($colorName != -1 && $colorName != 'other') {
            $color = array_flip($this->color);
            $color_id = $color[$colorName];
        }
        $arr = [];
        $arr = $this->categoryManager->arrCate($arr,$categoryId);
        $option =$_GET;
        $page = $this->params()->fromQuery('page', 1);

        $category = $this->entityManager->getRepository(category::class)->find($categoryId);
        $products = $this->entityManager->getRepository(ProductColorImage::class)
                ->findProductsByCategory($arr, $sort, $color_id, $price_gte, $price_lte);
        
        $adapter = new DoctrineAdapter(new ORMPaginator($products, false));
        
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(12);                
        $paginator->setCurrentPageNumber($page);

        $mainCategories = $this->categoryManager->mainCategories();
        $arrCateTree = $this->categoryManager->arrCateTree();
        
        $params = [
            'sort' => $sort,
            'color' => $colorName,
            'price_gte' => $price_gte,
            'price_lte' => $price_lte,
        ];
        //var_dump($paginator);die();
        $view = new ViewModel([
            'products' => $paginator,
            'category' => $category,
            'mainCategories' => $mainCategories,
            'arrCateTree' => $arrCateTree,
            'params' => $params,
             
        ]);
        $this->layout('application/layout');
        return $view;
    }
}
