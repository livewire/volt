<?php

namespace Livewire\Volt;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void ensureViewsAreCached()
 * @method static void mount(array|string $paths = [], array|string $uses = [])
 * @method static array paths()
 * @method static \Livewire\Features\SupportTesting\Testable test(string $name, array $params = [])
 * @method static static actingAs(\Illuminate\Contracts\Auth\Authenticatable $user, ?string $driver = null)
 * @method static static withQueryParams(array $params = [])
 * @method static \Illuminate\Routing\Route route(string $uri, string $componentName)
 */
class Volt extends Facade
{
    /**
     * {@inheritDoc}
     */
    public static function getFacadeAccessor(): string
    {
        return VoltManager::class;
    }
}
