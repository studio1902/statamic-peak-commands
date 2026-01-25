<?php

namespace Studio1902\PeakCommands\Models;

use Illuminate\Support\Str;
use Statamic\Facades\Blink;
use Statamic\Facades\Config;
use Statamic\Facades\Entry;
use Statamic\Support\Arr;
use Stringy\StaticStringy as Stringy;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\search;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class Collection
{
    public string $name;

    public string $filename;

    public bool $grantPermissions;

    public bool $public;

    public bool $slugs;

    public bool $shouldMount;

    public bool $addPage;

    public string $page;

    public string $mount;

    public string $route;

    public string $layout;

    public bool $revisions;

    public string $sortDir;

    public bool $dated;

    public string $datePast;

    public string $dateFuture;

    public bool $index;

    public bool $show;

    public function __construct(array $config = [])
    {
        $this->name = Arr::get($config, 'name') ?? $this->promptForName();
        $this->filename = $this->generateFilename();
        $this->public = Arr::get($config, 'public') ?? $this->promptForPublic();
        $this->slugs = Arr::get($config, 'slugs') ?? $this->promptForSlugs();
        $this->shouldMount = Arr::get($config, 'should_mount') ?? $this->promptForShouldMount();
        $this->addPage = Arr::get($config, 'add_page') ?? $this->promptForAddPage();
        $this->page = Arr::get($config, 'page') ?? $this->promptForPage();
        $this->route = Arr::get($config, 'route') ?? $this->promptForRoute();
        $this->layout = Arr::get($config, 'layout') ?? $this->promptForLayout();
        $this->revisions = Arr::get($config, 'revisions') ?? $this->promptForRevisions();
        $this->sortDir = Arr::get($config, 'sort_dir') ?? $this->promptForSortDir();
        $this->dated = Arr::get($config, 'dated') ?? $this->promptForDated();
        $this->datePast = Arr::get($config, 'date_past') ?? $this->promptForDatePast();
        $this->dateFuture = Arr::get($config, 'date_future') ?? $this->promptForDateFuture();
        $this->index = Arr::get($config, 'index') ?? $this->promptForIndex();
        $this->show = Arr::get($config, 'show') ?? $this->promptForShow();
        $this->grantPermissions = Arr::get($config, 'permissions') ?? $this->promptForPermissions();
        $this->mount = $this->linkMount();
    }

    protected function promptForName(): string
    {
        return text(
            label: 'What should be the name for this collection?',
            placeholder: 'E.g. News',
            required: true
        );
    }

    protected function generateFilename(): string
    {
        return Stringy::slugify($this->name, '_', Config::getShortLocale());
    }

    protected function promptForPublic(): bool
    {
        return confirm(
            label: 'Should this be a public collection with a route?',
            default: true
        );
    }

    protected function promptForSlugs(): bool
    {
        if ($this->public) {
            return true;
        }

        return confirm(
            label: 'Do you want to require slugs?',
            default: true
        );
    }

    protected function promptForShouldMount(): bool
    {
        if (! $this->public) {
            return false;
        }

        return confirm(
            label: 'Do you want to mount this collection on an entry?',
            default: true
        );
    }

    protected function promptForAddPage(): bool
    {
        if (! $this->shouldMount) {
            return false;
        }

        return confirm(
            label: 'Do you want to mount on a new or existing page?',
            default: true,
            yes: 'New page',
            no: 'Existing page'
        );
    }

    protected function promptForPage(): string
    {
        if (! $this->shouldMount) {
            return '';
        }

        if ($this->addPage) {
            return text(
                label: 'What should be the page title for this mount?',
                placeholder: 'E.g. News',
                required: true
            );
        }

        $selection = search(
            label: 'On which page existing page do you want to mount this collection?',
            options: function (string $value) {
                if (! $value) {
                    return collect($this->getPages())
                        ->values()
                        ->all();
                }

                return collect($this->getPages())
                    ->filter(fn (string $item) => Str::contains($item, $value, true))
                    ->values()
                    ->all();
            },
            required: true
        );

        preg_match('/\[(.*?)\]/', $selection, $matches);

        return $matches[1] ?? '';
    }

    protected function getPages()
    {
        return Blink::once('all_pages', function () {
            return Entry::query()
                ->where('collection', 'pages')
                ->whereStatus('published')
                ->orderBy('title', 'asc')
                ->get()
                ->map(fn ($entry) => "{$entry->get('title')} [{$entry->id()}]")
                ->toArray();
        });
    }

    protected function linkMount(): string
    {
        if (! $this->shouldMount) {
            return '';
        }

        if (! $this->addPage) {
            return $this->page;
        }

        return $this->addPage();
    }

    protected function addPage(): string
    {
        $entry = Entry::make()
            ->collection('pages')
            ->published(true)
            ->slug(Stringy::slugify($this->page, '-', Config::getShortLocale()))
            ->data(['title' => $this->page]);

        $entry->save();

        return $entry->id();
    }

    protected function promptForRoute(): string
    {
        if (! $this->public) {
            return '';
        }

        return text(
            label: 'What should be the route for this collection?',
            default: '/{mount}/{slug}',
            required: true
        );
    }

    protected function promptForLayout(): string
    {
        return text(
            label: 'What should be the layout file for this collection?',
            default: 'layout',
            required: true
        );
    }

    protected function promptForRevisions(): bool
    {
        return confirm(
            label: 'Should revisions be enabled?',
            default: false
        );
    }

    protected function promptForSortDir(): string
    {
        return select(
            label: 'What should the sort direction be?',
            options: [
                'asc' => 'Ascending',
                'desc' => 'Descending',
            ],
            default: 'asc'
        );
    }

    protected function promptForDated(): bool
    {
        return confirm(
            label: 'Should this be a dated collection?',
            default: false
        );
    }

    protected function promptForDatePast(): string
    {
        if (! $this->dated) {
            return '';
        }

        return select(
            label: 'What should be the date behavior for entries in the past?',
            options: [
                'public' => 'Public',
                'private' => 'Private',
            ],
            default: 'public'
        );
    }

    protected function promptForDateFuture(): string
    {
        if (! $this->dated) {
            return '';
        }

        return select(
            label: 'What should be the date behavior for entries in the future?',
            options: [
                'public' => 'Public',
                'private' => 'Private',
            ],
            default: 'private'
        );
    }

    protected function promptForIndex(): bool
    {
        if (! $this->public || ! $this->shouldMount) {
            return false;
        }

        return confirm(
            label: 'Generate and apply index template?',
            default: true
        );
    }

    protected function promptForShow(): bool|string
    {
        if (! $this->public) {
            return false;
        }

        return confirm(
            label: 'Generate and apply show template?',
            default: true
        );
    }

    protected function promptForPermissions(): bool
    {
        return confirm(
            label: 'Grant edit permissions to editor role?',
            default: true
        );
    }
}
