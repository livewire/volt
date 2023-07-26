<?php

use Livewire\Attributes\Url;
use Livewire\Volt\CompileContext;
use function Livewire\Volt\state;
use Pest\Expectation;

it('may be defined', function () {
    $context = CompileContext::instance();

    state('name');
    state(searchOne: 1)->url();
    state(searchTwo: 2)->url(as: 'search', history: true);
    state(searchThree: 3)->url(keep: true);

    expect($context->state)->toHaveKeys(['name', 'searchOne', 'searchTwo', 'searchThree'])
        ->sequence(
            fn (Expectation $property) => $property->attributes->toBe([]),
            fn (Expectation $property) => $property->attributes->toBe([
                Url::class => [],
            ]),
            fn (Expectation $property) => $property->attributes->toBe([
                Url::class => [
                    'as' => 'search',
                    'history' => true,
                ],
            ]),
            fn (Expectation $property) => $property->attributes->toBe([
                Url::class => [
                    'keep' => true,
                ],
            ]),
        );
});
