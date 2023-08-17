<?php

use Livewire\Volt\CompileContext;

use function Livewire\Volt\updating;

it('may not be defined', function () {
    $context = CompileContext::instance();

    expect($context->updating)->toBe([]);
});

it('may be defined using associative arrays', function () {
    $context = CompileContext::instance();

    updating(['name' => fn () => 'Nuno', 'email' => fn () => 'nuno@laravel.com']);

    expect($context->updating['name'])->resolve()->toBe('Nuno')
        ->and($context->updating['email'])->resolve()->toBe('nuno@laravel.com');
});

it('may be defined using named arguments', function () {
    $context = CompileContext::instance();

    updating(name: fn () => 'Nuno', email: fn () => 'nuno@laravel.com');

    expect($context->updating['name'])->resolve()->toBe('Nuno')
        ->and($context->updating['email'])->resolve()->toBe('nuno@laravel.com');
});

test('precedence', function () {
    $context = CompileContext::instance();

    updating(name: fn () => 'first');
    updating(email: fn () => 'first');
    updating(name: fn () => 'second');

    expect($context->updating['name'])->resolve()->toBe('second')
        ->and($context->updating['email'])->resolve()->toBe('first');
});
