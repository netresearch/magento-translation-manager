<?php
namespace Locale;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'locale' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/locale/[:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\LocaleController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
             'locale' => __DIR__ . '/../view',
         ],
    ],
];
