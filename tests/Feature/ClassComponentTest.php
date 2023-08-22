<?php

use Illuminate\Support\Facades\View;
use Illuminate\View\FileViewFinder;
use Livewire\Exceptions\MethodNotFoundException;
use Livewire\Exceptions\MissingRulesException;
use Livewire\Livewire;
use Livewire\Volt\Volt;

beforeEach(function () {
    View::setFinder(new FileViewFinder(app()['files'], [__DIR__.'/resources/views']));

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

it('allows to define components as routes', function () {
    Volt::route('/with-default-layout', 'navigate.with-default-layout');

    $this->get('/with-default-layout')
        ->assertSee('Title: default title.')
        ->assertSee('Layout: default layout.')
        ->assertSee('Content: content with default layout.');
});

it('allows to define components as routes with custom layout', function () {
    Volt::route('/with-custom-layout', 'navigate.with-custom-layout');

    $this->get('/with-custom-layout')
        ->assertSee('Title: custom title.')
        ->assertSee('Layout: custom layout.')
        ->assertSee('Content: content with custom layout.');
});

class PlusOne
{
    public function __invoke($value)
    {
        return $value + 1;
    }
}

it('may have data', function () {
    $component = Livewire::test('component-with-data');

    $component->assertSeeHtml([
        '<li>Counter Plus Zero: 3.</li>',
        '<li>Counter Plus One: 4.</li>',
        '<li>Counter Collection: 0, 1, 2, 3.</li>',
    ]);

    $component->set('counter', 10);

    $component->assertSeeHtml([
        '<li>Counter Plus Zero: 13.</li>',
        '<li>Counter Plus One: 14.</li>',
        '<li>Counter Collection: 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13.</li>',
    ]);
});
