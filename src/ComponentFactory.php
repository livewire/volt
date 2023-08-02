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
    public function make(string $componentName, string $path): string
    {
        CompileContext::flush();

        $this->requirePath(
            CompileContext::instance()->path = $path
        );

        if ($componentClass = $this->getComponentClass($path)) {
            return $componentClass;
        }

        $context = tap(CompileContext::instance(), fn () => CompileContext::flush());

        foreach ($this->mountedDirectories->paths() as $mountedPath) {
            if (str_starts_with($path, $mountedPath->path)) {
                $context->uses = array_merge($context->uses, $mountedPath->uses);
            }
        }

        $file = new CompiledComponentFile($path, $componentName);

        Compiler::compile($file, $context);

        require $file->path();

        return tap(
            $this->getComponentClass($file->path()),
            fn (string $componentClass) => $componentClass::$__context = $context
        );
    }

    /**
     * Imports the component definition into the compilation context, or returns the component class name.
     */
    protected function requirePath(string $path): void
    {
        try {
            ob_start();

            $__path = $path;

            CompileContext::instance()->variables = (static function () use ($__path) {
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
    }

    /**
     * Extract the component class from the declared classes, if it exists.
     */
    protected function getComponentClass(string $path): ?string
    {
        return collect(get_declared_classes())
            ->first(fn (string $class) => str_starts_with($class, Component::class."@anonymous\x00".$path));
    }
}
