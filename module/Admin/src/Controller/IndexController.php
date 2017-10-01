<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Product;
use Application\Entity\User;
use Application\Entity\Store;
use Application\Entity\Province;
use Application\Entity\Order;

class IndexController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Sale Program manager.
     * @var Application\Service\SaleProgramManager
     */
    private $saleProgramManager;

    private $statisticManager;

    private $elasticSearchManager;

    private $productElasticSearchManager;

    public function __construct(
        $entityManager,
        $saleProgramManager,
        $statisticManager,
        $elasticSearchManager,
        $productElasticSearchManager
    )
    {
        $this->entityManager = $entityManager;
        $this->saleProgramManager = $saleProgramManager;
        $this->statisticManager = $statisticManager;
        $this->elasticSearchManager = $elasticSearchManager;
        $this->productElasticSearchManager = $productElasticSearchManager;
    }

    public function indexAction()
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        $orders = $this->entityManager->getRepository(Order::class)->findAll();
        $users = $this->entityManager->getRepository(User::class)->findAll();

        $number_of_users = count($users);
        $number_of_products = count($products);
        $number_of_orders = count($orders);

        $sale_prodgram_need_active = $this->saleProgramManager->getSaleProgramNeedActive();
        $sale_prodgram_need_done = $this->saleProgramManager->getSaleProgramNeedDone();

        $order_pendings = $this->entityManager->getRepository(Order::class)
            ->findBy(["status" => Order::STATUS_PENDING], ['date_created' => 'DESC']);
        $order_shippings = $this->entityManager->getRepository(Order::class)
            ->findBy(["status" => Order::STATUS_SHIPPING], ['date_created' => 'DESC']);

        return new ViewModel([
            'number_of_users' => $number_of_users,
            'number_of_products' => $number_of_products,
            'number_of_orders' => $number_of_orders,
            'sale_prodgram_need_active' => $sale_prodgram_need_active,
            'sale_prodgram_need_done' => $sale_prodgram_need_done,
            'order_pendings' => $order_pendings,
            'order_shippings' => $order_shippings
        ]);
    }

    public function chartAction()
    {
        return new ViewModel();
    }

    public function orderchartAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data_order = $this->statisticManager->getOrderStatistic($data['type']);
            $data_json = json_encode($data_order);

            $this->response->setContent($data_json);

            return $this->response;
        }
    }

    public function reviewchartAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data_review = $this->statisticManager->getReviewStatistic($data['type']);
            $data_json = json_encode($data_review);

            $this->response->setContent($data_json);

            return $this->response;
        }
    }

    public function userchartAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data_user = $this->statisticManager->getUserStatistic($data['type']);
            $data_json = json_encode($data_user);

            $this->response->setContent($data_json);

            return $this->response;
        }
    }

    public function moneychartAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data_money = $this->statisticManager->getMoneyStatistic($data['type']);
            $data_json = json_encode($data_money);

            $this->response->setContent($data_json);

            return $this->response;
        }
    }

    public function initElasticSearchAction()
    {
        $result = $this->elasticSearchManager->createIndex('infinishop');

        $products = $this->entityManager->getRepository(Product::class)->findAll();

        foreach ($products as $p) {
            $this->productElasticSearchManager->updateProduct($p);
        }

        $this->response->setContent(json_encode($result));
        return $this->response;
    }
}
