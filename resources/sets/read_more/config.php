<?php

return [
    'handle' => 'read_more',
    'name' => 'Read more',
    'description' => 'Link to a related article.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => '_read_more.antlers.html',
            'output' => 'resources/vies/page_builder/_read_more.antlers.html'
        ],
        [
            'type' => 'update_article_sets',
            'block' => [
                'name' => 'Read more',
                'icon' => 'content-book-open',
                'description' => 'Link to a related article.',
                'handle' => 'read_more',
            ]
        ],
    ]
];
