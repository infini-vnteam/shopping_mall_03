<?php 
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Store;
use Admin\Form\StoreForm;

class StoreController extends AbstractActionController
{
     /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
     private $entityManager;
    /**
     * Store manager.
     * @var Application\Service\StoreManager
     */
    private $storeManager;
    public function __construct($entityManager,$storeManager){

        $this->entityManager = $entityManager;
        $this->storeManager  = $storeManager;
    }
    
    public function listAction()
    {
        $stores = $this->entityManager->getRepository(Store::class)->findAll();
        return new ViewModel([
            'stores' => $stores
            ]);
    }

    public function addAction()
    {
        $form = new StoreForm($this->entityManager);
        // Check whether this post is a POST request.
        if ($this->getRequest()->isPost()) {
            // Get POST data.
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                // Get validated form data.
                $data = $form->getData();
                $currentDate = date('Y-m-d H:i:s');
                $data['date_created'] = $currentDate;
                $this->storeManager->addNewStore($data);             
                  // Redirect the user to "index" page.
                return $this->redirect()->toRoute('stores',['action'=>'list']);
            }
        }
        
        // Render the view template.
        return new ViewModel([
        'form' => $form
        ]);
    }
    
    public function editAction()
    {
        // Get product ID.    
        $storeId = $this->params()->fromRoute('id', -1);
        // Find existing post in the database.    
        $store = $this->entityManager->getRepository(Store::class)->find($storeId);	
      	 // Create the form.
        $form = new StoreForm($this->entityManager,$store);

        if ($store == null) {
            $this->getResponse()->setStatusCode(404);
            echo "hasn't store with id = ".$storeId;                        
        } 
        if ($this->getRequest()->isPost()) {
            // Get POST data.
            $data = $this->params()->fromPost();
            // Fill form with data.
            $form->setData($data); 
            if ($form->isValid()) {
                // Get validated form data.
                $data = $form->getData();
                $this->storeManager->updateStore($store, $data);
                // Redirect the user to "admin" page.
                return $this->redirect()->toRoute('stores', ['action'=>'list']);
            }

        }else 
        {
            $data = [
                'name'  =>  $store->getName(),
                'address'  =>  $store->getAddress(), 
                'phone'  =>  $store->getPhone(), 
                ];

            $form->setData($data);
        }

    // Render the view template.
    return new ViewModel([
        'form' => $form,
        'store' => $store
        ]);
    }
    public function deleteAction()
    {

        $storeId = $this->params()->fromRoute('id', -1);

        $store = $this->entityManager->getRepository(Store::class)
            ->findOneById($storeId);        
        if ($store == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        }        
        $this->storeManager->removeStore($store);

        // Redirect the user to "products/list" page.
        return $this->redirect()->toRoute('stores', ['action'=>'list']);
    }

    public function viewAction()
    {
        $storeId = $this->params()->fromRoute('id', -1);

        $store = $this->entityManager->getRepository(Store::class)
            ->findOneById($storeId);
        if ($store == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        }
        $products = $store->getProducts();
        return new ViewModel([
            'products' => $products,
            'store' => $store
            ]);
             
    }
}
