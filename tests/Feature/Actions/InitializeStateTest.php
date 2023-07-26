<?php

use Livewire\Attributes\Url;
use Livewire\Volt\Actions\InitializeState;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;
use Livewire\Volt\Property;

it('sets properties on the component', function () {
    $context = CompileContext::make();

    $context->state = [
        'property' => Property::make('value'),
        'lazyProperty' => Property::make(fn () => 'lazyValue'),
    ];

    $component = new class extends Component
    {
        public $property;

        public $lazyProperty;
    };

    (new InitializeState)->execute($context, $component, []);

    expect($component)
        ->property->toBe('value')
        ->lazyProperty->toBe('lazyValue');
});

it('set url properties default value', function () {
    $context = CompileContext::make();

    $context->state = [
        'nullProperty' => Property::make(null)->attribute(Url::class),
        'lazyNullProperty' => Property::make(fn () => null)->attribute(Url::class),
        'emptyStringProperty' => Property::make('')->attribute(Url::class),
        'lazyEmptyStringProperty' => Property::make(fn () => '')->attribute(Url::class),
        'stringProperty' => Property::make('value')->attribute(Url::class),
        'lazyStringProperty' => Property::make(fn () => 'value')->attribute(Url::class),
    ];

    $component = new class extends Component
    {
        public $nullProperty;

        public $lazyNullProperty;

        public $emptyStringProperty;

        public $lazyEmptyStringProperty;

        public $stringProperty;

        public $lazyStringProperty;
    };

    (new InitializeState)->execute($context, $component, []);

    expect($component->nullProperty)->toBeNull()
        ->and($component->lazyNullProperty)->toBeNull()
        ->and($component->emptyStringProperty)->toBe('')
        ->and($component->lazyEmptyStringProperty)->toBe('')
        ->and($component->stringProperty)->toBe('value')
        ->and($component->lazyStringProperty)->toBe('value');
});

it('do not set url properties default value if already set by livewire', function () {
    $context = CompileContext::make();

    $context->state = [
        'nullProperty' => Property::make(null)->attribute(Url::class),
        'lazyNullProperty' => Property::make(fn () => null)->attribute(Url::class),
        'emptyStringProperty' => Property::make('')->attribute(Url::class),
        'lazyEmptyStringProperty' => Property::make(fn () => '')->attribute(Url::class),
        'stringProperty' => Property::make('value')->attribute(Url::class),
        'lazyStringProperty' => Property::make(fn () => 'value')->attribute(Url::class),
    ];

    $component = new class extends Component
    {
        public $nullProperty;

        public $lazyNullProperty;

        public $emptyStringProperty;

        public $lazyEmptyStringProperty;

        public $stringProperty;

        public $lazyStringProperty;
    };

    $component->nullProperty = 'not null';
    $component->lazyNullProperty = 'lazy not null';
    $component->emptyStringProperty = 'not empty';
    $component->lazyEmptyStringProperty = 'lazy not empty';
    $component->stringProperty = 'some value';
    $component->lazyStringProperty = 'lazy some value';

    (new InitializeState)->execute($context, $component, []);

    expect($component->nullProperty)->toBe('not null')
        ->and($component->lazyNullProperty)->toBe('lazy not null')
        ->and($component->emptyStringProperty)->toBe('not empty')
        ->and($component->lazyEmptyStringProperty)->toBe('lazy not empty')
        ->and($component->stringProperty)->toBe('some value')
        ->and($component->lazyStringProperty)->toBe('lazy some value');
});
