<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Order;

class OrderController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    private $orderManager;

    public function __construct($entityManager, $orderManager)
    {
        $this->entityManager = $entityManager;
        $this->orderManager = $orderManager;        
    }

    public function indexAction()
    {
        $orders = $this->entityManager->getRepository(Order::class)->findAll();

        return new ViewModel([
            'orders' => $orders
            ]);
    }

    public function viewAction()
    {
        $orderId = $this->params()->fromRoute('id', -1);

        $order = $this->entityManager->getRepository(Order::class)->find($orderId);
        if ($order == null) {
            $this->getResponse()->setStatusCode(404);                      
        }
        $order_items = $order->getOrderItems();

        return new ViewModel([
            'order_items' => $order_items,
            'order' => $order
            ]);
    }

    public function editAction()
    {
        $orderId = $this->params()->fromRoute('id', -1);

        $order = $this->entityManager->getRepository(Order::class)
            ->findOneById($orderId);        
        if ($order == null) {
          $this->getResponse()->setStatusCode(404);
          return;                        
        }
        
        $this->orderManager->updateStatus($order);

        return $this->redirect()->toRoute('orders', ['action'=>'index']);
    }
}
