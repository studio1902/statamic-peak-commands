<?php

return [
    'handle' => 'modal',
    'name' => 'Modal',
    'description' => 'A re-usable modal.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'modal.antlers.html.stub',
            'output' => 'resources/views/components/_modal.antlers.html'
        ],
        [
            'type' => 'copy',
            'input' => 'invoke_modal.antlers.html.stub',
            'output' => 'resources/views/components/_invoke_modal.antlers.html'
        ],
        [
            'type' => 'copy',
            'input' => 'invoke_modal.yaml.stub',
            'output' => 'resources/fieldsets/invoke_modal.yaml'
        ],
        [
            'type' => 'update_article_sets',
            'block' => [
                'name' => 'Invoke modal',
                'icon' => 'alert-warning-exclamation-mark',
                'description' => 'Invokes a modal.',
                'handle' => 'invoke_modal',
            ]
        ],
        [
            'type' => 'notify',
            'content' => "Make sure to `{{ yield:modal }}` in your layout file before closing the `<body>`."
        ]
    ]
];
