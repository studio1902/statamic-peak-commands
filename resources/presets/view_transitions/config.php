<?php

return [
    'handle' => 'view_transitions',
    'name' => 'View Transitions',
    'description' => 'Enable view transitions with speculation rules.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'resources/css/view-transitions.css',
            'output' => 'resources/css/view-transitions.css',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/snippets/_speculation_rules.antlers.html',
            'output' => 'resources/views/snippets/_speculation_rules.antlers.html',
        ],
        [
            'type' => 'notify',
            'content' => "Add `\n@import \"./view-transitions.css\";` to the top of your `resources/css/site.css` file.",
        ],
        [
            'type' => 'notify',
            'content' => 'Add `{{ partial:snippets/speculation_rules }}` the head of your layout file in: `resources/views/layout.antlers.html`.',
        ],
    ],
];
