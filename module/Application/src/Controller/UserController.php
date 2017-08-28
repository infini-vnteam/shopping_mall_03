<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\User;
use Application\Form\UserForm;
use Application\Form\PasswordChangeForm;
use Application\Form\PasswordResetForm;
use Zend\Session\Container;

/**
 * This controller is responsible for user management (adding, editing,
 * viewing users and changing user's password).
 */
class UserController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var
     */
    private $entityManager;

    /**
     * User manager.
     * @var
     */
    private $userManager;

    private $sessionContainer;

    /**
     * Constructor.
     */
    public function __construct($entityManager, $userManager, $sessionContainer)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        $this->sessionContainer = $sessionContainer;
    }

    /**
     * This action displays a page allowing to add a new user.
     */
    public function addAction()
    {

        if ($this->userManager->isLogin()) {
            // Get redirect URL.
            $redirectUrl = $this->params()->fromPost('redirect_url', '');

            if (!empty($redirectUrl)) {
                // The below check is to prevent possible redirect attack
                // (if someone tries to redirect user to another domain).
                $uri = new Uri($redirectUrl);
                if (!$uri->isValid() || $uri->getHost()!=null)
                    throw new \Exception('Incorrect redirect URL: ' . $redirectUrl);
            }

            // If redirect URL is provided, redirect the user to that URL;
            // otherwise redirect to Home page.
            if(empty($redirectUrl)) {
                return $this->redirect()->toRoute('home');
            } else {
                $this->redirect()->toUrl($redirectUrl);
            }
        }

        // Create user form
        $form = new UserForm('create', $this->entityManager);

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                // Add user.
                $user = $this->userManager->addUser($data);

                // Redirect to "view" page
                return $this->redirect()->toRoute('user',
                    ['action' => 'edit']);
            }
        }

        $view = new ViewModel([
            'form' => $form
        ]);

        $this->layout('application/layout');
        return $view;
    }

    /**
     * The "view" action displays a page allowing to view user's details.
     */
    public function viewAction()
    {
        if (isset($this->sessionContainer->id)) {
            $id = $this->sessionContainer->id;
        } else {
            $this->getResponse()->setStatusCode(404);
            return;
        }

//        $id = (int)$this->params()->fromRoute('id', -1);
//        if ($id < 1) {
//            $this->getResponse()->setStatusCode(404);
//            return;
//        }

        // Find a user with such ID.
        $user = $this->entityManager->getRepository(User::class)
            ->find($id);

        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $view = new ViewModel([
            'user' => $user
        ]);
        $this->layout('application/layout');
        return $view;
    }

    /**
     * The "edit" action displays a page allowing to edit user.
     */
    public function editAction()
    {
        if (isset($this->sessionContainer->id)) {
            $id = $this->sessionContainer->id;
        } else {
            $this->getResponse()->setStatusCode(404);
            return;
        }

//        $id = (int)$this->params()->fromRoute('id', -1);
//        if ($id < 1) {
//            $this->getResponse()->setStatusCode(404);
//            return;
//        }

        $user = $this->entityManager->getRepository(User::class)
            ->find($id);

        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Create user form
        $form = new UserForm('update', $this->entityManager, $user);

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                // Update the user.
                $this->userManager->updateUser($user, $data);

                // Edit session
                $sessionContainer = new Container('UserLogin');
                $sessionContainer->name = $data['full_name'];

                // Redirect to "view" page
                return $this->redirect()->toRoute('user',
                    ['action' => 'view', 'id' => $user->getId()]);
            } else {
                $data = $form->getData();
            }
        } else {
            $form->setData(array(
                'full_name' => $user->getName(),
                'email' => $user->getEmail(),
                'phone' => $user->getPhone(),
                'address' => $user->getAddress(),
            ));
        }

        $view = new ViewModel(array(
            'user' => $user,
            'form' => $form
        ));
        $this->layout('application/layout');
        return $view;
    }

    /**
     * This action displays a page allowing to change user's password.
     */
    public function changePasswordAction()
    {
        if (isset($this->sessionContainer->id)) {
            $id = $this->sessionContainer->id;
        } else {
            $this->getResponse()->setStatusCode(404);
            return;
        }

//        $id = (int)$this->params()->fromRoute('id', -1);
//        if ($id < 1) {
//            $this->getResponse()->setStatusCode(404);
//            return;
//        }

        $user = $this->entityManager->getRepository(User::class)
            ->find($id);

        if ($user == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Create "change password" form
        $form = new PasswordChangeForm('change');

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Get filtered and validated data
                $data = $form->getData();

                // Try to change password.
                if (!$this->userManager->changePassword($user, $data)) {
                    $this->flashMessenger()->addErrorMessage(
                        'Sorry, the old password is incorrect. Could not set the new password.');
                } else {
                    $this->flashMessenger()->addSuccessMessage(
                        'Changed the password successfully.');
                }

                // Redirect to "view" page
                return $this->redirect()->toRoute('user',
                    ['action' => 'view', 'id' => $user->getId()]);
            }
        }

        $view = new ViewModel([
            'user' => $user,
            'form' => $form
        ]);

        $this->layout('application/layout');
        return $view;
    }

    /**
     * This action displays the "Reset Password" page.
     */
    public function resetPasswordAction()
    {
        // Create form
        $form = new PasswordResetForm();

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                // Look for the user with such email.
                $user = $this->entityManager->getRepository(User::class)
                    ->findOneByEmail($data['email']);
                if ($user != null) {
                    // Generate a new password for user and send an E-mail
                    // notification about that.
                    $this->userManager->generatePasswordResetToken($user);

                    // Redirect to "message" page
                    return $this->redirect()->toRoute('user',
                        ['action' => 'message', 'id' => 'sent']);
                } else {
                    return $this->redirect()->toRoute('user',
                        ['action' => 'message', 'id' => 'invalid-email']);
                }
            }
        }

        $view = new ViewModel([
            'form' => $form
        ]);
        $this->layout('application/layout');
        return $view;
    }

    /**
     * This action displays an informational message page.
     * For example "Your password has been resetted" and so on.
     */
    public function messageAction()
    {
        // Get message ID from route.
        $id = (string)$this->params()->fromRoute('id');

        // Validate input argument.
        if ($id != 'invalid-email' && $id != 'sent' && $id != 'set' && $id != 'failed') {
            throw new \Exception('Invalid message ID specified');
        }

        $view = new ViewModel([
            'id' => $id
        ]);

        $this->layout('application/layout');
        return $view;
    }

    /**
     * This action displays the "Reset Password" page.
     */
    public function setPasswordAction()
    {
        $token = $this->params()->fromQuery('token', null);

        // Validate token length
        if ($token != null && (!is_string($token) || strlen($token) != 32)) {
            throw new \Exception('Invalid token type or length');
        }

        if ($token === null ||
            !$this->userManager->validatePasswordResetToken($token)
        ) {
            return $this->redirect()->toRoute('user',
                ['action' => 'message', 'id' => 'failed']);
        }

        // Create form
        $form = new PasswordChangeForm('reset');

        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {

            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {

                $data = $form->getData();

                // Set new password for the user.
                if ($this->userManager->setNewPasswordByToken($token, $data['new_password'])) {

                    // Redirect to "message" page
                    return $this->redirect()->toRoute('user',
                        ['action' => 'message', 'id' => 'set']);
                } else {
                    // Redirect to "message" page
                    return $this->redirect()->toRoute('user',
                        ['action' => 'message', 'id' => 'failed']);
                }
            }
        }

        $view = new ViewModel([
            'form' => $form
        ]);

        $this->layout('application/layout');
        return $view;
    }
}
