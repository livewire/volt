<?php

namespace Livewire\Volt;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void mount(array|string $paths = [], array|string $uses = [])
 * @method static array paths()
 * @method static \Livewire\Testing\TestableLivewire test(string $name, array $params = [])
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
