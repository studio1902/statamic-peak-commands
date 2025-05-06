<?php

return [
    'handle' => 'text_columns',
    'name' => 'Text columns',
    'description' => 'Text wrapping in two columns.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => '_text_columns.antlers.html',
            'output' => 'resources/views/page_builder/_text_columns.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'text_columns.yaml',
            'output' => 'resources/fieldsets/text_columns.yaml',
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Text columns',
                'instructions' => 'Text wrapping in two columns.',
                'icon' => 'layout-three-columns',
                'handle' => 'text_columns',
            ],
        ],
    ],
];
