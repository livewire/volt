<?php

namespace Livewire\Volt;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Testing\TestResponse;
use Livewire\Livewire;
use Livewire\LivewireServiceProvider;
use Livewire\Volt\Precompilers\ExtractFragments;
use Livewire\Volt\Precompilers\ExtractTemplate;

class VoltServiceProvider extends ServiceProvider
{
    /**
     * Register any package services.
     */
    public function register(): void
    {
        $this->app->singleton(ComponentFactory::class);
        $this->app->singleton(ComponentResolver::class);
        $this->app->singleton(ExtractFragments::class);
        $this->app->singleton(ExtractTemplate::class);
        $this->app->singleton(MountedDirectories::class);
        $this->app->singleton(VoltManager::class);

        $this->app->when(ExtractFragments::class)
            ->needs('$compiledViewPath')
            ->give(fn () => config('view.compiled'));
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerPublishing();
        $this->registerTestingMacros();

        Blade::prepareStringsForCompilationUsing(function (string $value) {
            foreach ([ExtractFragments::class, ExtractTemplate::class] as $precompiler) {
                $value = $this->app->make($precompiler)->__invoke($value);
            }

            return $value;
        });

        $this->app->booted(function () {
            $this->bindLivewireManager();

            $this->ensureMissingLivewireComponentsCanBeResolved();
        });
    }

    /**
     * Bind the custom Volt Livewire manager in the container.
     */
    protected function bindLivewireManager(): void
    {
        $this->app->singleton(LivewireManager::class);
        $this->app->alias(LivewireManager::class, 'livewire');

        Facade::clearResolvedInstance('livewire');

        $this->app->get(LivewireManager::class)->setProvider(
            $this->app->getProvider(LivewireServiceProvider::class),
        );
    }

    /**
     * Ensure that any missing Livewire components can be resolved.
     */
    protected function ensureMissingLivewireComponentsCanBeResolved(): void
    {
        Livewire::resolveMissingComponent(fn (string $name) => app(ComponentResolver::class)->resolve(
            $name, collect(app(MountedDirectories::class)->paths())->pluck('path')->all(),
        ));
    }

    /**
     * Register the package's commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
                Console\MakeCommand::class,
            ]);
        }
    }

    /**
     * Register the package's publishable resources.
     */
    private function registerPublishing(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../stubs/VoltServiceProvider.stub' => app_path('Providers/VoltServiceProvider.php'),
            ], 'volt-provider');
        }
    }

    /**
     * Register the package's testing macros.
     */
    protected function registerTestingMacros(): void
    {
        TestResponse::macro('assertSeeVolt', function ($component) {
            Volt::ensureViewsAreCached();

            if (FragmentMap::has($component)) {
                $component = FragmentMap::get($component);
            }

            return $this->assertSeeLivewire($component);
        });

        TestResponse::macro('assertDontSeeVolt', function ($component) {
            Volt::ensureViewsAreCached();

            if (FragmentMap::has($component)) {
                $component = FragmentMap::get($component);
            }

            return $this->assertDontSeeLivewire($component);
        });
    }
}
