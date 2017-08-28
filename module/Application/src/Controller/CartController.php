<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Application\Form\ShippingForm;
use Application\Entity\User;

/**
 * This controller is responsible for cart management (adding, editing,
 * viewing cart and changing user's password).
 */
class CartController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var
     */
    private $entityManager;

    private $sessionContainer;
    private $cartManager;
    private $orderManager;
    /**
     * Constructor.
     */
    public function __construct($entityManager, $cartManager, $orderManager)
    {
        $this->entityManager = $entityManager;
        $this->cartManager = $cartManager;
        $this->orderManager = $orderManager;
        $this->sessionContainer = new Container('UserLogin');

    }

    /**
     * The "view" action displays a page allowing to view cart's details.
     */
    public function viewAction()
    {
        $view = new ViewModel([

        ]);
        $this->layout('application/layout');
        return $view;
    }

    public function addAction()
    {
        $cookie = $this->getRequest()->getCookie('Cart', 'default');
        $cart_info = json_decode($cookie["Cart"]);
        $user = null;
        if (isset($this->sessionContainer->id)) {
            $id = $this->sessionContainer->id;
            $user = $this->entityManager->getRepository(User::class)
                ->find($id);

            if ($user == null) {
                $this->getResponse()->setStatusCode(500);
                return;
            }
            $form = new ShippingForm('update', $this->entityManager);
            $data = [
                "full_name" => $user->getName(),
                "address" => $user->getAddress(),
                "phone" => $user->getPhone(),
            ];

            $form->setData($data);
        } else {
            $form = new ShippingForm('create', $this->entityManager);
        }

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();
                $currentDate = date('Y-m-d H:i:s');
                $data['date_created'] = $currentDate;
                $data['status'] = 1;
                $arrItems = $cart_info->items;
                
                $this->orderManager->addNewOrder($data, $cart_info, $arrItems);

                $cookie = new \Zend\Http\Header\SetCookie(
                    'Cart',
                    '',
                    strtotime('-1 Year', time()), // -1 year lifetime (negative from now)
                    '/'
                );
                $this->getResponse()->getHeaders()->addHeader($cookie);

                // Redirect to "notify" page
                return $this->redirect()->toRoute('cart',
                    ['action' => 'notify']);
            }
        }



        $view = new ViewModel([
            'data' => $cart_info,
            'user' => $user,
            'form' => $form,
        ]);
        $this->layout('application/layout');
        return $view;
    }

    public function notifyAction()
    {
        $view = new ViewModel([

        ]);
        $this->layout('application/layout');
        return $view;
    }
}
