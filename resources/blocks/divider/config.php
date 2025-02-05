<?php

return [
    'handle' => 'divider',
    'name' => 'Divider',
    'description' => 'A visual divider between blocks.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => '_divider.antlers.html',
            'output' => 'resources/views/page_builder/_divider.antlers.html'
        ],
        [
            'type' => 'copy',
            'input' => 'divider.yaml',
            'output' => 'resources/fieldsets/divider.yaml'
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Divider',
                'instructions' => 'A visual divider between blocks.',
                'icon' => 'layout-table-row-insert',
                'handle' => 'divider',
            ]
        ],
    ]
];
