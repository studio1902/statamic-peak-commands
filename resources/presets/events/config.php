<?php

return [
    'handle' => 'events',
    'name' => 'Events',
    'singular_name' => 'Event',
    'description' => 'A dated renamable events collection.',
    'operations' => [
        [
            'type' => 'rename',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/collections/events/events.yaml',
            'output' => 'resources/blueprints/collections/{{ handle }}/{{ handle }}.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'content/collections/events.yaml',
            'output' => 'content/collections/{{ handle }}.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/events.yaml',
            'output' => 'resources/fieldsets/{{ handle }}.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/components/_events_item.antlers.html',
            'output' => 'resources/views/components/_{{ handle }}_item.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/page_builder/_events.antlers.html',
            'output' => 'resources/views/page_builder/_{{ handle }}.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'content/collections/pages/events.md',
            'output' => 'content/collections/pages/{{ multisite_handle }}/{{ handle }}.md',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/page_builder/_index_content.antlers.html',
            'output' => 'resources/views/page_builder/_index_content.antlers.html',
            'skippable' => true,
        ],
        [
            'type' => 'copy',
            'input' => 'resources/fieldsets/index_content.yaml',
            'output' => 'resources/fieldsets/index_content.yaml',
            'skippable' => true,
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/events/index.antlers.html',
            'output' => 'resources/views/{{ handle }}/index.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/events/show.antlers.html',
            'output' => 'resources/views/{{ handle }}/show.antlers.html',
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => 'Index content',
                'instructions' => 'Render the currently mounted entries if available.',
                'icon' => 'file-content-list',
                'handle' => 'index_content',
            ],
        ],
        [
            'type' => 'update_page_builder',
            'block' => [
                'name' => '{{ name }}',
                'instructions' => 'List upcoming {{ name }}.',
                'icon' => 'calendar-date',
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
            'content' => "Add this to your `lang/locale/strings.php` file:\n(and possibly any other locales you support)\n\n// {{ name }}\n'{{ handle }}_all' => 'All {{ name }}',\n'{{ handle }}_date' => 'Date',\n'{{ handle }}_date_start' => 'Start date',\n'{{ handle }}_date_end' => 'End date',\n'{{ handle }}_more' => 'More {{ name }}',\n'{{ handle }}_when' => 'When',\n'{{ handle }}_where' => 'Where',\n'{{ handle }}_organizer' => 'Organizer',\n'{{ handle }}_tickets' => 'Tickets',",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `config/statamic/cp.php` widgets array:\n\n[\n\t'type' => 'collection',\n\t'collection' => '{{ handle }}',\n\t'width' => 50\n],",
        ],
    ],
];
