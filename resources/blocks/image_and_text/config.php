<?php

return [
    'handle' => 'image_and_text',
    'name' => 'Image and text',
    'description' => 'An image and text side by side.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => '_image_and_text.antlers.html',
            'output' => 'resources/views/page_builder/_image_and_text.antlers.html'
        ],
        [
            'type' => 'copy',
            'input' => 'image_and_text.yaml',
            'output' => 'resources/fieldsets/image_and_text.yaml'
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Image and text',
                'instructions' => 'An image and text side by side.',
                'icon' => 'text-formatting-image-left',
                'handle' => 'image_and_text',
            ]
        ],
    ]
];
