<?php

return [
    'handle' => 'search',
    'name' => 'Search',
    'description' => 'Search form search results.',
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
            'content' => "Add this to your `lang/locale/strings.php` file:\n\n// Search\n'search' => 'Search',\n'search_close' => 'Close search',\n'search_open' => 'Open search',\n'search_no_results' => 'No results found',\n'search_results_for' => 'Results for',"
        ],
        [
            'type' => 'notify',
            'content' => "Add `{{ partial:components/search_form }}` as the last list item in the main ul in `resources/views/navigation/_main.antlers.html`."
        ],
        [
            'type' => 'notify',
            'content' => "Add the following route to `routes/web.php`:\n\n// The Search route to display search results with `views/search.antlers.html`.\nRoute::statamic('/search', 'search');"
        ],
        [
            'type' => 'notify',
            'content' => "Add fields you want indexed to the index in config/statamic/search.php. The page_builder field is added by default."
        ],
        [
            'type' => 'notify',
            'content' => "Update the search index by running php please search:update --all."
        ],
        [
            'type' => 'notify',
            'content' => "Make sure you add the update command to your deployment script."
        ]
    ]
];
