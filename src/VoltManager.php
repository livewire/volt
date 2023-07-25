<?php

namespace Livewire\Volt;

use Illuminate\Support\Facades\Artisan;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;

class VoltManager
{
    /**
     * Indicates if the views have been cached for testing.
     */
    protected static bool $viewsAreCached = false;

    /**
     * Create a new volt manager instance.
     */
    public function __construct(
        protected MountedDirectories $mountedDirectories
    ) {
    }

    /**
     * Mount the given path and auto-register its Volt components.
     *
     * @param  array<int, string>|string  $paths
     * @param  array<int, class-string>|class-string  $uses
     */
    public function mount(array|string $paths = [], array|string $uses = []): void
    {
        $this->mountedDirectories->mount(empty($paths) ? [
            config('view.paths')[0].'/livewire',
            config('view.paths')[0].'/pages',
        ] : $paths, $uses);
    }

    /**
     * Test a Volt / Livewire component.
     */
    public function test(string $name, array $params = []): Testable
    {
        if (! static::$viewsAreCached) {
            Artisan::call('view:cache');

            static::$viewsAreCached = true;
        }

        if (FragmentMap::has($name)) {
            $name = FragmentMap::get($name);
        }

        return Livewire::test($name, $params);
    }

    /**
     * Get the mounted directory paths.
     *
     * @return  array<int, \Livewire\Volt\MountedDirectory>
     */
    public function paths(): array
    {
        return $this->mountedDirectories->paths();
    }
}
