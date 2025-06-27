<?php

return [
    'handle' => 'vacancies',
    'name' => 'Vacancies',
    'singular_name' => 'Vacancy',
    'description' => 'A dated renamable vacancies collection.',
    'operations' => [
        [
            'type' => 'rename',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/vacancies/index.antlers.html',
            'output' => 'resources/views/{{ handle }}/index.antlers.html',
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
            'input' => 'resources/views/vacancies/show.antlers.html',
            'output' => 'resources/views/{{ handle }}/show.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'content/collections/pages/vacancies.md',
            'output' => 'content/collections/pages/{{ multisite_handle }}/{{ handle }}.md',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/views/components/_vacancies_item.antlers.html',
            'output' => 'resources/views/components/_{{ handle }}_item.antlers.html',
        ],
        [
            'type' => 'copy',
            'input' => 'resources/blueprints/collections/vacancies/vacancies.yaml',
            'output' => 'resources/blueprints/collections/{{ handle }}/{{ handle }}.yaml',
        ],
        [
            'type' => 'copy',
            'input' => 'content/collections/vacancies.yaml',
            'output' => 'content/collections/{{ handle }}.yaml',
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
            'type' => 'update_role',
            'role' => 'editor',
            'permissions' => ['view {{ handle }} entries', 'edit {{ handle }} entries', 'create {{ handle }} entries', 'delete {{ handle }} entries', 'publish {{ handle }} entries', 'reorder {{ handle }} entries', 'edit other authors {{ handle }} entries', 'publish other authors {{ handle }} entries', 'delete other authors {{ handle }} entries'],
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `lang/locale/strings.php` file:\n(and possibly any other locales you support)\n\n// {{ name }}\n'{{ handle }}_expires' => 'Expires',\n'{{ handle }}_hours' => 'Hours',\n'{{ handle }}_published' => 'Published',\n'{{ handle }}_region' => 'Region',\n'{{ handle }}_employment_type' => 'Employment type',\n'{{ handle }}_part_time' => 'Part time',\n'{{ handle }}_full_time' => 'Full time',\n'{{ handle }}_salary_min' => 'Minimum salary',\n'{{ handle }}_salary_max' => 'Maximum salary',",
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `config/statamic/cp.php` widgets array:\n\n[\n\t'type' => 'collection',\n\t'collection' => '{{ handle }}',\n\t'width' => 50\n],",
        ],
    ],
];
