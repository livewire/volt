<?php

use Livewire\Exceptions\MethodNotFoundException;
use Livewire\Exceptions\MissingRulesException;
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

it('throws exception when "action" is not found', function () {
    Livewire::test('basic-component', ['name' => 'Taylor'])
        ->call('missing-action');
})->throws(
    MethodNotFoundException::class,
    'Method or action [missing-action] does not exist on component [basic-component].',
);

it('throws exception when rules are not found', function () {
    Livewire::test('component-with-missing-rules')
        ->call('store');
})->throws(
    MissingRulesException::class,
    'Missing [$rules/rules()] property/method on Livewire component: [component-with-missing-rules].',
);

it('throws exception when method is not found within action', function () {
    Livewire::test('component-with-action-that-calls-bad-method', ['name' => 'Taylor'])
        ->call('action');
})->throws(
    BadMethodCallException::class,
    'Method, action or protected callable [missingActionOrHelper] not found on component [component-with-action-that-calls-bad-method].',
);
