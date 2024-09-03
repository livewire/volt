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
            } elseif (is_array($component = FragmentAlias::decode($alias))) {
                return $this->extractComponentClass($alias, realpath($component['path']));
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
