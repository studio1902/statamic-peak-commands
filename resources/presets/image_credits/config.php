<?php

return [
    'handle' => 'image_credits',
    'name' => 'Image credits',
    'description' => 'List images and their credits.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'image_credits.antlers.html.stub',
            'output' => 'resources/views/page_builder/_image_credits.antlers.html'
        ],
        [
            'type' => 'copy',
            'input' => 'image_credits_fieldset.yaml.stub',
            'output' => 'resources/fieldsets/image_credits.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'images_blueprint.yaml.stub',
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
