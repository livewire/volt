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

        $potentialComponentClass = $this->requirePath(
            CompileContext::instance()->path = $path
        );

        if ($potentialComponentClass) {
            return new $potentialComponentClass;
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
     * Imports the component definition into the compilation context, or returns the component class name.
     */
    protected function requirePath(string $path): ?string
    {
        $previouslyDeclaredClasses = get_declared_classes();

        try {
            ob_start();

            $__path = $path;

            CompileContext::instance()->variables = (function () use ($__path) {
                require $__path;

                return array_map(function (mixed $variable) {
                    return $variable instanceof Closure
                        ? ActionMethod::make($variable)
                        : $variable;
                }, get_defined_vars());
            })();
        } finally {
            ob_get_clean();
        }

        return collect(get_declared_classes())
            ->diff($previouslyDeclaredClasses)
            ->first(fn (string $class) => is_subclass_of($class, Component::class));
    }
}
