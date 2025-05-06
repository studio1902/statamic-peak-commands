<?php

return [
    /**
     * Here you can configure the paths in which the commands addon
     * searches for premade blocks, presets and article sets.
     */
    'paths' => [
        'blocks' => [
            base_path('vendor/studio1902/statamic-peak-commands/resources/blocks'),
        ],
        'presets' => [
            base_path('vendor/studio1902/statamic-peak-commands/resources/presets'),
        ],
        'sets' => [
            base_path('vendor/studio1902/statamic-peak-commands/resources/sets'),
        ],
    ],

    /**
     * Here you can configure the namespaces in which the commands addon
     * searches for available operations to run.
     */
    'namespaces' => [
        '\Studio1902\PeakCommands\Operations',
    ],
];
