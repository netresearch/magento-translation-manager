<?php
namespace Import;

use \Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'import' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/import/[:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
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
                    'admin/locale' => [
                        'label' => 'Import',
                        'route' => 'import',
                    ],
                ],
            ],
        ],
    ],
];
