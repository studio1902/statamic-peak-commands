<?php

return [
    'handle' => 'horizon',
    'name' => 'Horizon',
    'description' => 'Install Laravel Horizon configured for Peak.',
    'operations' => [
         [
            'type' => 'run',
            'command' => 'composer require laravel/horizon',
            'processing_message' => 'Installing laravel/horizon',
            'success_message' => 'Horizon installed.',
        ],
        [
            'type' => 'run',
            'command' => 'php artisan horizon:install',
            'processing_message' => 'Installing Horizon resources.',
            'success_message' => 'Horizon resources installed.',
        ],
        [
            'type' => 'run',
            'command' => "node -e \"require('fs').closeSync(require('fs').openSync('database/database.sqlite', 'a'))\"",
            'processing_message' => 'Creating SQLite database.',
            'success_message' => 'SQLite database created.',
        ],
        [
            'type' => 'copy',
            'input' => 'config/horizon.php',
            'output' => 'config/horizon.php',
        ],
        [
            'type' => 'notify',
            'content' => "Add this to `.env` file:\n\nSOCIAL_IMAGE_QUEUE_NAME=social-images\nSTATAMIC_STATIC_WARM_QUEUE=static-cache",
        ],
        [
            'type' => 'notify',
            'content' => "Replace the commands in your Ploi deploy script with:\n\n{SITE_PHP} artisan migrate --force\n{SITE_PHP} artisan cache:clear\n{SITE_PHP} artisan config:cache\n{SITE_PHP} artisan route:cache\n{SITE_PHP} artisan statamic:stache:warm\n{SITE_PHP} artisan horizon:terminate\n{SITE_PHP} artisan statamic:search:update --all\n{SITE_PHP} artisan horizon:clear --queue=static-cache --force\n{SITE_PHP} artisan statamic:static:clear\n{SITE_PHP} artisan statamic:static:warm --queue",
        ],
        [
            'type' => 'notify',
            'content' => "Replace the commands in your Forge deploy script with:\n\n\$FORGE_PHP artisan migrate --force\n\$FORGE_PHP artisan cache:clear\n\$FORGE_PHP artisan config:cache\n\$FORGE_PHP artisan route:cache\n\$FORGE_PHP artisan statamic:stache:warm\n\$FORGE_PHP artisan horizon:terminate\n\$FORGE_PHP artisan statamic:search:update --all\n\$FORGE_PHP artisan horizon:clear --queue=static-cache --force\n\$FORGE_PHP artisan statamic:static:clear\n\$FORGE_PHP artisan statamic:static:warm --queue",
        ],

    ],
];
