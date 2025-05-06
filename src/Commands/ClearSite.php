<?php

namespace Studio1902\PeakCommands\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Statamic\Console\RunsInPlease;
use Statamic\Facades\Entry;
use Statamic\Facades\GlobalSet;
use Statamic\Support\Arr;
use Studio1902\PeakCommands\Commands\Traits\CanClearCache;
use Symfony\Component\Yaml\Yaml;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;

class ClearSite extends Command
{
    use CanClearCache, RunsInPlease;

    protected $name = 'statamic:peak:clear-site';

    protected $description = 'Clear all default Peak content.';

    public function handle(): void
    {
        $shouldClear = confirm(
            label: 'Do you want to clear all default Peak content?',
            default: false
        );

        if (! $shouldClear) {
            return;
        }

        $this->trashAssets();
        $this->clearGlobalSocialMedia();
        $this->clearHomePage();
        $this->trashPagesButHomeAnd404();
        $this->clearNavigation();

        info('[✓] Your view from the peak is clear.');

        $this->clearGlideCache();
        $this->clearCache();
    }

    /**
     * Trash all assets.
     */
    protected function trashAssets(): void
    {
        $files = new Filesystem;
        $path = public_path('images');
        if ($files->exists($path)) {
            $files->cleanDirectory($path);
        }
    }

    /**
     * Trash global social media data.
     */
    protected function clearGlobalSocialMedia(): void
    {
        $set = GlobalSet::findByHandle('social_media');
        $set->inDefaultSite()->set('social_media', null)->save();
    }

    /**
     * Clear the home page.
     */
    protected function clearHomePage(): void
    {
        // Note: we can't use Entry::query()->save() when running from the PostInstallHook.
        $stub = $this->getStub('/home.md.stub');
        File::put(base_path('content/collections/pages/home.md'), $stub);
    }

    /**
     * Trash all pages but home.
     */
    protected function trashPagesButHomeAnd404(): void
    {
        $pages = Entry::query()
            ->where('collection', 'pages')
            ->where('id', '!=', 'home')
            ->where('title', '!=', 'Page not found')
            ->get();

        foreach ($pages as $page) {
            $file_path = base_path("content/collections/pages/{$page->slug()}.md");
            if (File::exists($file_path)) {
                File::delete($file_path);
            }
        }
    }

    /**
     * Clear navigation.
     */
    protected function clearNavigation(): void
    {
        $navigation = Yaml::parseFile(base_path('content/trees/navigation/main.yaml'));
        $tree = Arr::get($navigation, 'tree');
        unset($tree[1]);
        Arr::set($navigation, 'tree', $tree);

        File::put(base_path('content/trees/navigation/main.yaml'), Yaml::dump($navigation, 99, 2));
    }

    /**
     * Get stub.
     */
    protected function getStub(string $stubPath): string
    {
        $publishedStubPath = resource_path('stubs/vendor/statamic-peak-commands/'.ltrim($stubPath, " /\t\n\r\0\x0B"));
        $addonStubPath = __DIR__.'/../../resources/stubs/'.ltrim($stubPath, " /\t\n\r\0\x0B");

        return File::get(File::exists($publishedStubPath) ? $publishedStubPath : $addonStubPath);
    }
}
