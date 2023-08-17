<?php

use Livewire\Volt\CompileContext;

use function Livewire\Volt\boot;

it('may not be defined', function () {
    $context = CompileContext::instance();

    expect($context->boot)->toBeNull();
});

it('may be defined', function () {
    $context = CompileContext::instance();

    boot(fn () => 'bar');

    expect($context->boot)->resolve()->toBe('bar');
});

test('precedence', function () {
    $context = CompileContext::instance();

    boot(fn () => 'first');
    boot(fn () => 'second');

    expect($context->boot)->resolve()->toBe('second');
});
