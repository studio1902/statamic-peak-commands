<?php

return [
    'handle' => 'default_blocks',
    'name' => 'Default blocks',
    'description' => 'Reusable page builder blocks that can be placed globally.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'app/FieldTypes/DefaultBlockSelect.php',
            'output' => 'app/FieldTypes/DefaultBlockSelect.php',
        ],
        [
            'type' => 'copy',
            'input' => 'content/globals/default_blocks.yaml',
            'output' => 'content/globals/default_blocks.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/globals/default_blocks.yaml',
            'output' => 'resources/blueprints/globals/default_blocks.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/default_blocks.yaml',
            'output' => 'resources/fieldsets/default_blocks.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/layout/_default_blocks.antlers.html',
            'output' => 'resources/views/layout/_default_blocks.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/page_builder/_default_blocks.antlers.html',
            'output' => 'resources/views/page_builder/_default_blocks.antlers.html',
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Default blocks',
                'instructions' => 'Add a reusable block from the Default blocks global.',
                'icon' => 'layers-filled',
                'handle' => 'default_blocks',
            ],
        ],
        [
            'type' => 'update_role',
            'role' => 'editor',
            'permissions' => ['view default_blocks globals', 'edit default_blocks globals'],
        ],
        [
            'type' => 'notify',
            'content' => "Add the following partials to your `resources/views/default.antlers.html` template:\n\n{{# Default blocks (before content, inside main) #}}\n{{ partial:snippets/default_blocks position=\"before_content\" }}\n\n{{# Default blocks (after content, inside main) #}}\n{{ partial:layout/default_blocks position=\"after_content\" }}\n\n{{# Default blocks (before footer, outside main) #}}\n{{ partial:layout/default_blocks position=\"before_footer\" }}",
        ],
    ],
];