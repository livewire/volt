<?php

use Livewire\Volt\CompileContext;

use function Livewire\Volt\title;

it('may not be defined', function () {
    $context = CompileContext::instance();

    expect($context->title)->toBeNull();
});

it('may be defined', function () {
    $context = CompileContext::instance();

    title('my custom title');

    expect($context->title)->toBe('my custom title');
});

it('may be set using closures', function () {
    $context = CompileContext::instance();

    title(fn () => 'my custom title from a closure');

    expect($context->title)
        ->toBeCallable()
        ->and($context->title)
        ->resolve()
        ->toBe('my custom title from a closure');
});
