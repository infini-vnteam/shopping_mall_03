<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Category;
use Application\Entity\Product;
use Application\Entity\Province;
use Application\Entity\User;
use Admin\Helper\TrunCate;
use Zend\Mail;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class HomeController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var
     */
    private $entityManager;
    private $productManager;
    private $categoryManager;

    private $sqlManager;
    private $elasticSearchManager;
    private $sessionContainer;

    public function __construct(
        $entityManager,
        $categoryManager,
        $productManager,
        $sqlManager,
        $elasticSearchManager,
        $sessionContainer
    )
    {
        $this->entityManager = $entityManager;
        $this->categoryManager = $categoryManager;
        $this->productManager = $productManager;
        $this->sqlManager = $sqlManager;
        $this->elasticSearchManager = $elasticSearchManager;
        $this->sessionContainer = $sessionContainer;
    }

    public function indexAction()
    {
        $newProducts = $this->entityManager->getRepository(Product::class)->findBy(['status' => Product::STATUS_PUBLISHED], ['date_created' => 'DESC'], COUNT_IMAGES_IN_ROW);

        $bestSell = $this->productManager->getBestSellsInCurrentMonth(COUNT_IMAGES_IN_ROW);

        $bestSellProducts = $this->entityManager->getRepository(Product::class)->findBy(['id' => $bestSell, 'status' => Product::STATUS_PUBLISHED]);
        $bestSales = array_keys($this->productManager->getBestSaleProduct(COUNT_IMAGES_IN_ROW));
        $bestSaleProducts = $this->entityManager->getRepository(Product::class)->findBy(['id' => $bestSales, 'status' => Product::STATUS_PUBLISHED]);
        //var_dump($bestSaleProducts);die();
        $view = new ViewModel([
            'newProducts' => $newProducts,
            'bestSellProducts' => $bestSellProducts,
            'bestSaleProducts' => $bestSaleProducts
        ]);
        $this->layout('application/layout');

        return $view;
    }

    public function viewAction()
    {
        //var_dump(2);die();


        $view = new ViewModel();
        $this->layout('application/home');
        return $view;
    }

    public function searchAction()
    {
        $this->layout('application/layout');
        return new ViewModel();
    }

    public function getDataSearchAction()
    {
        $data = $this->getRequest()->getContent();
        $data = json_decode($data);
        $content = $data->content;

        $keywords = explode(' ', $content);

        $colors = [
            'purple',
            'black',
            'blue',
            'green',
            'orange',
            'grey',
            'red',
            'white',
            'yellow',
        ];

        $categories_name = $this->categoryManager->getAllCategories();

        $key_colors = [];
        $key_categories = [];
        $key_other = [];

        foreach ($keywords as $key) {
            if (in_array(strtolower($key), $categories_name)) {
                array_push($key_categories, $key);
            } else if (in_array(strtolower($key), $colors)) {
                array_push($key_colors, $key);
            } else {
                array_push($key_other, $key);
            }
        }

        $query = [
            'bool' => [
                'should' => [
                    [
                        'common' => [
                            'name' => [
                                'query' => $content,
                            ],
                        ],
                    ],
                ]
            ]
        ];

        if (count($key_colors) > 0 || count($key_categories) > 0) {
            $query['bool']['must'] = [];
        }

        if (count($key_colors) > 0) {
            foreach ($key_colors as $color) {
                array_push($query['bool']['must'], [
                    'match' => ['colors' => $color]
                ]);
            }
        }

        if (count($key_categories) > 0) {
            foreach ($key_categories as $category) {
                array_push($query['bool']['must'], [
                    'term' => ['categories' => $category]
                ]);
            }
        }

        if (count($key_other) > 0) {
            $key_other = join(" ", $key_other);
            array_push($query['bool']['should'], [
                'common' => [
                    'keywords' => [
                        'query' => $key_other,
                    ],
                ],
            ]);
            array_push($query['bool']['should'], [
                'common' => [
                    'intro' => [
                        'query' => $key_other,
                        'cutoff_frequency' => 0.001,
                        'low_freq_operator' => 'and'
                    ],
                ],
            ]);
        }

        $params = [
            'index' => 'infinishop',
            'type' => 'product',
            'body' => [
                'query' => $query
            ]
        ];

        $results = $this->elasticSearchManager->getClient()->search($params);

        $this->response->setContent(json_encode($results['hits']['hits']));

        return $this->response;
    }

    public function loaddistrictAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();

            if (!$data['province_id'] && !$data['province_name']) {
                $data = json_decode($this->getRequest()->getContent());
                $data = [
                    'province_name' => $data->province_name,
                ];
            }

            if ($data['province_id']) {
                $province_id = $data['province_id'];
                $province = $this->entityManager->getRepository(Province::class)
                    ->find($province_id);
            } else {
                $province_name = $data['province_name'];
                $province = $this->entityManager->getRepository(Province::class)
                    ->findOneBy(array('name' => $province_name));;
            }

            if ($province == null) {
                $this->getResponse()->setStatusCode(404);
                return;
            }

            $districts = $province->getDistricts();
            foreach ($districts as $d) {
                $districts_for_select[$d->getId()] = $d->getName();
            }

            $data_json = json_encode($districts_for_select);
            $this->response->setContent($data_json);
            return $this->response;
        }

        return 'only post method accepted';
    }

    public function loadprovinceAction()
    {
        $provinces = $this->entityManager->getRepository(Province::class)
            ->findAll();
        $arr = [];
        foreach ($provinces as $prov) {
            array_push($arr, $prov->getName());
        }
        $this->response->setContent(json_encode($arr));
        return $this->response;
    }

    public function addViewAction()
    {

        $views = 0;

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $views = $this->productManager->addView((int)($data['product_id']));

        }
        $this->response->setContent(json_encode($views));

        return $this->response;
    }


    public function sqlTestAction()
    {

        $this->sqlManager->sqlAddress();
        $this->sqlManager->sqlUser();
        $this->sqlManager->sqlCategory();
        $this->sqlManager->sqlProduct();
        $this->sqlManager->sqlProductColorImage();
        $this->sqlManager->sqlSaleProgram();
        $this->sqlManager->sqlKeyword();
        $this->sqlManager->sqlReview();
        $this->sqlManager->sqlComment();
        $this->sqlManager->sqlOrder();
        $this->sqlManager->sqlUpdate();
        die();
    }

    public function loadNotificationAction()
    {
        if (isset($this->sessionContainer->id)) {
            $user_id = $this->sessionContainer->id;
            $user = $this->entityManager->getRepository(User::class)->find($user_id);
            $unread_notifications = $user->getUnreadNotifications();
            $data['unread_count'] = count($unread_notifications);
            foreach ($unread_notifications as $un) {
                array_push($data, $un->getNotiContent());
            }

            $this->response->setContent(json_encode($data));
        } else {
            $this->response->setContent(json_encode([
                'error' => 'You need login',
            ]));
        }

        return $this->response;
    }
}
