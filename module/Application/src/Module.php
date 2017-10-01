<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\AbstractActionController;
use Application\Controller\AuthController;
use Admin\Controller\CategoryController;
use Admin\Controller\IndexController;
use Admin\Controller\OrderController;
use Admin\Controller\ProductController;
use Admin\Controller\StoreController;
use Admin\Controller\UserController;
use Application\Service\AuthManager;
use Zend\Session\SessionManager;
use Zend\Session\Container;
use Application\Form\LoginForm;
use Application\Form\SearchForm;

class Module
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * This method is called once the MVC bootstrapping is complete and allows
     * to register event listeners.
     */
    public function onBootstrap(MvcEvent $event)
    {
        // Get event manager.
        $eventManager = $event->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        // Register the event listener method.
        $sharedEventManager->attach(AbstractActionController::class,
            MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);

        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();

        $viewModel = $event->getViewModel();

        $categoryManager = $serviceManager->get('Application\Service\CategoryManager');
        $viewModel->mainCategories = $categoryManager->mainCategories();
        $viewModel->arrCateTree = $categoryManager->arrCateTree();

        $sessionContainer = new Container('UserLogin');

        if (isset($sessionContainer->id)) {
            $user = [
                'id' => $sessionContainer->id,
                'name' => $sessionContainer->name,
            ];
            $viewModel->user = $user;
        } else {
            $viewModel->user = null;
        }
        $form = new LoginForm();
        $search_form = new SearchForm();
        $viewModel->search_form = $search_form;
        $viewModel->form = $form;

        // The following line instantiates the SessionManager and automatically
        // makes the SessionManager the 'default' one.
        $sessionManager = $serviceManager->get(SessionManager::class);
    }

    /**
     * Event listener method for the 'Dispatch' event. We listen to the Dispatch
     * event to call the access filter. The access filter allows to determine if
     * the current visitor is allowed to see the page or not. If he/she
     * is not authorized and is not allowed to see the page, we redirect the user
     * to the login page.
     */
    public function onDispatch(MvcEvent $event)
    {
        // Get controller and action to which the HTTP request was dispatched.
        $controller = $event->getTarget();
        $controllerName = $event->getRouteMatch()->getParam('controller', null);
        $actionName = $event->getRouteMatch()->getParam('action', null);

        // Convert dash-style action name to camel-case.
        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));

        // Get the instance of AuthManager service.
        $authManager = $event->getApplication()->getServiceManager()->get(AuthManager::class);

        // Execute the access filter on every controller except AuthController
        // (to avoid infinite redirect).
        if ($controllerName != AuthController::class &&
            !$authManager->filterAccess($controllerName, $actionName)
        ) {

            // Remember the URL of the page the user tried to access. We will
            // redirect the user to that URL after successful login.
            $uri = $event->getApplication()->getRequest()->getUri();
            // Make the URL relative (remove scheme, user info, host name and port)
            // to avoid redirecting to other domain by a malicious user.
            $uri->setScheme(null)
                ->setHost(null)
                ->setPort(null)
                ->setUserInfo(null);
            $redirectUrl = $uri->toString();

            // Redirect the user to the "Login" page.
            return $controller->redirect()->toRoute('login', [],
                ['query' => ['redirectUrl' => $redirectUrl]]);
        }

        $sessionContainer = new Container('UserLogin');

        // if (isset($sessionContainer->id)) {
        //     if ($sessionContainer->id != 1
        //         && ($controllerName == CategoryController::class
        //             || $controllerName == IndexController::class
        //             || $controllerName == OrderController::class
        //             || $controllerName == ProductController::class
        //             || $controllerName == StoreController::class
        //             || $controllerName == UserController::class)
        //     ) {
        //         return $controller->redirect()->toRoute('home');
        //     }
        // }
    }
}
