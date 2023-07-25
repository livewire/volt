<?php

namespace Livewire\Volt;

class FragmentMap
{
    /**
     * The registered fragments and their aliases.
     */
    protected static array $map = [];

    /**
     * Add a fragment name and alias pair to the fragment map.
     */
    public static function add(string $fragmentName, string $alias): void
    {
        static::$map[$fragmentName] = $alias;
    }

    /**
     * Determine if the given fragment is mapped.
     */
    public static function has(string $fragmentName): bool
    {
        return isset(static::$map[$fragmentName]);
    }

    /**
     * Get the alias for a given fragment name.
     */
    public static function get(string $fragmentName): string
    {
        return static::$map[$fragmentName];
    }
}
