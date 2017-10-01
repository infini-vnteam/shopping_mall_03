<?php
namespace Application\Service;

use Application\Entity\Order;
use Application\Entity\ProductMaster;
use Application\Entity\Product;
use Application\Entity\OrderItem;
use Application\Entity\User;
use Application\Entity\Address;
use Application\Entity\Activity;
use Application\Entity\District;
use Application\Entity\Province;
use Zend\Filter\StaticFilter;


class OrderManager 
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $list_color = [
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

    private $list_size = [
        ProductMaster::S => 'S',
        ProductMaster::M => 'M',
        ProductMaster::L => 'L',
        ProductMaster::XL => 'XL',
        ProductMaster::XXL => 'XXL',
    ];

    private $entityManager;
    
    // Constructor is used to inject dependencies into the service.
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function addNewOrder($data, $cart)
    {
        $arrItems = $cart->items;
        $date_created = date('Y-m-d H:i:s');

        //create address
        $province = $this->entityManager->getRepository(Province::class)
            ->findOneByName($data['province']);
            
        foreach ($province->getDistricts() as $district) {
            if($district->getName() == $data['district']){
                $cur_district = $district;break;
            }
        }
        

        $address = new Address();
        
        $address->setAddress($data['address']);
        $address->setDateCreated($date_created);
        $address->setDistrict($cur_district);
        $this->entityManager->persist($address);
        
        
        $order = new Order();
        
        $order->setName($data['full_name']);
        $order->setPhone($data['phone_number']);
        $order->setEmail($data['email']);
        $order->setAddress($address);

        $order->setNumberOfItems($cart->totalItem);
        $order->setCost($data['total_price']);
        $order->setStatus(Order::STATUS_PENDING);
        $order->setDateCreated($date_created);
        if (!empty($data['user_id'])) {
            $user = $this->entityManager
                ->getRepository(User::class)->find($data['user_id']);
            $order->setUser($user);
            
        }    
        
        $this->entityManager->persist($order);
        if (!empty($data['user_id'])) {
            $user = $this->entityManager
                ->getRepository(User::class)->find($data['user_id']);
            $admin = $this->entityManager
                ->getRepository(User::class)->find(1);
            $activity = new Activity();
            $activity->setSender($user);
            $activity->setReceiver($admin);
            $activity->setType(Activity::ORDER);
            $activity->setTarget($order);
            $activity->setDateCreated($date_created);
            $this->entityManager->persist($activity);
        }
        

        foreach ($arrItems as $item) {
            $arr = explode("_", $item->id);
            $color_id = array_search($arr[1], $this->list_color);
            $size_id = array_search($arr[2], $this->list_size);

            $orderItem = new OrderItem();
            
            $product = $this->entityManager->
                getRepository(Product::class)->find((int)$arr[0]);
            $productMasters = $product->getProductMasters();
            
            foreach ($productMasters as $pM) {
                if ($pM->getColorId() == $color_id && $pM->getSizeId() == $size_id) {
                    $orderItem->setProductMaster($pM);
                    break;
                }
            }

            $orderItem->setOrder($order);
            $orderItem->setQuantity($item->quantity);
            $orderItem->setCost($item->price);
            //$orderItem->setStatus($data['status']);
            $orderItem->setDateCreated($date_created);

            $this->entityManager->persist($orderItem);
        }

        $this->entityManager->flush();
        return $order;
    }

    public function getOrder($order_id, $email) 
    {
        $order_id = self::decryptByOrderId($order_id);
        $order = $this->entityManager->getRepository(Order::class)->find($order_id);
        if ($email != $order->getEmail()) return null;
        return $order;
    }

    public function encryptByOrderId($data) {
        return base64_encode($data);
    }
    public function decryptByOrderId($data) {
        return base64_decode($data);
    }
}
