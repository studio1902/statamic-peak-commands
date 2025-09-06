<?php

return [
    'handle' => 'block_groups',
    'name' => 'Block Groups',
    'description' => 'Reusable page builder blocks that can be placed globally.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'app/FieldTypes/BlockGroupSelect.php',
            'output' => 'app/FieldTypes/BlockGroupSelect.php',
        ],
        [
            'type' => 'copy',
            'input' => 'content/globals/block_groups.yaml',
            'output' => 'content/globals/block_groups.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/globals/block_groups.yaml',
            'output' => 'resources/blueprints/globals/block_groups.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/block_groups.yaml',
            'output' => 'resources/fieldsets/block_groups.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/layout/_block_groups.antlers.html',
            'output' => 'resources/views/layout/_block_groups.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/page_builder/_block_groups.antlers.html',
            'output' => 'resources/views/page_builder/_block_groups.antlers.html',
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Block Groups',
                'instructions' => 'Render a selected block group.',
                'icon' => 'layers-filled',
                'handle' => 'block_groups',
            ],
        ],
        [
            'type' => 'update_role',
            'role' => 'editor',
            'permissions' => ['view block_groups globals', 'edit block_groups globals'],
        ],
        [
            'type' => 'notify',
            'content' => "Add the following partials to your `resources/views/default.antlers.html` template:\n\n{{# Block Groups (before content, inside main) #}}\n{{ partial:snippets/block_groups position=\"before_content\" }}\n\n{{# Block Groups (after content, inside main) #}}\n{{ partial:layout/block_groups position=\"after_content\" }}\n\n{{# Block Groups (before footer, outside main) #}}\n{{ partial:layout/block_groups position=\"before_footer\" }}",
        ],
    ],
];