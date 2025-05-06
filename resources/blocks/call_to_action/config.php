<?php

return [
    'handle' => 'call_to_action',
    'name' => 'Call to action',
    'description' => 'Show a call to action.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => '_call_to_action.antlers.html',
            'output' => 'resources/views/page_builder/_call_to_action.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'call_to_action.yaml',
            'output' => 'resources/fieldsets/call_to_action.yaml',
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Call to action',
                'instructions' => 'Show a call to action.',
                'icon' => 'alert-alarm-bell',
                'handle' => 'call_to_action',
            ],
        ],
    ],
];
