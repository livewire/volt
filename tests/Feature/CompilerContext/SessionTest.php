<?php

use Livewire\Attributes\Session;
use Livewire\Volt\CompileContext;
use Pest\Expectation;

use function Livewire\Volt\state;

it('may be defined', function () {
    $context = CompileContext::instance();

    state('name');
    state(tabOne: 1)->session();
    state(tabTwo: 2)->session(key: 'tab');

    expect($context->state)->toHaveKeys(['name', 'tabOne', 'tabTwo'])
        ->sequence(
            fn (Expectation $property) => $property->attributes->toBe([]),
            fn (Expectation $property) => $property->attributes->toBe([
                Session::class => [],
            ]),
            fn (Expectation $property) => $property->attributes->toBe([
                Session::class => [
                    'key' => 'tab',
                ],
            ]),
        );
});
