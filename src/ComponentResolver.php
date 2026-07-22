<?php

namespace Livewire\Volt;

use Illuminate\Support\Facades\File;

class ComponentResolver
{
    /**
     * Create a new component resolver instance.
     */
    public function __construct(
        protected ComponentFactory $factory
    ) {}

    /**
     * Attempt to resolve the given component name into a Volt component class name.
     *
     * @param  array<int, string>  $paths
     */
    public function resolve(string $alias, array $paths): ?string
    {
        foreach ($paths as $path) {
            if (File::exists($possiblePath = $path.'/'.str_replace('.', '/', $alias).'.blade.php')) {
                return $this->extractComponentClass($alias, realpath($possiblePath));
            }
        }

        if (is_array($component = FragmentAlias::decode($alias)) &&
            ($componentPath = realpath($component['path'])) !== false) {
            foreach (array_merge($paths, config('view.paths', []), [config('view.compiled')]) as $path) {
                if (($path = realpath($path)) !== false &&
                    str_starts_with($componentPath, $path.DIRECTORY_SEPARATOR)) {
                    return $this->extractComponentClass($alias, $componentPath);
                }
            }
        }

        return null;
    }

    /**
     * Extract the component class from the given file.
     */
    protected function extractComponentClass(string $componentName, string $componentPath): string
    {
        return $this->factory->make($componentName, $componentPath);
    }
}
