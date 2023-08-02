<?php

use Livewire\Livewire;
use Livewire\Volt\Volt;

beforeEach(function () {
    Volt::mount([__DIR__.'/resources/views/class-api-pages', __DIR__.'/resources/views/class-api']);
});

it('can be rendered', function () {
    Livewire::test('basic-component')
        ->assertSee('Hello World');

    Volt::test('basic-component')
        ->assertSee('Hello World');
});

it('can have url attribute', function () {
    $component = Livewire::test('component-with-url-attribute');

    $component->assertSeeHtml([
        '<li>Nuno Maduro</li>',
        '<li>Nuno</li>',
        '<li>Taylor</li>',
    ]);

    $component->set('search', 'Nuno');

    $component->assertSeeHtml([
        '<li>Nuno Maduro</li>',
        '<li>Nuno</li>',
    ]);

    $component->assertDontSeeHtml([
        '<li>Taylor</li>',
    ]);
});
