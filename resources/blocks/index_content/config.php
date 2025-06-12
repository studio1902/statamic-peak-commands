<?php

return [
    'handle' => 'index_content',
    'name' => 'Index content',
    'description' => 'Render the currently mounted entries if available.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => '_index_content.antlers.html',
            'output' => 'resources/views/page_builder/_index_content.antlers.html',
            'skippable' => true,
        ],
        [
            'type' => 'copy',
            'input' => 'index_content.yaml',
            'output' => 'resources/fieldsets/index_content.yaml',
            'skippable' => true,
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Index content',
                'instructions' => 'Render the currently mounted entries if available.',
                'icon' => 'file-content-list',
                'handle' => 'index_content',
            ],
        ],
    ],
];
