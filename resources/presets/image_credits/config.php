<?php

return [
    'handle' => 'image_credits',
    'name' => 'Image credits',
    'description' => 'List images and their credits.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'resources/views/page_builder/_image_credits.antlers.html',
            'output' => 'resources/views/page_builder/_image_credits.antlers.html'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/image_credits.yaml',
            'output' => 'resources/fieldsets/image_credits.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/assets/images.yaml',
            'output' => 'resources/blueprints/assets/images.yaml'
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Image credits',
                'instructions' => 'List images with their credits.',
                'icon' => 'content-book-open',
                'handle' => 'image_credits',
            ]
        ],
    ]
];
