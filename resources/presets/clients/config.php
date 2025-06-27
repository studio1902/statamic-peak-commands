<?php

return [
    'handle' => 'clients',
    'name' => 'Clients',
    'singular_name' => 'Client',
    'description' => 'A renamable client/partner collection.',
    'operations' => [
        [
            'type' => 'rename',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/collections/clients/clients.yaml',
            'output' => 'resources/blueprints/collections/{{ handle }}/{{ handle }}.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'content/collections/clients.yaml',
            'output' => 'content/collections/{{ handle }}.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/page_builder/_clients.antlers.html',
            'output' => 'resources/views/page_builder/_{{ handle }}.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/clients.yaml',
            'output' => 'resources/fieldsets/{{ handle }}.yaml',
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => '{{ name }}',
                'instructions' => 'A {{ name }} logo cloud.',
                'icon' => 'favorite-award',
                'handle' => '{{ handle }}',
            ],
        ],
        [
            'type' => 'update_role',
            'role' => 'editor',
            'permissions' => ['view {{ handle }} entries', 'edit {{ handle }} entries', 'create {{ handle }} entries', 'delete {{ handle }} entries', 'publish {{ handle }} entries', 'reorder {{ handle }} entries', 'edit other authors {{ handle }} entries', 'publish other authors {{ handle }} entries', 'delete other authors {{ handle }} entries'],
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `lang/locale/strings.php` file:\n(and possibly any other locales you support)\n\n'clients_sr' => 'A list of the following clients',",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `config/statamic/cp.php` widgets array:\n\n[\n\t'type' => 'collection',\n\t'collection' => '{{ handle }}',\n\t'width' => 50\n],",
        ],
    ],
];
