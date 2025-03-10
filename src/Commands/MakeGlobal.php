<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Statamic\Console\RunsInPlease;
use Studio1902\PeakCommands\Commands\Traits\NeedsValidLicense;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\text;

class MakeGlobal extends Command
{
    use RunsInPlease, SharedFunctions, NeedsValidLicense;

    protected $name = 'statamic:peak:make:global';
    protected $description = "Make a global set.";
    protected $global_name = '';
    protected $filename = '';
    protected $permissions = true;

    public function handle()
    {
        $this->checkLicense();

        $this->global_name = text(
            label: 'What should be the name for this global?',
            placeholder: 'E.g. Contact data',
            required: true
        );

        $this->filename = Str::slug($this->global_name, '_');

        $this->permissions = confirm(
            label: 'Grant edit permissions to editor role?',
            default: true
        );

        try {
            $this->createGlobal();
            $this->createBlueprint();
            $this->handlePermissions();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        Artisan::call('cache:clear');

        $this->info("<info>[✓]</info> Global '{$this->global_name}' created.");
    }

    /**
     * Create fieldset.
     *
     * @return bool|null
     */
    protected function createGlobal()
    {
        $this->checkExistence('Global', "content/globals/{$this->filename}.yaml");

        $stub = $this->getStub('/global.yaml.stub');
        $contents = Str::of($stub)
            ->replace('{{ global_name }}', $this->global_name);

        File::put(base_path("content/globals/{$this->filename}.yaml"), $contents);
    }

    /**
     * Create blueprints.
     *
     * @return bool|null
     */
    protected function createBlueprint()
    {
        $this->checkExistence('Blueprint', "resources/blueprints/globals/{$this->filename}.yaml");

        $stub = $this->getStub('/global_blueprint.yaml.stub');

        $contents = Str::of($stub)
            ->replace('{{ global_name }}', $this->global_name);

        File::put(base_path("resources/blueprints/globals/{$this->filename}.yaml"), $contents);
    }

    /**
     * Handle permissions
     *
     * @return bool|null
     */
    protected function handlePermissions()
    {
        if (! $this->permissions) {
            return;
        }

        $permissions = [
            "edit {$this->filename} globals",
        ];

        $this->grantPermissionsToEditor($permissions);
    }
}
