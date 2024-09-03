<?php

namespace Livewire\Volt;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;

class ExtractedFragment
{
    /**
     * Create a new extracted fragment instance.
     */
    public function __construct(
        protected string $componentName,
        protected string $componentPath
    ) {}

    /**
     * Resolve an extracted fragmnet instance from a given alias.
     */
    public static function fromAlias(string $alias): ?ExtractedFragment
    {
        if (! is_null($decoded = FragmentAlias::decode($alias))) {
            return new ExtractedFragment($decoded['name'], $decoded['path']);
        }

        return null;
    }

    /**
     * Re-extract the fragment if the upstream template containing it has been modified.
     */
    public function extractIfStale(): ExtractedFragment
    {
        if ($this->stale()) {
            Blade::compile($this->componentPath);
        }

        return $this;
    }

    /**
     * Determine if the fragment needs to be re-extracted due to upstream template changes.
     */
    public function stale(): bool
    {
        return ! $this->exists() || (File::lastModified($this->componentPath) >= File::lastModified($this->path()));
    }

    /**
     * Determine if the extracted fragment file exists.
     */
    public function exists(): bool
    {
        return File::exists($this->path());
    }

    /**
     * Get the path to the extracted fragment file.
     */
    public function path(): string
    {
        return config('view.compiled').'/'.md5($this->componentName).'.blade.php';
    }
}
