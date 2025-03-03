<?php

return [
    'handle' => 'modal',
    'name' => 'Modal',
    'description' => 'A re-usable modal.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'resources/views/components/_modal.antlers.html',
            'output' => 'resources/views/components/_modal.antlers.html'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/components/_invoke_modal.antlers.html',
            'output' => 'resources/views/components/_invoke_modal.antlers.html'
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/invoke_modal.yaml',
            'output' => 'resources/fieldsets/invoke_modal.yaml'
        ],
        [
            'type' => 'update_article_sets',
            'set' => [
                'name' => 'Invoke modal',
                'icon' => 'alert-warning-exclamation-mark',
                'instructions' => 'Invokes a modal.',
                'handle' => 'invoke_modal',
            ]
        ],
        [
            'type' => 'notify',
            'content' => "Make sure to `{{ yield:modal }}` in your layout file before closing the `<body>`."
        ]
    ]
];
