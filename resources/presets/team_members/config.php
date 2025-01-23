<?php

return [
    'handle' => 'team_members',
    'name' => 'Team members',
    'singular_name' => 'Member',
    'description' => 'A renamable team member collection.',
    'operations' => [
        [
            'type' => 'rename'
        ],
        [
            'type' => 'copy',
            'input' => 'team_members_blueprint.yaml.stub',
            'output' => 'resources/blueprints/collections/{{ handle }}/{{ handle }}.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'team_members_collection.yaml.stub',
            'output' => 'content/collections/{{ handle }}.yaml'
        ],
        [
            'type' => 'copy',
            'input' => 'team_members.antlers.html.stub',
            'output' => 'resources/views/page_builder/_{{ handle }}.antlers.html'
        ],
        [
            'type' => 'copy',
            'input' => 'team_members_fieldset.yaml.stub',
            'output' => 'resources/fieldsets/{{ handle }}.yaml'
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => '{{ name }}',
                'instructions' => 'List {{ name }}.',
                'icon' => 'user-multiple',
                'handle' => '{{ handle }}',
            ]
        ],
        [
            'type' => 'update_role',
            'role' => 'editor',
            'permissions' => ['view {{ handle }} entries', 'edit {{ handle }} entries', 'create {{ handle }} entries', 'delete {{ handle }} entries', 'publish {{ handle }} entries', 'reorder {{ handle }} entries', 'edit other authors {{ handle }} entries', 'publish other authors {{ handle }} entries', 'delete other authors {{ handle }} entries']
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `config/statamic/cp.php` widgets array:\n\n[\n\t'type' => 'collection',\n\t'collection' => '{{ handle }}',\n\t'width' => 50\n],"
        ]
    ]
];
