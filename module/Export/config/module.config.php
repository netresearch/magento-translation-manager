<?php
namespace Export;

use \Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'export' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/export/[:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\ExportController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
             __DIR__ . '/../view',
         ],
        'template_map' => [
            'export'   => __DIR__ . '/../../Export/view/export/export/index.phtml',
            'download' => __DIR__ . '/../../Export/view/export/export/download.phtml',
         ],
    ],

    'navigation' => [
        'default' => [
            'admin' => [
                'route' => 'admin',
                'pages' => [
                    'admin/export' => [
                        'label' => 'Export',
                        'route' => 'export',
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
