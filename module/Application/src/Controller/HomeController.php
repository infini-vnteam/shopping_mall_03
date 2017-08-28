<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Category;
use Application\Entity\Product;

class HomeController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var
     */
    private $entityManager;
    private $productManager;
    private $categoryManager;

    public function __construct($entityManager, $categoryManager, $productManager)
    {
        $this->entityManager = $entityManager;
        $this->categoryManager = $categoryManager;
        $this->productManager = $productManager;
    }


    public function indexAction()
    {
        $newProducts = $this->entityManager->getRepository(Product::class)->findBy(
           ['popular_level' => 1],['date_created'=>'DESC'], 15);
        $saleProducts = $this->entityManager->getRepository(Product::class)->findBy(
           [],['sale'=>'DESC'], 5);   
        $view = new ViewModel(['newProducts' => $newProducts, 
                                'saleProducts' => $saleProducts
                                ]);
        $this->layout('application/layout');
        return $view;
    }

    public function viewAction()
    {
    	//var_dump(2);die();
        $view = new ViewModel();
        $this->layout('application/home');
        return $view;
    }

    public function searchAction()
    {  
        $this->layout('application/layout');
        return new ViewModel();
    }

    public function getDataSearchAction()
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        $productArray = [];
        foreach ($products as $product) {
            $product_a['id'] = $product->getID();
            $product_a['name'] = $product->getName();
            $product_a['image'] = $product->getImage();
        array_push($productArray, $product_a);
        }
        $product_json = json_encode($productArray);
        $this->response->setContent($product_json);

        return $this->response;
    }
}
