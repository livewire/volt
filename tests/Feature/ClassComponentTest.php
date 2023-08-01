<?php

use Livewire\Livewire;
use Livewire\Volt\Volt;

beforeEach(function () {
    Volt::mount([__DIR__.'/resources/views/pages', __DIR__.'/resources/views/class-api']);
});

it('can be rendered', function () {
    Livewire::test('basic-component')
        ->assertSee('Hello World');

    Volt::test('basic-component')
        ->assertSee('Hello World');
})->only();
