<?php

return [
    'handle' => 'view_transitions',
    'name' => 'View Transitions',
    'description' => 'Enable view transitions with speculation rules',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'resources/css/view-transitions.css',
            'output' => 'resources/css/view-transitions.css',
        ],
        [
            'type' => 'notify',
            'content' => 'Add `{{ partial:snippets/speculation_rules }}` the head of your layout file in: `resources/views/layout.antlers.html`.',
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `resources/css/site.css` file:\n@import \"./view-transitions.css\";",
        ],
    ],
];
