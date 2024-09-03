<?php

namespace Livewire\Volt\Precompilers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Laravel\Folio\Folio;
use Livewire\Volt\Exceptions\VoltDirectiveMissingException;
use Livewire\Volt\FragmentAlias;
use Livewire\Volt\FragmentMap;

class ExtractFragments
{
    use Concerns\ExtractsImports;

    /**
     * Create a new precompiler instance.
     */
    public function __construct(protected string $compiledViewPath) {}

    /**
     * Extract @volt Livewire fragments from the template and write them to their own template file.
     *
     * Then, replace them with <livewire> tags that render that template.
     */
    public function __invoke(string $template): string
    {
        if (! str_contains($template, '@volt')) {
            $this->ensurePagesUsingFragmentsUseDirective($template);

            return $template;
        }

        $imports = $this->imports($template);

        $template = preg_replace_callback('/(?<!@)@volt\((.*?)\)(.*?)@endvolt/s', function (array $matches) use ($imports) {
            [$name, $arguments] = $this->argumentsFromMatch($matches[1]);

            if (is_null($name)) {
                $name = 'volt-anonymous-fragment-'.md5($matches[2]);
            }

            File::put(
                $this->compiledViewPath.DIRECTORY_SEPARATOR.md5($name).'.blade.php',
                $imports.$matches[2]
            );

            return $this->directive($name, $arguments);
        }, $template);

        return preg_replace_callback('/(?<!@)@volt(.*?)@endvolt/s', function (array $matches) use ($imports) {
            $name = 'volt-anonymous-fragment-'.md5($matches[1]);

            File::put(
                $this->compiledViewPath.DIRECTORY_SEPARATOR.md5($name).'.blade.php',
                $imports.$matches[1]
            );

            return $this->directive($name, []);
        }, $template);
    }

    /**
     * Get the arguments from the @volt directive.
     *
     * @return array{0: string|null, 1: array<string, mixed>}
     */
    protected function argumentsFromMatch(string $match): array
    {
        $arguments = eval(sprintf('return (fn (...$arguments) => $arguments)(%s);', trim($match)));

        if (isset($arguments[0])) {
            if (is_string($arguments[0])) {
                $name = array_shift($arguments);
            }

            if (isset($arguments[0]) && is_array($arguments[0])) {
                $arguments = $arguments[0];
            }
        }

        return [$name ?? null, $arguments];
    }

    /**
     * Compute the @volt directive into a <livewire> tag.
     */
    protected function directive(string $name, array $arguments): string
    {
        $argumentsAsString = sprintf(
            '%s::componentArguments([...get_defined_vars(), ...%s])',
            static::class,
            var_export($arguments, true),
        );

        FragmentMap::add($name, $alias = FragmentAlias::encode($name, Blade::getPath()));

        return '@livewire("'.$alias.'", '.$argumentsAsString.')';
    }

    /**
     * Ensure that Folio pages using fragments have the "@volt" directive.
     *
     * @throws \Livewire\Volt\Exceptions\VoltDirectiveMissingException
     */
    protected function ensurePagesUsingFragmentsUseDirective(string $template): void
    {
        if (! class_exists(Folio::class)
            || ! str_contains($template, 'Livewire\Volt')
            || (bool) preg_match('/{{.*?}}/s', $template) === false) {
            return;
        }

        foreach (Folio::paths() as $path) {
            if (str_starts_with($bladePath = Blade::getPath(), $path)) {
                throw new VoltDirectiveMissingException($bladePath);
            }
        }
    }

    /**
     * Get the arguments that should be passed to the component when rendering it.
     *
     * @param  array<string, mixed>  $definedVars
     * @return array<string, mixed>
     */
    public static function componentArguments(array $definedVars): array
    {
        return array_diff_key($definedVars, [
            '__data' => null,
            '__env' => null,
            '__livewire' => null,
            '__path' => null,
            '__split' => null,
            '_instance' => null,
            'app' => null,
            'component' => null,
            'errors' => null,
        ]);
    }
}
