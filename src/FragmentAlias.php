<?php

namespace Livewire\Volt;

class FragmentAlias
{
    /**
     * The custom base path to use when encoding / decoding aliases.
     */
    protected static string $basePath;

    /**
     * Encode the given fragment's component name and path into a base64 embedded alias.
     */
    public static function encode(string $componentName, string $path, string $basePath = null): string
    {
        $basePath = $basePath ?? static::$basePath ?? base_path();

        return 'volt-anonymous-fragment-'.base64_encode(json_encode([
            'name' => $componentName,
            'path' => str_replace($basePath.DIRECTORY_SEPARATOR, '', $path),
        ]));
    }

    /**
     * Resolve the given fragment's component name and path from a base64 embedded alias.
     */
    public static function decode(string $alias, string $basePath = null): ?array
    {
        if (! static::isFragment($alias)) {
            return null;
        }

        $encoded = str_replace('volt-anonymous-fragment-', '', $alias);

        $decoded = json_decode(base64_decode($encoded), true);

        if (! isset($decoded['path'])) {
            return null;
        }

        $basePath = $basePath ?? static::$basePath ?? base_path();

        return [
            'name' => $decoded['name'],
            'path' => $basePath.DIRECTORY_SEPARATOR.$decoded['path'],
        ];
    }

    /**
     * Determine if the given component alias references a fragment.
     */
    protected static function isFragment(string $alias): bool
    {
        return str_starts_with($alias, 'volt-anonymous-fragment-');
    }

    /**
     * Specify a custom base path to be used when encoding / decoding aliases.
     */
    public static function useBasePath(string $basePath): void
    {
        static::$basePath = $basePath;
    }
}
