<?php
namespace Application;

use \Zend\Router\Http\Literal;
use \Zend\Router\Http\Segment;
use \Zend\I18n\Translator\TranslatorServiceFactory;
use \Zend\Navigation\Service\DefaultNavigationFactory;
use \Zend\Cache\Service\StorageCacheAbstractServiceFactory;
use \Zend\Log\LoggerAbstractServiceFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

//             'application' => [
//                 'type'    => Segment::class,
//                 'options' => [
//                     'route'    => '/application[/:action]',
//                     'defaults' => [
//                         'controller' => Controller\IndexController::class,
//                         'action'     => 'index',
//                     ],
//                 ],
//             ],

            'index' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/index/[:action][/:baseId]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'baseId' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

            'admin' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/admin/[:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

            'ajax' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/ajax/[:action][/:baseId]',
                    'defaults' => [
                        'controller' => Controller\AjaxController::class,
                        'action'     => 'index',
                    ],
                ],
            ],

            'locale' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/locale/[:action][/:id]',
                    'defaults' => [
                        'controller' => Controller\LocaleController::class,
                        'action'     => 'index',
                    ],
                    'constraints' => [
                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'         => '[0-9]+',
                    ],
                ],
            ],

//             // The following is a route to simplify getting started creating
//             // new controllers and actions without needing to create a new
//             // module. Simply drop new controllers in, and you can access them
//             // using the path /application/:controller/:action
//             'application' => [
//                 'type'    => 'Segment',
//                 'options' => [
//                     'route'    => '/application[/:action][/:id]',
//                     'defaults' => [
//                         '__NAMESPACE__' => 'Application\Controller',
//                         'controller'    => 'Index',
//                         'action'        => 'index',
//                     ],
//                 ],
//                 'may_terminate' => true,
//                 'child_routes' => [
//                     'default' => [
//                         'type'    => 'Segment',
//                         'options' => [
//                             'route'    => '/[:controller[/:action]]',
//                             'constraints' => [
//                                 'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
//                                 'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
//                             ],
//                             'defaults' => [
//                             ],
//                         ],
//                     ],
//                 ],
//             ],
        ],
    ],

    // Navigation used for navbar and breadcrumb
    'navigation' => [
        'default' => [
            'home' => [
                'label' => 'Home',
                'route' => 'home',
                'pages' => [
                    [
                        'label'      => 'Edit translation',
                        'controller' => Controller\IndexController::class,
                        'action'     => 'edit',
                        'visible'    => false, // Hidden from menu but will be shown in breadcrumb
                    ],
                ],
            ],
            'admin' => [
                'label' => 'Admin',
                'route' => 'admin',
                'pages' => [
                    'locale' => [
                        'label' => 'Locale configuration',
                        'route' => 'locale',
                        'pages' => [
                            [
                                'label'      => 'Add locale',
                                'controller' => Controller\LocaleController::class,
                                'action'     => 'add',
                                'visible'    => false,
                            ],
                                        [
                                'label'      => 'Edit locale',
                                'controller' => Controller\LocaleController::class,
                                'action'     => 'edit',
                                'visible'    => false,
                            ],
                            [
                                'label'      => 'Delete locale',
                                'controller' => Controller\LocaleController::class,
                                'action'    => 'delete',
                                'visible'    => false,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'abstract_factories' => [
            StorageCacheAbstractServiceFactory::class,
            LoggerAbstractServiceFactory::class,
        ],
//         'aliases' => [
//             'translator' => 'MvcTranslator',
//         ],
        'factories' => [
            'translator' => TranslatorServiceFactory::class,
            'navigation' => DefaultNavigationFactory::class,
        ]
    ],

    'translator' => [
        'locale' => 'en_US',
        'translation_file_patterns' => [
            // poorly we can not use CSV translations because it's not supported
            [
                'type'     => 'phpArray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.php',
            ],
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
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
