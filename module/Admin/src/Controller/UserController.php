<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Form\UserForm;
use Application\Entity\User;


class UserController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * User manager.
     * @var Application\Service\UserManager
     */
    private $userManager;

    /**
     * Constructor is used for injecting dependencies into the controller.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
        
    }

    /**
     * This action displays the "New User" page. The page contains a form allowing
     * to enter post title, content and tags. When the user clicks the Submit button,
     * a new Post entity will be created.
     */
    public function indexAction(){
    	return ViewModel();
    }

    public function listAction(){
        //var_dump(2);die();
    	$users = $this->entityManager->getRepository(User::class)->findAll();
        // Render the view template

        return new ViewModel([
            'users' => $users
        ]);
    }

    public function viewAction(){
        //var_dump(2);die();
        $userId = $this->params()->fromRoute('id', -1);

        $user = $this->entityManager->getRepository(User::class)->find($userId);
        if ($user == null) {
            $this->getResponse()->setStatusCode(404);                      
        }

        $orders = $user->getOrders();

        //setup new order variable
        $pending_order_exist = false;
        $new_orders = 0;
        $total_pays = 0;
        $total_purchased = 0;
        foreach ($orders as $o) {
            $total_pays+=$o->getCost();
            $total_purchased+=$o->getNumberOfItems();
            if ($o->getStatus() == 1){
                $pending_order_exist = true;
                $new_orders++;
                break;
            }
        }

        return new ViewModel([
            'total_pays' => $total_pays,
            'total_purchased' => $total_purchased,
            'pending_order_exist' => $pending_order_exist,
            'new_orders' => $new_orders,
            'orders' => $orders,
            'user' => $user
        ]);
    }
}

