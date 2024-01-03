<?php

namespace Livewire\Volt\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'volt:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Volt resources';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->components->info('Publishing Volt Service Provider.');

        $this->callSilent('vendor:publish', ['--tag' => 'volt-provider']);

        $this->registerVoltServiceProvider();

        $this->ensureLivewireDirectoryExists();

        $this->components->info('Volt scaffolding installed successfully.');
    }

    /**
     * Register the Volt service provider in the application configuration file.
     */
    protected function registerVoltServiceProvider(): void
    {
        if (method_exists(ServiceProvider::class, 'addProviderToBootstrapFile') &&
            ServiceProvider::addProviderToBootstrapFile(\App\Providers\VoltServiceProvider::class)) { // @phpstan-ignore-line
            return;
        }

        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace.'\\Providers\\VoltServiceProvider::class')) {
            return;
        }

        $lineEndingCount = [
            "\r\n" => substr_count($appConfig, "\r\n"),
            "\r" => substr_count($appConfig, "\r"),
            "\n" => substr_count($appConfig, "\n"),
        ];

        $eol = array_keys($lineEndingCount, max($lineEndingCount))[0];

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\RouteServiceProvider::class,".$eol,
            "{$namespace}\\Providers\RouteServiceProvider::class,".$eol."        {$namespace}\Providers\VoltServiceProvider::class,".$eol,
            $appConfig
        ));

        file_put_contents(app_path('Providers/VoltServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/VoltServiceProvider.php'))
        ));
    }

    /**
     * Ensure the Livewire directory exists.
     */
    protected function ensureLivewireDirectoryExists(): void
    {
        if (! is_dir($directory = config('livewire.view_path', resource_path('views/livewire')))) {
            File::ensureDirectoryExists($directory);

            File::put($directory.'/.gitkeep', '');
        }
    }
}
