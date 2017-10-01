<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Form\UserForm;
use Application\Entity\User;
use Application\Entity\Activity;


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
        // countOfOrderByStatus[$i] : count of pending,skip,success order;
        for($i = 1; $i < 4; $i++){
            $countOfOrderByStatus[$i] = 0;
        }
        
        foreach ($orders as $o) {
            if($o->getStatus() == 3){
                $countOfOrderByStatus[3]++;
                $total_pays += $o->getCost();
                $total_purchased += $o->getNumberOfItems();
            }
            if ($o->getStatus() == 2){
                $countOfOrderByStatus[2]++;
            }
            if ($o->getStatus() == 1){
                $countOfOrderByStatus[1]++;
            }
        }
        
        $activities_by_day = $user->getActivitiesByDay(0, 2);

        return new ViewModel([
            'total_pays' => $total_pays,
            'total_purchased' => $total_purchased,
            'countOfOrderByStatus' => $countOfOrderByStatus,
            'orders' => $orders,
            'activities_by_day' => $activities_by_day,
            'user' => $user
        ]);
    }

    public function loadmoreactionAction()
    {
        $data = $this->params()->fromPost();
        $user = $this->entityManager->getRepository(User::class)->find($data['user_id']);
        $activities_by_day = $user
            ->getActivitiesByDay($data['index'], 2);


        $data_json = json_encode($activities_by_day);
        $this->response->setContent($data_json);
        
        return $this->response;
    }
}

