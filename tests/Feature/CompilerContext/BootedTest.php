<?php

use function Livewire\Volt\booted;
use Livewire\Volt\CompileContext;

it('may not be defined', function () {
    $context = CompileContext::instance();

    expect($context->booted)->toBeNull();
});

it('may be defined', function () {
    $context = CompileContext::instance();

    booted(fn () => 'bar');

    expect($context->booted)->resolve()->toBe('bar');
});

test('precedence', function () {
    $context = CompileContext::instance();

    booted(fn () => 'first');
    booted(fn () => 'second');

    expect($context->booted)->resolve()->toBe('second');
});
