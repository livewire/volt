<?php

namespace Livewire\Volt;

use Closure;
use Livewire\Volt\Methods\ActionMethod;

class ComponentFactory
{
    /**
     * Create a new component factory instance.
     */
    public function __construct(protected MountedDirectories $mountedDirectories)
    {
    }

    /**
     * Make a new component instance from the given path.
     */
    public function make(string $componentName, string $path): Component
    {
        CompileContext::flush();

        $potentialComponentInstance = $this->requirePath(
            CompileContext::instance()->path = $path
        );

        if ($potentialComponentInstance instanceof Component) {
            return $potentialComponentInstance;
        }

        $context = tap(CompileContext::instance(), fn () => CompileContext::flush());

        foreach ($this->mountedDirectories->paths() as $mountedPath) {
            if (str_starts_with($path, $mountedPath->path)) {
                $context->uses = array_merge($context->uses, $mountedPath->uses);
            }
        }

        $file = new CompiledComponentFile($path, $componentName);

        Compiler::compile($file, $context);

        return tap(
            require $file->path(),
            fn (Component $component) => $component::$__context = $context
        );
    }

    /**
     * Imports the component definition into the compilation context.
     */
    protected function requirePath(string $path): ?Component
    {
        try {
            ob_start();

            $__path = $path;

            $variablesOrComponent = (function () use ($__path) {
                if (($potentiallyComponentInstance = require $__path) instanceof Component) {
                    return $potentiallyComponentInstance;
                }

                return array_map(function (mixed $variable) {
                    return $variable instanceof Closure
                        ? ActionMethod::make($variable)
                        : $variable;
                }, get_defined_vars());
            })();
        } finally {
            ob_get_clean();
        }

        if ($variablesOrComponent instanceof Component) {
            return $variablesOrComponent;
        }

        CompileContext::instance()->variables = $variablesOrComponent;

        return null;
    }
}
