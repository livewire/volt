<?php

use Livewire\Volt\CompileContext;
use function Livewire\Volt\mount;

it('may be defined without behaviour', function () {
    $context = CompileContext::instance();

    expect($context->mount)->resolve()->toBeNull();
});

it('may be defined', function () {
    $context = CompileContext::instance();

    mount(fn () => 'bar');

    expect($context->mount)->resolve()->toBe('bar');
});

test('precedence', function () {
    $context = CompileContext::instance();

    mount(fn () => 'first');
    mount(fn () => 'second');

    expect($context->mount)->resolve()->toBe('second');
});
