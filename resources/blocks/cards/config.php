<?php

return [
    'handle' => 'cards',
    'name' => 'Cards',
    'description' => 'Cards that link using the button fieldset.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => '_cards.antlers.html',
            'output' => 'resources/views/page_builder/_cards.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'cards.yaml',
            'output' => 'resources/fieldsets/cards.yaml',
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Cards',
                'instructions' => 'Cards that link using the button fieldset.',
                'icon' => 'link',
                'handle' => 'cards',
            ],
        ],
    ],
];
