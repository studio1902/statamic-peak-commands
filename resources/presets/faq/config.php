<?php

return [
    'handle' => 'faq',
    'name' => 'FAQ',
    'description' => 'A FAQ collection.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'faq_blueprint.yaml.stub',
            'output' => 'resources/blueprints/collections/faq/faq.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'faq_collection.yaml.stub',
            'output' => 'content/collections/faq.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'faq_fieldset.yaml.stub',
            'output' => 'resources/fieldsets/faq.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'faq.antlers.html.stub',
            'output' => 'resources/views/page_builder/_faq.antlers.html'
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'FAQ',
                'instructions' => 'List frequently asked questions in an accordion.',
                'icon' => 'alert-help-question',
                'handle' => 'faq',
            ]
        ],
        [
            'type' => 'update_role',
            'role' => 'editor',
            'permissions' => ['view faq entries', 'edit faq entries', 'create faq entries', 'delete faq entries', 'publish faq entries', 'reorder faq entries', 'edit other authors faq entries', 'publish other authors faq entries', 'delete other authors faq entries']
        ],
        [
            'type' => 'notify',
            'content' => "\nAdd this to your `config/statamic/cp.php` widgets array:\n\n[\n\t'type' => 'collection',\n\t'collection' => 'faq',\n\t'width' => 50\n],\n"
        ]
    ],
];
