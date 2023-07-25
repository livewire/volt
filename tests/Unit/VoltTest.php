<?php

use Livewire\Volt\Volt;
use Livewire\Volt\VoltManager;

it('resolves an manager instance', function () {
    $instance = Volt::getFacadeRoot();

    expect($instance)->toBeInstanceOf(VoltManager::class);
});
