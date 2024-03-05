<?php

use Livewire\Volt\Volt;
use Livewire\Volt\VoltManager;

it('resolves an manager instance', function () {
    $instance = Volt::getFacadeRoot();

    expect($instance)->toBeInstanceOf(VoltManager::class);
});

it('will not store duplicate paths', function () {
    /** @var VoltManager $managerInstance */
    $managerInstance = Volt::getFacadeRoot();

    expect(count($managerInstance->paths()))->toBe(0);

    $managerInstance->mount([
        $path1 = __DIR__ . '/resources/views/livewire',
    ]);

    $managerInstance->mount([
        $path1
    ]);

    $managerInstance->mount($path1);

    expect(count($managerInstance->paths()))->toBe(1);
});