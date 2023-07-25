<?php

use Livewire\Volt\CompileContext;
use function Livewire\Volt\state;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Reactive;
use Pest\Expectation;

it('may be defined', function () {
    $context = CompileContext::instance();

    state('name');
    state(id: 1)->reactive();
    state('email')->reactive()->locked();

    expect($context->state)->toHaveKeys(['name', 'id', 'email'])
        ->sequence(
            fn (Expectation $property) => $property->attributes->toBe([]),
            fn (Expectation $property) => $property->attributes->toBe([
                Reactive::class => [],
            ]),
            fn (Expectation $property) => $property->attributes->toBe([
                Reactive::class => [],
                Locked::class => [],
            ]),
        );
});
