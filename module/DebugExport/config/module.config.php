<?php
namespace DebugExport;

return [
    // Overwrite used controller of export route
    'router' => [
        'routes' => [
            'export' => [
                'options' => [
                    'defaults' => [
                        'controller' => Controller\DebugExportController::class,
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'template_map' => [
            'export' => __DIR__ . '/../../Export/view/export/export/index.phtml',
            'debug'  => __DIR__ . '/../view/debugexport/index.phtml',
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
