<?php

return [
    'handle' => 'full_width_image',
    'name' => 'Full width image',
    'description' => 'A full width image with optional text and button(s).',
    'operations' => [
        [
            'type' => 'copy',
            'input' => '_full_width_image.antlers.html',
            'output' => 'resources/views/page_builder/_full_width_image.antlers.html'
        ],
        [
            'type' => 'copy',
            'input' => 'full_width_image.yaml',
            'output' => 'resources/fieldsets/full_width_image.yaml'
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Full width image',
                'instructions' => 'A full width image with optional text and button(s).',
                'icon' => 'media-image-picture-orientation',
                'handle' => 'full_width_image',
            ]
        ],
    ]
];
