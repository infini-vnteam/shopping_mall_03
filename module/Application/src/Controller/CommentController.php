<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Product;
use Zend\Session\Container;
use Application\Entity\Comment;

class CommentController extends AbstractActionController
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

    public function addAction()
    {
        $data = $this->params()->fromPost();
        $id = $data['product_id'];
        $product = $this->entityManager->getRepository(Product::class)
            ->find($id);

        if ($product == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        
        $data = $this->params()->fromPost();
        $data['user_id'] = $this->sessionContainer->id;

        $this->productManager->addCommentToProduct($product, $data);
        $data['username'] = $this->sessionContainer->name;
        $data_json = json_encode($data);
        $this->response->setContent($data_json);
        return $this->response;
    }

    public function deleteAction()
    {
        $data = $this->params()->fromPost();
        $commentId = $data['comment_id'];
            
        $comment = $this->entityManager->getRepository(Comment::class)
                    ->findOneById($commentId);       
        if ($comment == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        }

        if ($this->sessionContainer->id != $comment->getUser()->getId()) {
            $this->getResponse()->setStatusCode(404);
            return; 
        }        
            
        $this->entityManager->remove($comment);   
        $this->entityManager->flush();
        return $this->response;
    }
}
