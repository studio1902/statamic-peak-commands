<?php

return [
    'handle' => 'images_grid',
    'name' => 'Images grid',
    'description' => 'A multi row image grid.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => '_images_grid.antlers.html',
            'output' => 'resources/views/page_builder/_images_grid.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'images_grid.yaml',
            'output' => 'resources/fieldsets/images_grid.yaml',
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Images grid',
                'instructions' => 'A multi row image grid.',
                'icon' => 'layout-grid-dots',
                'handle' => 'images_grid',
            ],
        ],
    ],
];
