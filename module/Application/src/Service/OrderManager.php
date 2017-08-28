<?php
namespace Application\Service;

use Application\Entity\Order;
use Application\Entity\OrderItem;
use Zend\Filter\StaticFilter;


class OrderManager 
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

    public function addNewOrder($data, $cart, $arrItems)
    {
        
        $order = new Order();
        //var_dump($arrItems);die();
        $order->setName($data['full_name']);
        $order->setPhone($data['phone']);
        $order->setAddress($data['address']);
        $order->setNumberOfItems($cart->totalItem);
        $order->setCost($cart->totalPrice);
        $order->setStatus(Order::STATUS_PENDING);
        $order->setDateCreated($data['date_created']);
        
        $this->entityManager->persist($order);
        
        foreach ($arrItems as $item) {
            $orderItem = new OrderItem();
            $product = $this->entityManager->
                getRepository('Application\Entity\Product')->find($item->id);

            $orderItem->setProduct($product);
            $orderItem->setOrder($order);
            $orderItem->setQuantity($item->quantity);
            $orderItem->setCost($item->price);
            $orderItem->setStatus($data['status']);
            $orderItem->setDateCreated($data['date_created']);

            $this->entityManager->persist($orderItem);
            $this->entityManager->flush();
        }
        // Add the entity to entity manager.  
    }
    public function addNewOrderItems($arrItems)
    {
        $count = count($arrItems);
        foreach ($arrItems as $item) {
            $order = new OrderItem();
            
            $order->setName($data['full_name']);
            $order->setPhone($data['phone']);
            $order->setAddress($data['address']);
        }
    }
   
}
