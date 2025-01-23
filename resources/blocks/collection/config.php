<?php

return [
    'handle' => 'collection',
    'name' => 'Collection',
    'description' => 'Show collection entries.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => '_collection.antlers.html',
            'output' => 'resources/views/page_builder/_collection.antlers.html'
        ],
        [
            'type' => 'copy',
            'input' => 'collection.yaml',
            'output' => 'resources/fieldsets/collection.yaml'
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Collection',
                'instructions' => 'Show collection entries.',
                'icon' => 'file-content-list',
                'handle' => 'collection',
            ]
        ],
    ]
];
