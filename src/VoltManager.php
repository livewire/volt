<?php

namespace Livewire\Volt;

use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Routing\Route;
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
        protected LivewireManager $manager,
        protected MountedDirectories $mountedDirectories,
        protected Registrar $router,
    ) {
    }

    /**
     * Registers a new Volt route.
     */
    public function route(string $uri, string $componentName): Route
    {
        return $this->router->get($uri, function () use ($componentName) {
            $container = Container::getInstance();

            return $container->call([
                $container->make(LivewireManager::class)->new($componentName),
                '__invoke',
            ]);
        });
    }

    /**
     * Mount the given path and auto-register its Volt components.
     *
     * @param  array<int, string>|string  $paths
     * @param  array<int, class-string>|class-string  $uses
     */
    public function mount(array|string $paths = [], array|string $uses = []): void
    {
        $paths = collect(empty($paths) ? [
            config('view.paths')[0].'/livewire',
            config('view.paths')[0].'/pages',
        ] : $paths)->map(fn (string $p) => str_replace(
            '/',
            DIRECTORY_SEPARATOR,
            $p,
        ))->all();

        $this->mountedDirectories->mount($paths, $uses);
    }

    /**
     * Test a Volt / Livewire component.
     */
    public function test(string $name, array $params = []): Testable
    {
        $this->ensureViewsAreCached();

        if (FragmentMap::has($name)) {
            $name = FragmentMap::get($name);
        }

        return Livewire::test($name, $params);
    }

    /**
     * Define the query parameters for testing.
     */
    public function withQueryParams(array $params): static
    {
        $this->manager->withQueryParams($params);

        return $this;
    }

    /**
     * Set the currently logged in user for the application.
     */
    public function actingAs(Authenticatable $user, ?string $driver = null): static
    {
        $this->manager->actingAs($user, $driver);

        return $this;
    }

    /**
     * Ensure that the views are cached for testing.
     */
    public function ensureViewsAreCached(): void
    {
        if (! static::$viewsAreCached) {
            Artisan::call('view:cache');

            static::$viewsAreCached = true;
        }
    }

    /**
     * Get the mounted directory paths.
     *
     * @return array<int, \Livewire\Volt\MountedDirectory>
     */
    public function paths(): array
    {
        return $this->mountedDirectories->paths();
    }
}
