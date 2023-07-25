<?php

use Livewire\Volt\CompileContext;
use function Livewire\Volt\state;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Modelable;
use Livewire\Attributes\Reactive;
use Pest\Expectation;

it('may be defined', function () {
    $context = CompileContext::instance();

    state('name')->modelable()->reactive()->locked();
    state(id: 1)->modelable()->reactive();
    state('email');

    expect($context->state)->toHaveKeys(['name', 'id', 'email'])
        ->sequence(
            fn (Expectation $property) => $property->attributes->toBe([
                Modelable::class => [],
                Reactive::class => [],
                Locked::class => [],
            ]),
            fn (Expectation $property) => $property->attributes->toBe([
                Modelable::class => [],
                Reactive::class => [],
            ]),
            fn (Expectation $property) => $property->attributes->toBe([]),
        );
});
