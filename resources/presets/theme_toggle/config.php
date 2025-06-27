<?php

return [
    'handle' => 'theme_toggle',
    'name' => 'Theme toggle',
    'description' => 'Theme toggle for dark mode.',
    'operations' => [
        [
            'type' => 'copy',
            'input' => 'resources/views/components/_theme_toggle.antlers.html',
            'output' => 'resources/views/components/_theme_toggle.antlers.html',
        ],
        [
            'type' => 'notify',
            'content' => 'Add `@variant dark (&:where(.dark, .dark *));` to `resources/css/site.css`.',
        ],
        [
            'type' => 'notify',
            'content' => 'Add `{{ partial:components/theme_toggle }}` as the last list item in the main ul in `resources/views/navigation/_main_desktop.antlers.html`. The `section:theme_toggle` is automatically yielded in `resources/views/snippets/_browser_appearance.antlers.html`.',
        ],
        [
            'type' => 'notify',
            'content' => "Add this to your `lang/locale/strings.php` file:\n(and possibly any other locales you support)\n'theme_toggle_dark' => 'Use dark theme.',\n'theme_toggle_light' => 'Use light theme.',\n'theme_toggle_system' => 'Use system preference.',\n'theme_toggle_dark_short' => 'Dark',\n'theme_toggle_light_short' => 'Light',\n'theme_toggle_system_short' => 'System',\n'theme_toggle_dark_toggle_open' => 'Dark theme: open site theme picker.',\n'theme_toggle_dark_toggle_close' => 'Dark theme: close site theme picker.',\n'theme_toggle_light_toggle_open' => 'Light theme: open site theme picker.',\n'theme_toggle_light_toggle_close' => 'Light theme: close site theme picker.',\n'theme_toggle_system_toggle_open' => 'System (Default): open site theme picker.',\n'theme_toggle_system_toggle_close' => 'System (Default): close site theme picker.'",
        ],
        [
            'type' => 'notify',
            'content' => 'Optionally, add the classes `scheme-light dark:scheme-dark` your HTML element in `resources/views/layout.antlers.html`.',
        ],
    ],
];
