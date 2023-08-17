<?php

use Livewire\Volt\CompileContext;

use function Livewire\Volt\layout;

it('may not be defined', function () {
    $context = CompileContext::instance();

    expect($context->layout)->toBeNull();
});

it('may be defined', function () {
    $context = CompileContext::instance();

    layout('layouts.guest');

    expect($context->layout)->toBe('layouts.guest');
});
