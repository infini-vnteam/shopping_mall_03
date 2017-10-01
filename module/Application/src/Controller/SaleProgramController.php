<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\SaleProgram;
use Application\Entity\Product;

class SaleProgramController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function indexAction()
    {
        $this->layout('application/layout');
        return new ViewModel();
    }

    public function getSalesAction()
    {
        $sale_programs = $this->entityManager->getRepository(SaleProgram::class)
            ->findBy(['status' => 0], ['date_start' => 'ASC']);

        $sale_data = [];

        foreach ($sale_programs as $sp) {
            $products = $sp->getProducts();

            $sale = [
                'name' => $sp->getName(),
                'id' => $sp->getId(),
                'date_start' => $sp->getDateStart(),
                'date_end' => $sp->getDateEnd(),
                'products' => [],
                'num_products' => sizeof($products),
            ];

            for ($i = 0; $i < sizeof($products) && $i < 50; $i++) {
                $product = $products[$i];
                $product_data = [
                    'name' => $product->getName(),
                    'id' => $product->getId(),
                    'sale' => $product->getCurrentSale(),
                    'current_price' => $product->getCurrentPrice(),
                    'image' => $product->getImage(),
                ];
                array_push($sale['products'], $product_data);
            }

            array_push($sale_data, $sale);
        }

        $this->getResponse()->setContent(json_encode($sale_data));
        return $this->getResponse();
    }

    public function viewAction()
    {
        $this->layout('application/layout');
        return new ViewModel();
    }

    public function getSaleAction()
    {
        $saleProgramId = $this->params()->fromRoute('id', -1);
        $sp = $this->entityManager->getRepository(SaleProgram::class)
            ->findOneById($saleProgramId);

        if ($sp == null) {
            $this->getResponse()->setStatusCode(404);
            $this->getResponse()->setContent(json_encode([
                'error' => '404',
            ]));
        } else {
            $products = $sp->getProducts();

            $sale = [
                'name' => $sp->getName(),
                'id' => $sp->getId(),
                'date_start' => $sp->getDateStart(),
                'date_end' => $sp->getDateEnd(),
                'products' => [],
                'num_products' => sizeof($products),
            ];

            for ($i = 0; $i < sizeof($products); $i++) {
                $product = $products[$i];
                $product_data = [
                    'name' => $product->getName(),
                    'id' => $product->getId(),
                    'sale' => $product->getCurrentSale(),
                    'current_price' => $product->getCurrentPrice(),
                    'image' => $product->getImage(),
                ];
                array_push($sale['products'], $product_data);
            }

            $this->getResponse()->setContent(json_encode($sale));
        }

        return $this->getResponse();
    }
}
