<?php
namespace Application\Service;

use Application\Entity\Product;
use Zend\Filter\StaticFilter;
use Application\Entity\Comment;

class ProductManager 
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

    public function getCommentCountStr($product)
    {
        $commentCount = count($product->getComments());
        if ($commentCount == 0)
            return 'No comments';
        else if ($commentCount == 1) 
            return '1 comment';
        else
            return $commentCount . ' comments';
    }

    // This method adds a new comment to .
    public function addCommentToProduct($product, $data) 
    {
        // Create new Comment entity.
        $data['user'] = $this->entityManager->
            getRepository('Application\Entity\User')->find($data['user_id']);
        $comment = new Comment();
        $comment->setProduct($product);
        $comment->setUser($data['user']);
        $comment->setContent($data['comment']);        
        $currentDate = date('Y-m-d H:i:s');
        $comment->setDateCreated($currentDate);

        // Add the entity to entity manager.
        $this->entityManager->persist($comment);

        // Apply changes.
        $this->entityManager->flush();
    }   
}
