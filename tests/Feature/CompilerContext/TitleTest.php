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
