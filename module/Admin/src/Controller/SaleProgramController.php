<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\SaleProgram;
use Application\Entity\Product;
use Admin\Form\SaleProgramForm;

class SaleProgramController extends AbstractActionController
{
	/**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    private $saleProgramManager;

    public function __construct($entityManager, $saleProgramManager)
    {
        $this->entityManager = $entityManager;
        $this->saleProgramManager = $saleProgramManager;        
    }

    public function indexAction()
    {
        $sale_programs = $this->entityManager->getRepository(SaleProgram::class)
            ->findBy([], ['status' => 'ASC']);

        return new ViewModel([
            'sale_programs' => $sale_programs
            ]);
    }

    public function viewAction()
    {
        $saleProgramId = $this->params()->fromRoute('id', -1);
        $saleProgram = $this->entityManager->getRepository(SaleProgram::class)
            ->findOneById($saleProgramId);        
        if ($saleProgram == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        }

        $products = $this->entityManager->getRepository(Product::class)
            ->findAll();
        $products_in_sale= $saleProgram->getProducts();
    
        $sale_array = $saleProgram->getSaleArray();

        $currentDate = date('d-m-Y');
        
        return new ViewModel([
            'products_in_sale' => $products_in_sale,
            'saleProgram' => $saleProgram,
            'products' => $products,
            'currentDate' => $currentDate,
            'sale_array' => $sale_array,
            ]);
    }

    public function addAction()
    {
        $form = new SaleProgramForm();
        if ($this->getRequest()->isPost()) {
            //var_dump(2);die();
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {

                // Get validated form data.
                $data = $form->getData();

                if ($this->saleProgramManager->addNewSaleProgram($data) != 0)             
                    return $this->redirect()->toRoute('sale_programs', ['action'=>'index']);
            }
        }

    	return new ViewModel([
    		'form' => $form
    		]);
    }

    public function editAction()
    {
        $saleProgramId = $this->params()->fromRoute('id', -1);
        $saleProgram = $this->entityManager->getRepository(SaleProgram::class)
            ->findOneById($saleProgramId);        
        if ($saleProgram == null) {
          $this->getResponse()->setStatusCode(404);
          return;                        
        }

        $form = new SaleProgramForm();

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {

                // Get validated form data.
                $data = $form->getData();

                if ($this->saleProgramManager->updateSaleProgram($saleProgram, $data) != 0)             
                    return $this->redirect()->toRoute('sale_programs', ['action'=>'index']);
            }
        }
        else {
            $data = [
               'name' => $saleProgram->getName(),
               'date_start' => $saleProgram->getDateStart(),
               'date_end' => $saleProgram->getDateEnd(),
            ];
            $form->setData($data);
        }

        return new ViewModel([
            'form' => $form
            ]);
    }

    public function cancelAction()
    {
        $saleProgramId = $this->params()->fromRoute('id', -1);
        $saleProgram = $this->entityManager->getRepository(SaleProgram::class)
            ->findOneById($saleProgramId);        
        if ($saleProgram == null) {
          $this->getResponse()->setStatusCode(404);
          return;                        
        }

        $this->saleProgramManager->cancelSaleProgram($saleProgram);

        return $this->redirect()->toRoute('sale_programs',['action'=>'index']);
    }

    public function addproductAction()
    {
        if ($this->getRequest()->isPost()) {
            //get data from POST request
            $data = $this->params()->fromPost();

            //find $salProgram by ID
            $saleProgramId = $this->params()->fromRoute('id', -1);
            $saleProgram = $this->entityManager->getRepository(SaleProgram::class)
                ->findOneById($saleProgramId);        
            if ($saleProgram == null) {
                $this->getResponse()->setStatusCode(404);

                return;                        
            }
            
            $this->saleProgramManager->addProductToSaleProgram($saleProgram, $data);
            
            $this->redirect()->toRoute('sale_programs', ['action'=>'view', 'id' => $saleProgram->getId()]);
        } else {
            $this->redirect()->toRoute('sale_programs', ['action'=>'index']);
        }
    }

    public function removeproductAction()
    {
        if ($this->getRequest()->isPost()) {
            //get data from POST request

            $data = $this->params()->fromPost();

            $this->saleProgramManager->removeProductOutOfSaleProgram($data);
            $this->redirect()->toRoute('sale_programs', ['action'=>'view', 'id' => $data['sale_program_id']]);
        } else {
            $this->redirect()->toRoute('sale_programs', ['action'=>'index']);
        }
    }

    public function setStatusAction()
    {
        $saleProgramId = $this->params()->fromRoute('id', -1);
        $saleProgram = $this->entityManager->getRepository(SaleProgram::class)
            ->findOneById($saleProgramId);        
        if ($saleProgram == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        }

        $this->saleProgramManager->setStatusDependOnTime($saleProgram);

        return $this->redirect()->toRoute('sale_programs', ['action'=>'view', 'id' => $saleProgram->getId()]);
    }
}
