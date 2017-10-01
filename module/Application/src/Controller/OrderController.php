<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Order;
use Application\Entity\User;

class OrderController extends AbstractActionController
{
    private $entityManager;
    private $sessionContainer;
    private $orderManager;

    function __construct($entityManager, $sessionContainer, $orderManager)
    {
        $this->entityManager = $entityManager;
        $this->sessionContainer = $sessionContainer;
        $this->orderManager = $orderManager;
    }

    function trackAction()
    {
        if ($this->getRequest()->isPost()) {

            $data = $this->getRequest()->getContent();
            $data = json_decode($data);
            
            // find order by $data->email and $data->order_id
            $order = $this->orderManager->getOrder($data->order_id, $data->email);

            if ($order != null) {

                $items = $order->getOrderItems();

                $items_data = [];
                foreach ($items as $item) {
                    $pm = $item->getProductMaster();
                    $data = [
                        'id' => $item->getId(),
                        'name' => $pm->getProduct()->getName(),
                        'quantity' => $item->getQuantity(),
                        'color' => $pm->getColorInWord(),
                        'size' => $pm->getSizeInWord(),
                        'price' => $item->getCost(),
                        'total' => $item->getCost() * $item->getQuantity(),
                    ];
                    array_push($items_data, $data);
                }

                $order = [
                    'id' => $order->getId(),
                    'created_at' => $order->getDateCreated(),
                    'completed_at' => $order->getCompletedAt(),
                    'items' => $items_data,
                    'customer_detail' => [
                        'full_name' => $order->getName(),
                        'email' => $order->getEmail(),
                        'phone_number' => $order->getPhone(),
                        'address' => $order->getFullAddress(),
                    ],
                    'total_price' => $order->getCost(),
                    'status' => $order->getStatus(),
                ];

                $this->response->setContent(json_encode($order));
            } else {
                $this->response->setContent(json_encode([
                    'error' => 'No order match',
                ]));
            }
            return $this->response;
        }

        $view = new ViewModel([

        ]);
        $this->layout('application/layout');
        return $view;
    }

    function viewAction()
    {
        $view = new ViewModel([

        ]);
        $this->layout('application/layout');
        return $view;
    }

    function getOrdersAction()
    {
        if (isset($this->sessionContainer->id)) {
            $user_id = $this->sessionContainer->id;
            $user = $this->entityManager->getRepository(User::class)->find($user_id);
            // find order by user id
            $orders = $this->entityManager->getRepository(Order::class)->findBy(['user' => $user]);

            if ($orders != null) {
                $orders_data = [];
                foreach ($orders as $order) {
                    $items = $order->getOrderItems();

                    $items_data = [];
                    foreach ($items as $item) {
                        $pm = $item->getProductMaster();
                        $data = [
                            'id' => $item->getId(),
                            'name' => $pm->getProduct()->getName(),
                            'quantity' => $item->getQuantity(),
                            'color' => $pm->getColorInWord(),
                            'size' => $pm->getSizeInWord(),
                            'price' => $item->getCost() / $item->getQuantity(),
                            'total' => $item->getCost(),
                        ];
                        array_push($items_data, $data);
                    }

                    $order = [
                        'id' => $order->getId(),
                        'created_at' => $order->getDateCreated(),
                        'completed_at' => $order->getCompletedAt(),
                        'items' => $items_data,
                        'customer_detail' => [
                            'full_name' => $order->getName(),
                            'email' => $order->getEmail(),
                            'phone_number' => $order->getPhone(),
                            'address' => $order->getFullAddress(),
                        ],
                        'total_price' => $order->getCost(),
                        'status' => $order->getStatus(),
                    ];

                    array_push($orders_data, $order);
                }
                $this->response->setContent(json_encode($orders_data));
            } else {
                $this->response->setContent(json_encode([

                ]));
            }
            return $this->response;
        }
        $this->response->setContent(json_encode([
            'error' => 'You must login to use this feature',
        ]));
        return $this->response;
    }
}
