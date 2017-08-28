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

class IndexController extends AbstractActionController
{
	/**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
	private $entityManager;

	public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;        
    }

    public function indexAction()
    {
    	$products = $this->entityManager->getRepository(Product::class)->findAll();
    	$stores = $this->entityManager->getRepository(Store::class)->findAll();
    	$users = $this->entityManager->getRepository(User::class)->findAll();

    	$number_of_users = count($users);
    	$number_of_products = count($products);
    	$number_of_stores = count($stores);

        return new ViewModel([
            'number_of_users' => $number_of_users,
            'number_of_products' => $number_of_products,
            'number_of_stores' => $number_of_stores
            ]);
    }
}
