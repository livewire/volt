<?php

use Livewire\Exceptions\ComponentNotFoundException;
use Livewire\Livewire;
use Livewire\Volt\Component;
use Livewire\Volt\LivewireManager;

it('resolves an "custom" manager instance', function () {
    $instance = Livewire::getFacadeRoot();

    expect($instance)->toBeInstanceOf(LivewireManager::class);
});

test('resolve missing component', function () {
    /** @var LivewireManager $managerInstance */
    $managerInstance = Livewire::getFacadeRoot();
    $componentInstance = new class extends Component
    {
    };

    expect(fn () => $managerInstance->new('basic-component'))
        ->toThrow(ComponentNotFoundException::class, 'Unable to find component: [basic-component]');

    $managerInstance->component('basic-component', $componentInstance::class);

    expect($managerInstance->new('basic-component'))->toBeInstanceOf(Component::class);
});
