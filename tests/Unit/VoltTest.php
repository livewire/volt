<?php

use Livewire\Volt\Volt;
use Livewire\Volt\VoltManager;

it('resolves an manager instance', function () {
    $instance = Volt::getFacadeRoot();

    expect($instance)->toBeInstanceOf(VoltManager::class);
});

it('mounts paths into memory from strings', function () {
    /** @var VoltManager $managerInstance */
    $managerInstance = Volt::getFacadeRoot();

    $managerInstance->mount($path1 = __DIR__ . '/resources/views/vendor/livewire');

    expect(count($managerInstance->paths()))->toBe(1);

    expect($path = (collect($managerInstance->paths())->first())->path)->toBe(str_replace(
        '/',
        DIRECTORY_SEPARATOR,
        $path1,
    ));

    $managerInstance->mount($path2 = __DIR__ . '/resources/views/livewire');

    expect(count($managerInstance->paths()))->toBe(2);

    expect($path = (collect($managerInstance->paths())->last())->path)->toBe(str_replace(
        '/',
        DIRECTORY_SEPARATOR,
        $path2,
    ));
});

it('mounts paths into memory from arrays', function () {
    /** @var VoltManager $managerInstance */
    $managerInstance = Volt::getFacadeRoot();

    $managerInstance->mount([
        $path1 = __DIR__ . '/resources/views/vendor/livewire',
    ]);

    expect(count($managerInstance->paths()))->toBe(1);

    expect($path = (collect($managerInstance->paths())->first())->path)->toBe(str_replace(
        '/',
        DIRECTORY_SEPARATOR,
        $path1,
    ));

    $managerInstance->mount([
        $path2 = __DIR__ . '/resources/views/livewire',
    ]);

    expect($path = (collect($managerInstance->paths())->last())->path)->toBe(str_replace(
        '/',
        DIRECTORY_SEPARATOR,
        $path2,
    ));
});
