<?php

namespace Livewire\Volt\Console;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Volt\Volt;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'make:volt')]
class MakeCommand extends GeneratorCommand
{
    use CreatesMatchingTest;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:volt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Volt component';

    /**
     * The type of file being generated.
     *
     * @var string
     */
    protected $type = 'Volt component';

    /**
     * Get the destination view path.
     *
     * @param  string  $name
     */
    protected function getPath($name): string
    {
        $paths = Volt::paths();

        $mountPath = isset($paths[0]) ? $paths[0]->path : resource_path('views/livewire');

        return $mountPath.'/'.Str::lower(Str::finish($this->argument('name'), '.blade.php'));
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        $stubName = $this->option('class') ? 'volt-component-class.stub' : 'volt-component.stub';

        return file_exists($customPath = $this->laravel->basePath('stubs/'.$stubName))
            ? $customPath
            : __DIR__.'/../../stubs/'.$stubName;
    }

    /**
     * Create the matching test case if requested.
     *
     * @param  string  $path
     */
    protected function handleTestCreation($path): bool
    {
        if (! $this->option('test') && ! $this->option('pest')) {
            return false;
        }

        $contents = preg_replace(
            ['/\{{ namespace \}}/', '/\{{ class \}}/', '/\{{ name \}}/'],
            [$this->testNamespace(), $this->testClassName(), $this->testComponentName()],
            File::get($this->getTestStub()),
        );

        File::ensureDirectoryExists(dirname($this->getTestPath()), 0755, true);

        return File::put($this->getTestPath(), $contents);
    }

    /**
     * Get the namespace for the test.
     */
    protected function testNamespace(): string
    {
        return Str::of($this->fullyQualifiedTestName())
            ->beforeLast('\\')
            ->value();
    }

    /**
     * Get the class name for the test.
     */
    protected function testClassName(): string
    {
        return Str::of($this->fullyQualifiedTestName())
            ->afterLast('\\')
            ->append('Test')
            ->value();
    }

    /**
     * Get the component name for the test.
     */
    protected function testComponentName(): string
    {
        return Str::of($this->argument('name'))
            ->replace('.blade.php', '')
            ->replace('/', '.')
            ->lower()
            ->value();
    }

    /**
     * Get the test stub file for the generator.
     */
    protected function getTestStub(): string
    {
        $stubName = 'volt-component-'.($this->option('pest') ? 'pest' : 'test').'.stub';

        return file_exists($customPath = $this->laravel->basePath("stubs/$stubName"))
            ? $customPath
            : __DIR__.'/../../stubs/'.$stubName;
    }

    /**
     * Get the destination test case path.
     */
    protected function getTestPath(): string
    {
        return base_path(
            Str::of($this->fullyQualifiedTestName())
                ->replace('\\', '/')
                ->replaceFirst('Tests/Feature', 'tests/Feature')
                ->append('Test.php')
                ->value()
        );
    }

    /**
     * Get the fully qualified name for the test.
     */
    protected function fullyQualifiedTestName(): string
    {
        $name = Str::of(Str::lower($this->argument('name')))->replace('.blade.php', '');

        $namespacedName = Str::of(
            Str::of($name)
                ->replace('/', ' ')
                ->explode(' ')
                ->map(fn ($part) => Str::of($part)->ucfirst())
                ->implode('\\')
        )
            ->replace(['-', '_'], ' ')
            ->explode(' ')
            ->map(fn ($part) => Str::of($part)->ucfirst())
            ->implode('');

        return 'Tests\\Feature\\Livewire\\'.$namespacedName;
    }

    /**
     * Get the console command arguments.
     */
    protected function getOptions(): array
    {
        return [
            ['class', null, InputOption::VALUE_NONE, 'Create a class based component'],
            ['force', 'f', InputOption::VALUE_NONE, 'Create the Volt component even if the component already exists'],
        ];
    }

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array
     */
    protected function promptForMissingArgumentsUsing()
    {
        return [
            'name' => version_compare(Application::VERSION, '10.17.0', '>=')
                ? ['What should the Volt component be named?', 'E.g. counter']
                : 'What should the Volt component be named?',
        ];
    }
}
