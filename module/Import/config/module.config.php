<?php
namespace Import;

use \Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'import' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/import/[:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\ImportController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
             'import' => __DIR__ . '/../view',
         ],
    ],

    'navigation' => [
        'default' => [
            'admin' => [
                'label' => 'Admin',
                'route' => 'admin',
                'pages' => [
                    'admin/import' => [
                        'label' => 'Import',
                        'route' => 'import',
                    ],
                ],
            ],
        ],
    ],

    'translator' => [
        'locale' => 'en_US',
        'translation_file_patterns' => [
            [
                'type'     => 'phpArray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.php',
            ],
        ],
    ],
];
