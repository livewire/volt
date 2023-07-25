<?php

use function Livewire\Volt\boot;
use Livewire\Volt\CompileContext;

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
