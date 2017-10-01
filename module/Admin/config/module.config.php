<?php

namespace Admin;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
return [
    // This lines opens the configuration for the RouteManager
    'router' => [
        // Open configuration for all possible routes
        'routes' => [
            // Define a new route called "blog"
            'admin' => [
                // Define a "literal" route type:
                'type' => Segment::class,
                // Configure the route itself
                'options' => [
                    // Listen to "/admin" as uri:
                    'route'    => '/admin[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*'
                    ],
                    // Define default controller and action to be called when
                    // this route is matched
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'users' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin/users[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller'    => Controller\UserController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'products' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin/products[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller'    => Controller\ProductController::class,
                        'action'        => 'list',
                    ],
                ],
            ],
            'categories' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin/categories[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller'    => Controller\CategoryController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'stores' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin/stores[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller'    => Controller\StoreController::class,
                        'action'        => 'list',
                    ],
                ],
            ],
            'orders' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin/orders[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller'    => Controller\OrderController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'sale_programs' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/admin/sale_programs[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\SaleProgramController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => 
                Controller\Factory\IndexControllerFactory::class,
            Controller\UserController::class => 
                Controller\Factory\UserControllerFactory::class,
            Controller\CategoryController::class => 
                Controller\Factory\CategoryControllerFactory::class,
            Controller\ProductController::class => 
                Controller\Factory\ProductControllerFactory::class,
            Controller\StoreController::class => 
                Controller\Factory\StoreControllerFactory::class,
            Controller\SaleProgramController::class => 
                Controller\Factory\SaleProgramControllerFactory::class,
            Controller\OrderController::class => 
                Controller\Factory\OrderControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\ProductManager::class => Service\Factory\ProductManagerFactory::class,
            Service\CategoryManager::class => Service\Factory\CategoryManagerFactory::class,
            Service\OrderManager::class => Service\Factory\OrderManagerFactory::class,
            Service\StoreManager::class => Service\Factory\StoreManagerFactory::class,
            Service\ImageManager::class => Service\Factory\ImageManagerFactory::class,
            Service\SaleProgramManager::class => Service\Factory\SaleProgramManagerFactory::class,
            Service\StatisticManager::class => Service\Factory\StatisticManagerFactory::class,
        ],
        'abstract_factories' => array(
            'Zend\Form\FormAbstractServiceFactory',
        ),
    ],
    'validators' => [
        'factories' => [
            Validator\ProductExitsValidator::class => InvokableFactory::class,
            Validator\StoreExitsValidator::class => InvokableFactory::class,

        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/admin/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'ToSlug' => 'Admin\Helper\ToSlug',
        ],
    ],
];
