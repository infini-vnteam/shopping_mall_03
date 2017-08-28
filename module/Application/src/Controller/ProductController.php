<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Product;
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
        $id = (int)$this->params()->fromRoute('id', -1);

        $product = $this->entityManager->getRepository(Product::class)
            ->find($id);

        if ($product == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $comments = $product->getComments();
        $commentCount = $this->productManager->getCommentCountStr($product);

        $comment_form = new CommentForm();

        if($this->getRequest()->isPost()) {

            $data = $this->params()->fromPost();
                
            $comment_form->setData($data);
            if($comment_form->isValid()) {
                                     
                // Get validated form data.
                $data = $comment_form->getData();
                $data['user_id'] = $this->sessionContainer->id;
                // Use product manager service to add new comment to product.
                $this->productManager->addCommentToProduct($product, $data);
                        
                // Redirect the user again to "view" page.
                return $this->redirect()->toRoute('product', ['action'=>'view', 'id' => $id]);
            }
        }
        $view = new ViewModel([
            'user_id' => $this->sessionContainer->id,
            'commentCount' => $commentCount,
            'comment_form' => $comment_form,
            'comments' => $comments,
            'product' => $product
        ]);
        $this->layout('application/layout');
        return $view;
    }
}
