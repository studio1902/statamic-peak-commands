<?php

return [
    'handle' => 'read_more',
    'name' => 'Read more',
    'description' => 'Link to a related article.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => '_read_more.antlers.html',
            'output' => 'resources/views/components/_read_more.antlers.html'
        ],
        [
            'type' => 'copy',
            'input' => 'read_more.yaml',
            'output' => 'resources/fieldsets/read_more.yaml'
        ],
        [
            'type' => 'update_article_sets',
            'set' => [
                'name' => 'Read more',
                'icon' => 'content-book-open',
                'instructions' => 'Link to a related article.',
                'handle' => 'read_more',
            ]
        ],
    ]
];
