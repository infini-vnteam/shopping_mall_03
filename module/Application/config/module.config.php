<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\HomeController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'application' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\HomeController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/login',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'login',
                    ],
                ],
            ],
            'register' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/register',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'add',
                    ],
                ],
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'logout',
                    ],
                ],
            ],
            'reset-password' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/reset-password',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'resetPassword',
                    ],
                ],
            ],
            'set-password' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/set-password',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'setPassword',
                    ],
                ],
            ],
            'user' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'view',
                    ],
                ],
            ],
            'product' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/product[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\ProductController::class,
                        'action' => 'view',
                    ],
                ],
            ],

            'search' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/search',
                    'defaults' => [
                        'controller' => Controller\HomeController::class,
                        'action' => 'search',
                    ],
                ],
            ],
            'getDataSearch' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/getDataSearch',
                    'defaults' => [
                        'controller' => Controller\HomeController::class,
                        'action' => 'getDataSearch',
                    ],
                ],
            ],
            'category' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/category[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\CategoryController::class,
                        'action' => 'view',

                    ],
                ],
            ],
            'cart' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/cart[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\CartController::class,
                        'action' => 'view',
                    ],
                ],
            ],
            'comment' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/comment[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\CommentController::class,
                        'action' => 'add',
                    ],
                ],
            ],
            'order' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/order[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\OrderController::class,
                        'action' => 'view',
                    ]
                ]
            ],
            'getCurrentUser' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/getCurrentUser',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'getCurrentUser',
                    ],
                ],
            ],
            'sale' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/sale[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\SaleProgramController::class,
                        'action' => 'index',
                    ]
                ]
            ],
            
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
            Controller\UserController::class => Controller\Factory\UserControllerFactory::class,
            Controller\HomeController::class => Controller\Factory\HomeControllerFactory::class,
            Controller\ProductController::class => Controller\Factory\ProductControllerFactory::class,
            Controller\CategoryController::class => Controller\Factory\CategoryControllerFactory::class,
            Controller\CartController::class => Controller\Factory\CartControllerFactory::class,
            Controller\CommentController::class => Controller\Factory\CommentControllerFactory::class,
            Controller\OrderController::class => Controller\Factory\OrderControllerFactory::class,
            Controller\SaleProgramController::class => Controller\Factory\SaleProgramControllerFactory::class,
            
        ],
    ],
    'access_filter' => [
        'controllers' => [
            Controller\UserController::class => [
                // Give access to "resetPassword", "message" and "setPassword" actions
                // to anyone.
                ['actions' => ['resetPassword', 'message', 'setPassword', 'add', 'getinfo'], 'allow' => '*'],
                // Give access to "index", "add", "edit", "view", "changePassword" actions to authorized users only.
                ['actions' => ['edit', 'view', 'changePassword'], 'allow' => '@']
            ],
            Controller\HomeController::class => [
                // Give access to "resetPassword", "message" and "setPassword" actions
                // to anyone.
                ['actions' => ['index', 'search', 'loaddistrict', 'loadprovince', 'getDataSearch'], 'allow' => '*'],
                // Give access to "index", "add", "edit", "view", "changePassword" actions to authorized users only.
                 ['actions' => ['loadNotification'], 'allow' => '@']
            ],
            Controller\ProductController::class => [
                // Give access to "resetPassword", "message" and "setPassword" actions
                // to anyone.
                ['actions' => ['view', 'getinfo'], 'allow' => '*'],
                // Give access to "index", "add", "edit", "view", "changePassword" actions to authorized users only.
                // ['actions' => ['changePassword'], 'allow' => '@']
            ],
            Controller\CategoryController::class => [
                // Give access to "resetPassword", "message" and "setPassword" actions
                // to anyone.
                ['actions' => ['view'], 'allow' => '*'],
                // Give access to "index", "add", "edit", "view", "changePassword" actions to authorized users only.
                // ['actions' => ['changePassword'], 'allow' => '@']
            ],
            Controller\CartController::class => [
                // Give access to "view" actions to anyone.
                ['actions' => ['view', 'checkout', 'success'], 'allow' => '*'],
            ],
            Controller\OrderController::class => [
                ['actions' => ['view', 'getOrders'], 'allow' => '@'],
                ['actions' => ['track'], 'allow' => '*'],
            ],
            Controller\SaleProgramController::class => [
                // Give access to "view" actions to anyone.
                ['actions' => ['index', 'view', 'getSales', 'getSale'], 'allow' => '*'],
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            \Zend\Authentication\AuthenticationService::class => Service\Factory\AuthenticationServiceFactory::class,
            Service\AuthAdapter::class => Service\Factory\AuthAdapterFactory::class,
            Service\AuthManager::class => Service\Factory\AuthManagerFactory::class,
            Service\UserManager::class => Service\Factory\UserManagerFactory::class,
            Service\CategoryManager::class => Service\Factory\CategoryManagerFactory::class,
            Service\ProductManager::class => Service\Factory\ProductManagerFactory::class,
            Service\CartManager::class => Service\Factory\CartManagerFactory::class,
            Service\OrderManager::class => Service\Factory\OrderManagerFactory::class,
            Service\MailManager::class => Service\Factory\MailManagerFactory::class,
            Service\SqlManager::class => Service\Factory\SqlManagerFactory::class,
        ],
    ],
    'session_containers' => [
        'UserLogin',
    ],
//    'view_helpers' => [
//        'factories' => [
//            View\Helper\Menu::class => View\Helper\Factory\MenuFactory::class,
//        ],
//        'aliases' => [
//            'mainMenu' => View\Helper\Menu::class,
//        ],
//    ],
    'view_manager' => [
//        'display_not_found_reason' => true,
//        'display_exceptions' => true,
//        'doctype' => 'HTML5',
//        'not_found_template' => 'error/404',
//        'exception_template' => 'error/index',
        'template_map' => [
            'application/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/layout_category' => __DIR__ . '/../view/layout/layout_category.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
];
