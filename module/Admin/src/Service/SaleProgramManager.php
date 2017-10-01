<?php
namespace Admin\Service;

use Application\Entity\SaleProgram;
use Application\Entity\Sale;
use Application\Entity\Product;
use Zend\Filter\StaticFilter;

class SaleProgramManager 
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

    public function addNewSaleProgram($data) 
    {
        $saleProgram = new SaleProgram();
        $saleProgram->setName($data['name']);
        $saleProgram->setDateStart($data['date_start']);
        $saleProgram->setDateEnd($data['date_end']);
        $saleProgram->setStatus();
        $currentDate = date('Y-m-d H:i:s');
        $saleProgram->setDateCreated($currentDate);       
            
        // Add the entity to entity manager.
        $this->entityManager->persist($saleProgram);
            
        // Apply changes to database.
        $this->entityManager->flush();
        
        return 1;
    }

    public function updateSaleProgram($saleProgram, $data)
    {
        $saleProgram->setName($data['name']);
        $saleProgram->setDateStart($data['date_start']);
        $saleProgram->setDateEnd($data['date_end']);
        $saleProgram->setStatus();

        $this->entityManager->flush();

        return 1;
    }

    public function cancelSaleProgram($saleProgram)
    {
        $saleProgram->setStatus(SaleProgram::CANCEL);
        $products = $saleProgram->getProducts();
        for($i = 0; $i < count($products); $i++) {
            $current_price = (int)($products[$i]->getPrice()*(100 - $products[$i]->getCurrentSale())/100);
            $products[$i]->setCurrentPrice($current_price); 
        }
        $this->entityManager->flush();
    }

    public function setStatusDependOnTime($saleProgram)
    {
        $saleProgram->setStatus();

        $this->entityManager->flush();
    }

    public function addProductToSaleProgram($saleProgram, $data)
    {
        for ($i = 0; $i < count($data['products']['id']); $i++){ 
            $sale = new Sale();
            $product = $this->entityManager->getRepository(Product::class)
                ->findOneById($data['products']['id'][$i]);

            $sale->setSaleProgram($saleProgram);
            
            $sale->setSale($data['products']['sale'][$i]);

            $currentDate = date('Y-m-d H:i:s');
            $sale->setDateCreated($currentDate);
            $sale->setProduct($product);
            
            $this->entityManager->persist($sale);
        }            

        $this->entityManager->flush();
    }

    public function removeProductOutOfSaleProgram($data)
    {
        $saleProgram = $this->entityManager->getRepository(SaleProgram::class)
            ->findOneById($data['sale_program_id']);
        $product = $this->entityManager->getRepository(Product::class)
            ->findOneById($data['product_id']);

        $saleProgram->removeProductAssociation($product);
        
        $this->entityManager->flush();
    }

    public function getSaleProgramNeedActive()
    {
        $currentDate = date('d-m-Y');
        $salePrograms = $this->entityManager->getRepository(SaleProgram::class)
            ->findBy(['status' => SaleProgram::PENDING]);
        $list = [];
        foreach ($salePrograms as $sP) {
            if ($sP->getCurrentStatus() == SaleProgram::ACTIVE) {
                array_push($list, $sP);
            }
        }

        return $list;
    }

    public function getSaleProgramNeedDone()
    {
        $currentDate = date('d-m-Y');
        $salePrograms = $this->entityManager->getRepository(SaleProgram::class)
            ->findBy(['status' => SaleProgram::ACTIVE]);
        $list = [];
        foreach ($salePrograms as $sP) {
            if ($sP->getCurrentStatus() == SaleProgram::DONE) {
                array_push($list, $sP);
            }
        }

        return $list;
    }
}
