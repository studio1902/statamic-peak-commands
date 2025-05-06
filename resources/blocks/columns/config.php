<?php

return [
    'handle' => 'columns',
    'name' => 'Columns',
    'description' => 'Text columns with optional images and buttons.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => '_columns.antlers.html',
            'output' => 'resources/views/page_builder/_columns.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'columns.yaml',
            'output' => 'resources/fieldsets/columns.yaml',
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Columns',
                'instructions' => 'Text columns with optional images and buttons.',
                'icon' => 'layout-two-columns',
                'handle' => 'columns',
            ],
        ],
    ],
];
