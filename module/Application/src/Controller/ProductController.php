<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Product;
use Application\Entity\ProductMaster;
use Application\Entity\Product_image;
use Zend\Session\Container;
use Application\Form\CommentForm;
use Application\Entity\Comment;

/**
 * This controller is responsible for user management (adding, editing,
 * viewing users and changing user's password).
 */
class ProductController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var
     */
    private $entityManager;


    private $sessionContainer;

    private $productManager;

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

    /**
     * Constructor.
     */
    public function __construct($entityManager, $sessionContainer, $productManager)
    {
        $this->entityManager = $entityManager;
        $this->sessionContainer = $sessionContainer;
        $this->productManager = $productManager;
    }

    /**
     * The "view" action displays a page allowing to view user's details.
     */
    public function viewAction()
    {
//        $comments = $product->getComments();
//        $commentCount = $this->productManager->getCommentCountStr($product);

//        $comment_form = new CommentForm();
        $productId = $this->params()->fromRoute('id', -1);

        $product = $this->entityManager->getRepository(Product::class)
            ->findOneById($productId);
            // /var_dump($product->getPrice());die();
        if ($product == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        if ($this->getRequest()->isPost()) {

            $data = $this->getRequest()->getContent();
            $data = json_decode($data);

            switch ($data->type) {
                case 'comment':

                    // db
                    // $data->content
                    // $data->user_id
                    // $data->product_id

                    $res = [
                        'content' => $data->content,
                        'user_id' => $data->user_id,
                    ];
                    $comment = $this->productManager->addComment($product, $res);
                    $res['id'] = $comment->getId();
                    $this->response->setContent(json_encode($res));
                    break;
                case 'reply':

                    //db
                    // $data->content
                    // $data->user_id
                    // $data->product_id
                    // $data->comment_id // parent comment

                    $res = [
                        'comment_id' => $data->comment_id,
                        'user_id' => $data->user_id,
                        'content' => $data->content,
                    ];
                    $comment = $this->productManager->addComment($product, $res);
                    $res['id'] = $comment->getId();
                    $this->response->setContent(json_encode($res));
                    break;
                case 'review':

                    // database
                    // $data->user_id
                    // $data->product_id
                    // $data->review->rate
                    // $data->review->content

                    $res = [
                        'rate' => $data->review->rate,
                        'user_id' => $data->user_id,
                        'content' => $data->review->content,
                    ];
                    $review = $this->productManager->addReview($product, $res);
                    $res['id'] = $review->getId();
                    $this->response->setContent(json_encode($res));
                    break;
                case 'delete_comment':
                    // database
                    // $data->user_id
                    // $data->product_id
                    // $data->comment_id

                    $res = [
                        'comment_id' => $data->comment_id,
                        'type' => $data->type,
                    ];
                    $this->productManager->deleteComment($res);
                    $res['status'] = 'done';
                    $this->response->setContent(json_encode($res));
                    break;
                case 'delete_reply':
                    // database
                    // $data->user_id
                    // $data->product_id
                    // $data->comment_id
                    // $data->parent_id

                    $res = [
                        'comment_id' => $data->comment_id,
                        'type' => $data->type,
                        'parent_id' => $data->parent_id,
                    ];
                    $this->productManager->deleteComment($res);
                    $res['status'] = 'done';
                    $this->response->setContent(json_encode($res));
                    break;
                default:
                    $this->response->setContent(json_encode('error'));
            }
            return $this->response;
        }

        $view = new ViewModel([

        ]);
        $this->layout('application/layout');
        return $view;
    }

    public function getinfoAction()
    {
        $productId = $this->params()->fromRoute('id', -1);

        $product = $this->entityManager->getRepository(Product::class)
            ->findOneById($productId);

        if ($product == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        if ($product->getStatus() == 0) {
            $this->getResponse()->setContent(json_encode([
                'error' => 'Product is inactive',
            ]));
            return $this->getResponse();
        }

        // get color in word
        $colors = $product->getColors();
        $colors_in_word = [];
        foreach ($colors as $c) {
            array_push($colors_in_word, $this->list_color[$c]);
        }

        // get size and image each Color
        $size_and_images = $product->getSizeAndImageEachColors();

        foreach ($size_and_images as $key => $s_a_i) {
            $sizes_each_color[$this->list_color[$key]] = [];
            $images_each_color[$this->list_color[$key]] = [];

            foreach ($s_a_i['size'] as $size) {
                array_push($sizes_each_color[$this->list_color[$key]], $this->list_size[$size]);
            }

            array_push($images_each_color[$this->list_color[$key]], $s_a_i['image'][0]);
            foreach ($s_a_i['image'][1] as $img) {
                array_push($images_each_color[$this->list_color[$key]], $img);
            }
        }

        // get Review
        $reviews = $product->getReviews();
        $review_items = [];
        foreach ($reviews as $r) {
            $item = $r->getInfoReview();
            array_push($review_items, $item);
        }

        //comment
        $comments = $product->getMainComments();
        $comment_items = [];
        foreach ($comments as $c) {
            $item = $c->getInfoComment();
            array_push($comment_items, $item);
        }

        $data = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'keywords' => $product->getInfoKeywords(),
            'category' => $product->getAllCategory(),
            'rate_sum' => $product->getRateSum(),
            'rate_count' => $product->getRateCount(),
            'review' => [
                'size' => 10,
                'page' => 1,
                'total' => 120,
                'items' => $review_items,
            ],
            'comment' => [
                'size' => 10,
                'page' => 1,
                'total' => 30,

                'items' => $comment_items,
            ],
            'colors' => $colors_in_word,
            'sizes' => $sizes_each_color,
            'images' => $images_each_color,
            'intro' => $product->getIntro(),
            'description' => $product->getDescription(),
            'current_sale' => $product->getCurrentSale(),
            'current_price' => $product->getCurrentPrice(),
        ];

        $this->response->setContent(json_encode($data));
        return $this->response;
    }

}
