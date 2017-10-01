<?php
namespace Admin\Service;

use Application\Entity\Order;
use Application\Entity\User;
use Application\Entity\Activity;

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

    public function updateStatus($order)
    {
        $currentDate = date('Y-m-d H:i:s');
        if ($order->getStatus() == Order::STATUS_PENDING) {
            $order->setStatus(Order::STATUS_SHIPPING);
            $order->setShipAt($currentDate);
            if ($order->getUser() != null) {
                $activity = new Activity();
                $user = $order->getUser();
                $activity->setReceiver($user);
                $activity->setType(Activity::ORDER_SHIP);
                $activity->setTarget($order);
                $activity->setDateCreated($currentDate);
                $this->entityManager->persist($activity);
            }
        }elseif ($order->getStatus() == Order::STATUS_SHIPPING) {
            $order->setStatus(Order::STATUS_COMPLETED);
            $order->setCompletedAt($currentDate);
            if ($order->getUser() != null) {
                $activity = new Activity();
                $user = $order->getUser();
                $activity->setReceiver($user);
                $activity->setType(Activity::ORDER_COMPLETE);
                $activity->setTarget($order);
                $activity->setDateCreated($currentDate);
                $this->entityManager->persist($activity);
            }
        }

        $this->entityManager->flush();
    }
}
