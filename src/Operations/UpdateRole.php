<?php

namespace Studio1902\PeakCommands\Operations;

use Illuminate\Support\Facades\File;
use Statamic\Support\Arr;
use Studio1902\PeakCommands\Models\Installable;
use Symfony\Component\Yaml\Yaml;

use function Laravel\Prompts\info;

class UpdateRole extends Operation
{
    protected string $role;

    protected array $permissions;

    public function __construct(array $config)
    {
        $this->role = Arr::get($config, 'role');
        $this->permissions = Arr::get($config, 'permissions');
    }

    public function run(): Installable
    {
        $roles = Yaml::parseFile(base_path('resources/users/roles.yaml'));

        $existingPermissions = Arr::get($roles, "$this->role.permissions");
        $permissions = array_merge($existingPermissions, str_replace('{{ handle }}', $this->installable->renameHandle, $this->permissions));

        Arr::set($roles, "$this->role.permissions", $permissions);

        File::put(base_path('resources/users/roles.yaml'), Yaml::dump($roles, 99, 2));

        info("Permissions updated for '{$this->role}' role.");

        return $this->installable;
    }
}
