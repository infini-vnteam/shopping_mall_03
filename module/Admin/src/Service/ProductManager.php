<?php
namespace Admin\Service;

use Application\Entity\Product;
use Zend\Filter\StaticFilter;
use Application\Entity\Keyword;

// The UserManager service is responsible for adding new posts.
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


    
    // This method adds a new product.
    public function addNewProduct($data)
    {
        $product = new Product();

        $data['store'] = $this->entityManager->
            getRepository('Application\Entity\Store')->find($data['store_id']);
        $data['category'] = $this->entityManager->
            getRepository('Application\Entity\Category')->find($data['category_id']);
        $currentDate = date('Y-m-d H:i:s');
        $data['date_created'] = $currentDate;
        
        $product->setName($data['name']);
        $product->setAlias($data['alias']);
        $product->setPrice($data['price']);
        $product->setIntro($data['intro']);
        $product->setPrice($data['price']);
        $product->setDescription($data['description']);
        $product->setColor($data['color']);
        $product->setQuantity($data['quantity']);
        $product->setSize($data['size']);
        if ($data['category'] != null)
            $product->setCategory($data['category']);
        if ($data['store'] != null)
            $product->setStore($data['store']);
        if($data['image'] != null)
            $product->setImage($data['image']);
        else $product->setImage('/img/products/default.jpg');
        $product->setDateCreated($data['date_created']);

        // Add the entity to entity manager.
        $this->entityManager->persist($product);
        $this->addKeywordsToProduct($data['keywords'], $product);
        $this->entityManager->flush();
    }

    public function updateProduct($product, $data) 
    {
        $data['store'] = $this->entityManager->
            getRepository('Application\Entity\Store')->find($data['store_id']);
        $data['category'] = $this->entityManager->
            getRepository('Application\Entity\Category')->find($data['category_id']);
        
        $product->setName($data['name']);
        $product->setAlias($data['alias']);
        $product->setPrice($data['price']);
        $product->setIntro($data['intro']);
        $product->setImage($data['image']);
        $product->setColor($data['color']);
        $product->setQuantity($data['quantity']);
        $product->setSize($data['size']);
        $product->setPopular_level($data['popular_level']);
        $product->setDescription($data['description']);
        $product->setStatus($data['status']);
        $product->setSale($data['sale']);
        if ($data['category'] != null)
            $product->setCategory($data['category']);
        if ($data['store'] != null)
            $product->setStore($data['store']);
        
        // Apply changes to database.
        $this->entityManager->flush();
    }

    public function removeProduct($product)
    {   
        //remove comment
        $comments = $product->getComments();
        foreach ($comments as $comment) {
            $this->entityManager->remove($comment);
        }

        //remove keyword
        $productkeys = $product->getKeywords();
            foreach ($productkeys as $productkey) {
                $this->entityManager->remove($productkey);
        }
        $this->entityManager->remove($product);   
        $this->entityManager->flush();
    }

    public function convertKeywordsToString($product)
    {
        $keywords = $product->getKeywords();
        $keywordsCount = count($keywords);
        $keywordsStr = '';
        $i = 0;
        foreach ($keywords as $kw) {
            $i ++;
            $keywordsStr .= $kw->getKeyword();
            if ($i < $keywordsCount) 
                $keywordsStr .= ', ';
        }
        
        return $keywordsStr;
    }

    private function addKeywordsToProduct($keywordsStr, $product) 
    {
        // Remove tag associations (if any)
        $keywords = $product->getKeywords();
        foreach ($keywords as $keyword) {            
            $product->removeKeyWordAssociation($keyword);
        }
            
        // Add tags to product
        $keywords = explode(',', $keywordsStr);
        foreach ($keywords as $kword) {           
            $kword = StaticFilter::execute($kword, 'StringTrim');
            if (empty($kword)) {
                continue; 
            }
                
            $keyword = $this->entityManager->getRepository(Keyword::class)
                        ->findOneByKeyword($kword);
            if ($keyword == null)
                $keyword = new KeyWord();
            
            $keyword->setKeyword($kword);
            $currentDate = date('Y-m-d H:i:s');
            $keyword->setDateCreated($currentDate);
            $keyword->addProduct($product);
                    
            $this->entityManager->persist($keyword);
                    
            $product->addKeyword($keyword);
        }
    }
}
