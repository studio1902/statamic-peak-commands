<?php

namespace Studio1902\PeakCommands\Commands;

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
                'handle' => 'business_hours',
                'name' => 'Business hours',
                'description' => 'A business hours global and a list and call component that shows the business hours and if the business is currently open / available.',
                'operations' => [
                    [
                        'type' => 'copy',
                        'input' => 'business_hours_blueprint.yaml.stub',
                        'output' => 'resources/blueprints/globals/business_hours.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'business_hours_global.yaml.stub',
                        'output' => 'content/globals/business_hours.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'business_hours_snippet.antlers.html.stub',
                        'output' => 'resources/views/snippets/_business_hours.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'business_hours.antlers.html.stub',
                        'output' => 'resources/views/components/_business_hours.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'call_now.antlers.html.stub',
                        'output' => 'resources/views/components/_call_now.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'phone.svg.stub',
                        'output' => 'resources/svg/phone.svg'
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `lang/locale/strings.php` file:\n\n// Business hours\n'business_hours_can_reach' => 'Available now',\n'business_hours_cant_reach' => 'Not available now',\n'business_hours_closed' => 'Closed',\n'business_hours_open_now' => 'Open now',\n'business_hours_closed_now' => 'Closed now',\n'business_hours_monday' => 'Monday',\n'business_hours_tuesday' => 'Tuesday',\n'business_hours_wednesday' => 'Wednesday',\n'business_hours_thursday' => 'Thursday',\n'business_hours_friday' => 'Friday',\n'business_hours_saturday' => 'Saturday',\n'business_hours_sunday' => 'Sunday',"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Make sure to set the correct timezone in `config/app.php`."
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Import the following partials where needed: `{{ partial:components/business_hours }}` and `{{ partial:components/call_now }}`."
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
                'description' => 'A dated renamable news/blog collection with index and show templates (including JSON-ld), a page builder set and an RSS feed.',
                'operations' => [
                    [
                        'type' => 'rename'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'feed.antlers.xml.stub',
                        'output' => 'resources/views/feed/feed.antlers.xml'
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
                        'content' => "Add this to your `lang/locale/strings.php` file:\n\n// {{ name }}\n'{{ handle }}_all' => 'All articles',\n'{{ handle }}_more' => 'More articles',\n'{{ handle }}_read_more' => 'Read more',"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `config/statamic/cp.php` widgets array:\n\n[\n\t'type' => 'collection',\n\t'collection' => '{{ handle }}',\n\t'width' => 50\n],"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `routes/web.php` file:\n\n// The route to the RSS feed.\nRoute::statamic('/feed/{{ handle }}', 'feed/feed', [\n\t'layout' => null,\n\t'content_type' => 'application/xml',\n]);"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to the `<head>` in your `views/layout.antlers.html` file:\n\n<link rel=\"alternate\" type=\"application/rss+xml\" title=\"{{ name }} Feed\" href=\"{{ config:app:url }}/feed/{{ handle }}\"/>"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to the `exclude` array in your `config/statamic/static_caching.php` file:\n\n'/feed*',"
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
                        'content' => "To enable this do the following:\n1. Add `{{ partial:components/search_form }}` as the last list item in the main ul in `resources/views/navigation/_main.antlers.html`.\n2. Add the following route to `routes/web.php`:\n\n// The Search route to display search results with `views/search.antlers.html`.\nRoute::statamic('/search', 'search');\n\n3. Add fields you want indexed to the index in config/statamic/search.php. The page_builder field is added by default.\n4. Update the search index by running php please search:update --all.\n5. Make sure you add the update command to your deployment script."
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
                        'content' => "To enable this do the following:\n1. Add `darkMode: 'class'` to `tailwind.config.js`.\n2. Add `{{ partial:components/theme_toggle }}` as the last list item in the main ul in `resources/views/navigation/_main_desktop.antlers.html`. The `section:theme_toggle` is automatically yielded in `resources/views/snippets/_browser_appearance.antlers.html`."
                    ]
                ]
            ],
            [
                'handle' => 'vacancies',
                'name' => 'Vacancies',
                'description' => 'A dated renamable vacancies collection with index and show templates (including JSON-ld).',
                'operations' => [
                    [
                        'type' => 'rename'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'index.antlers.html.stub',
                        'output' => 'resources/views/{{ handle }}/index.antlers.html'
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
                        'input' => 'show.antlers.html.stub',
                        'output' => 'resources/views/{{ handle }}/show.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'vacancies.md.stub',
                        'output' => 'content/collections/pages/{{ multisite_handle }}/{{ handle }}.md'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'vacancies_item.antlers.html.stub',
                        'output' => 'resources/views/components/_{{ handle }}_item.antlers.html'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'vacancies_blueprint.yaml.stub',
                        'output' => 'resources/blueprints/collections/{{ handle }}/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'copy',
                        'input' => 'vacancies_collection.yaml.stub',
                        'output' => 'content/collections/{{ handle }}.yaml'
                    ],
                    [
                        'type' => 'update_role',
                        'role' => 'editor',
                        'permissions' => ['view {{ handle }} entries', 'edit {{ handle }} entries', 'create {{ handle }} entries', 'delete {{ handle }} entries', 'publish {{ handle }} entries', 'reorder {{ handle }} entries', 'edit other authors {{ handle }} entries', 'publish other authors {{ handle }} entries', 'delete other authors {{ handle }} entries']
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `lang/locale/strings.php` file:\n\n// {{ name }}\n'{{ handle }}_expires' => 'Expires',\n'{{ handle }}_hours' => 'Hours',\n'{{ handle }}_published' => 'Published',\n'{{ handle }}_region' => 'Region',\n'{{ handle }}_employment_type' => 'Employment type',\n'{{ handle }}_part_time' => 'Part time',\n'{{ handle }}_full_time' => 'Full time',\n'{{ handle }}_salary_min' => 'Minimum salary',\n'{{ handle }}_salary_max' => 'Maximum salary',"
                    ],
                    [
                        'type' => 'notify',
                        'content' => "Add this to your `config/statamic/cp.php` widgets array:\n\n[\n\t'type' => 'collection',\n\t'collection' => '{{ handle }}',\n\t'width' => 50\n],"
                    ],
                ]
            ],
        ]);
    }
}
