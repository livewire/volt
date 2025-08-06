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

        $mountPath = isset($paths[0]) ? $paths[0]->path : config('livewire.view_path', resource_path('views/livewire'));

        $argumentName = $this->argument('name');

        if (! str_contains($argumentName, '.blade.php')) {
            $view = str_replace('.', '/', $argumentName);
        } else {
            $view = $argumentName;
        }

        return $mountPath.'/'.Str::lower(Str::finish($view, '.blade.php'));
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        if ($this->option('class')) {
            $stubName = 'volt-component-class.stub';
        } elseif ($this->option('functional')) {
            $stubName = 'volt-component.stub';
        } elseif ($this->alreadyUsingClasses()) {
            $stubName = 'volt-component-class.stub';
        } else {
            $stubName = 'volt-component.stub';
        }

        return file_exists($customPath = $this->laravel->basePath('stubs/'.$stubName))
            ? $customPath
            : __DIR__.'/../../stubs/'.$stubName;
    }

    /**
     * Determine if the project is currently using class-based components.
     */
    protected function alreadyUsingClasses(): bool
    {
        $paths = Volt::paths();

        $mountPath = isset($paths[0])
            ? $paths[0]->path
            : config('livewire.view_path', resource_path('views/livewire'));

        $files = collect(File::allFiles($mountPath));

        foreach ($files as $file) {
            if ($file->getExtension() === 'php' && str_ends_with($file->getFilename(), '.blade.php')) {
                $content = File::get($file->getPathname());

                if (str_contains($content, 'use Livewire\Volt\Component') ||
                    str_contains($content, 'new class extends Component')) {
                    return true;
                }
            }
        }

        return false;
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
        $argumentName = $this->argument('name');

        if (! str_contains($argumentName, '.blade.php')) {
            $processedName = str_replace('.', '/', $argumentName);
        } else {
            $processedName = $argumentName;
        }

        $name = Str::of(Str::lower($processedName))->replace('.blade.php', '');

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
            ['functional', null, InputOption::VALUE_NONE, 'Create a functional component'],
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
