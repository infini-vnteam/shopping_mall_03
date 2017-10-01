<?php
namespace Message;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'sendmessage' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/sendmessage',
                    'defaults' => [
                        'controller' => \Message\Controller\MessageController::class,
                        'action'     => 'sendMessage',
                    ],
                ],
            ],
            'getmessages' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/getmessages',
                    'defaults' => [
                        'controller' => \Message\Controller\MessageController::class,
                        'action'     => 'getMessages',
                    ],
                ],
            ],
            'getchatrooms' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/getchatrooms',
                    'defaults' => [
                        'controller' => \Message\Controller\MessageController::class,
                        'action'     => 'getChatrooms',
                    ],
                ],
            ],
            'test' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/test',
                    'defaults' => [
                        'controller' => Controller\MessageController::class,
                        'action'     => 'test',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            \Message\Controller\MessageController::class => \Message\Controller\Factory\MessageControllerFactory::class,
        ],
    ],
    'access_filter' => [

        'controllers' => [
            Controller\MessageController::class => [
                // Allow anyone to visit "index" and "about" actions
                ['actions' => ['sendMessage'], 'allow' => '*'],
            ],
        ]
    ],

    'service_manager' => [
        'factories' => [
            Service\MessageManager::class => Service\Factory\MessageManagerFactory::class,
        ],
    ],
    'view_manager' => [
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
