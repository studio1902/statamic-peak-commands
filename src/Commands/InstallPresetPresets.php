<?php

namespace Studio1902\Peak\Commands;

trait InstallPresetPresets {

    public function getPresets() {
        $this->presets = collect([
            [
                'handle' => 'breadcrumbs',
                'name' => 'Breadcrumbs',
                'description' => 'A breadcrumbs partial using schema markup.',
                'operations' => [
                    [
                        'type' => 'copy',
                        'input' => 'breadcrumbs.antlers.html.stub',
                        'output' => 'resources/views/navigation/_breadcrumbs.antlers.html'
                    ]
                ]
            ],
            [
                'handle' => 'clients',
                'name' => 'Clients',
                'description' => 'A routeless renamable client/partner collection with a logo cloud page builder block.',
                'operations' => [
                    [
                        'type' => 'rename'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'clients_blueprint.yaml.stub',
                        'output' => 'resources/blueprints/collections/{{ handle }}/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'clients_collection.yaml.stub',
                        'output' => 'content/collections/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'clients.antlers.html.stub',
                        'output' => 'resources/views/page_builder/_{{ handle }}.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'clients_fieldset.yaml.stub',
                        'output' => 'resources/fieldsets/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'update_page_builder',
                        'block' => [
                            'name' => '{{ name }}',
                            'instructions' => 'A {{ name }} logo cloud.',
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
            ],
            [
                'handle' => 'events',
                'name' => 'Events',
                'description' => 'A dated renamable events collection with index and show templates (including JSON-ld) and a page builder set.',
                'operations' => [
                    [
                        'type' => 'rename'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'events_blueprint.yaml.stub',
                        'output' => 'resources/blueprints/collections/{{ handle }}/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'events_collection.yaml.stub',
                        'output' => 'content/collections/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'events_fieldset.yaml.stub',
                        'output' => 'resources/fieldsets/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'events_item.antlers.html.stub',
                        'output' => 'resources/views/components/_{{ handle }}_item.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'events.antlers.html.stub',
                        'output' => 'resources/views/page_builder/_{{ handle }}.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'events.md.stub',
                        'output' => 'content/collections/pages/{{ multisite_handle }}/{{ handle }}.md'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'index_content.antlers.html.stub',
                        'output' => 'resources/views/page_builder/_index_content.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'index_content.yaml.stub',
                        'output' => 'resources/fieldsets/index_content.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'index.antlers.html.stub',
                        'output' => 'resources/views/{{ handle }}/index.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'show.antlers.html.stub',
                        'output' => 'resources/views/{{ handle }}/show.antlers.html'
                    ],
                    [
                        'type' => 'update_page_builder',
                        'block' => [
                            'name' => 'Index content',
                            'instructions' => 'Render the currently mounted entries if available.',
                            'handle' => 'index_content',
                        ]
                    ],
                    [
                        'type' => 'update_page_builder',
                        'block' => [
                            'name' => '{{ name }}',
                            'instructions' => 'List upcoming {{ name }}.',
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
                        'content' => "Add this to your `lang/locale/strings.php` file:\n\n// {{ name }}\n'{{ handle }}_all' => 'All {{ name }}',\n'{{ handle }}_date' => 'Date',\n'{{ handle }}_date_start' => 'Start date',\n'{{ handle }}_date_end' => 'End date',\n'{{ handle }}_more' => 'More {{ name }}',\n'{{ handle }}_when' => 'When',\n'{{ handle }}_where' => 'Where',\n'{{ handle }}_organizer' => 'Organizer',\n'{{ handle }}_tickets' => 'Tickets',"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `config/statamic/cp.php` widgets array:\n\n[\n\t'type' => 'collection',\n\t'collection' => '{{ handle }}',\n\t'width' => 50\n],"
                    ]
                ]
            ],
            [
                'handle' => 'faq',
                'name' => 'FAQ',
                'description' => 'A FAQ collection with a page builder set (including JSON-ld).',
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
                        'type' => 'copy',
                        'input' => 'question.antlers.html.stub',
                        'output' => 'resources/views/components/_question.antlers.html'
                    ],
                    [
                        'type' => 'update_page_builder',
                        'block' => [
                            'name' => 'FAQ',
                            'instructions' => 'List frequently asked questions in an accordion.',
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
            ],
            [
                'handle' => 'language_picker',
                'name' => 'Language picker',
                'description' => 'A language picker for when you use multisite.',
                'operations' => [
                    [
                        'type' => 'copy',
                        'input' => 'language_picker.antlers.html.stub',
                        'output' => 'resources/views/navigation/_language_picker.antlers.html'
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `lang/locale/strings.php` file:\n\n// Language picker\n'language_open' => 'Open language picker. Current language is :current_language',\n'language_close' => 'Close language picker',"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add `{{ partial:navigation/language_picker }}` as the last list item in the main ul in `resources/views/navigation/_main.antlers.html`."
                    ]
                ]
            ],
            [
                'handle' => 'modal',
                'name' => 'Modal',
                'description' => 'A modal that only has to be rendered once but can be used multiple times with different content.',
                'operations' => [
                    [
                        'type' => 'copy',
                        'input' => 'modal.antlers.html.stub',
                        'output' => 'resources/views/components/_modal.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'invoke_modal.antlers.html.stub',
                        'output' => 'resources/views/components/_invoke_modal.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'invoke_modal.yaml.stub',
                        'output' => 'resources/fieldsets/invoke_modal.yaml'
                    ],
                    [
                        'type' => 'update_article_sets',
                        'block' => [
                            'name' => 'Invoke modal',
                            'handle' => 'invoke_modal',
                        ]
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Make sure to `{{ yield:modal }}` in your layout file before closing the `<body>`."
                    ]
                ]
            ],
            [
                'handle' => 'news',
                'name' => 'News',
                'description' => 'A dated renamable news/blog collection with index and show templates (including JSON-ld) and a page builder set.',
                'operations' => [
                    [
                        'type' => 'rename'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'index_content.antlers.html.stub',
                        'output' => 'resources/views/page_builder/_index_content.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'index_content.yaml.stub',
                        'output' => 'resources/fieldsets/index_content.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'index.antlers.html.stub',
                        'output' => 'resources/views/{{ handle }}/index.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'news_blueprint.yaml.stub',
                        'output' => 'resources/blueprints/collections/{{ handle }}/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'news_collection.yaml.stub',
                        'output' => 'content/collections/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'news_fieldset.yaml.stub',
                        'output' => 'resources/fieldsets/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'news_item.antlers.html.stub',
                        'output' => 'resources/views/components/_{{ handle }}_item.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'news.antlers.html.stub',
                        'output' => 'resources/views/page_builder/_{{ handle }}.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'news.md.stub',
                        'output' => 'content/collections/pages/{{ multisite_handle }}/{{ handle }}.md'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'show.antlers.html.stub',
                        'output' => 'resources/views/{{ handle }}/show.antlers.html'
                    ],
                    [
                        'type' => 'update_page_builder',
                        'block' => [
                            'name' => 'Index content',
                            'instructions' => 'Render the currently mounted entries if available.',
                            'handle' => 'index_content',
                        ]
                    ],
                    [
                        'type' => 'update_page_builder',
                        'block' => [
                            'name' => '{{ name }}',
                            'instructions' => 'List the most recent {{ name }}.',
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
                        'content' => "Add this to your `lang/locale/strings.php` file:\n\n// {{ name }}\n'{{ handle }}_all' => 'All articles',\n'{{ handle }}_more' => 'More articles',"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `config/statamic/cp.php` widgets array:\n\n[\n\t'type' => 'collection',\n\t'collection' => '{{ handle }}',\n\t'width' => 50\n],"
                    ]
                ]
            ],
            [
                'handle' => 'search',
                'name' => 'Search',
                'description' => 'A search form component and a styled search results template.',
                'operations' => [
                    [
                        'type' => 'copy',
                        'input' => 'search.antlers.html.stub',
                        'output' => 'resources/views/search.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'search_form.antlers.html.stub',
                        'output' => 'resources/views/components/_search_form.antlers.html'
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `lang/locale/strings.php` file:\n\n// Search\n'search' => 'Search',\n'search_no_results' => 'No results found',\n'search_results_for' => 'Results for',"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "To enable this do the following:\n1. Add `{{ partial:components/search_form }}` as the last list item in the main ul in `resources/views/navigation/_main.antlers.html`.\n2. Uncomment the search results route in routes/web.php.\n3. Add fields you want indexed to the index in config/statamic/search.php. The page_builder field is added by default.\n4. Update the search index by running php please search:update --all.\n5. Make sure you add the update command to your deployment script."
                    ]
                ]
            ],
            [
                'handle' => 'team_members',
                'name' => 'Team members',
                'description' => 'A routeless renamable team member collection with a team member page builder block.',
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
            ],
            [
                'handle' => 'theme_toggle',
                'name' => 'Theme toggle',
                'description' => 'A theme toggle typically used for a Tailwind class based dark mode.',
                'operations' => [
                    [
                        'type' => 'copy',
                        'input' => 'theme_toggle.antlers.html.stub',
                        'output' => 'resources/views/components/_theme_toggle.antlers.html'
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `lang/locale/strings.php` file:\n\n// Theme toggle\n'theme_toggle_dark' => 'Use dark theme.',\n'theme_toggle_light' => 'Use light theme.',\n'theme_toggle_system' => 'Use system preference.',\n'theme_toggle_dark_short' => 'Dark',\n'theme_toggle_light_short' => 'Light',\n'theme_toggle_system_short' => 'System',\n'theme_toggle_toggle_open' => 'Open dark mode picker.',\n'theme_toggle_toggle_close' => 'Close dark mode picker.',"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "To enable this do the following:\n1. Uncomment `darkMode: 'class'` in `tailwind.config.js`.\n2. Add `{{ partial:components/theme_toggle }}` as the last list item in the main ul in `resources/views/navigation/_main_desktop.antlers.html`. The `section:theme_toggle` is automatically yielded in `resources/views/snippets/_browser_appearance.antlers.html`."
                    ]
                ]
            ],
        ]);
    }
}
