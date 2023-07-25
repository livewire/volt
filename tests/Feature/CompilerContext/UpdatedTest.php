<?php

use Livewire\Volt\CompileContext;
use function Livewire\Volt\updated;

it('may not be defined', function () {
    $context = CompileContext::instance();

    expect($context->updated)->toBe([]);
});

it('may be defined using associative arrays', function () {
    $context = CompileContext::instance();

    updated(['name' => fn () => 'Nuno', 'email' => fn () => 'nuno@laravel.com']);

    expect($context->updated['name'])->resolve()->toBe('Nuno')
        ->and($context->updated['email'])->resolve()->toBe('nuno@laravel.com');
});

it('may be defined using named arguments', function () {
    $context = CompileContext::instance();

    updated(name: fn () => 'Nuno', email: fn () => 'nuno@laravel.com');

    expect($context->updated['name'])->resolve()->toBe('Nuno')
        ->and($context->updated['email'])->resolve()->toBe('nuno@laravel.com');
});

test('precedence', function () {
    $context = CompileContext::instance();

    updated(name: fn () => 'first');
    updated(email: fn () => 'first');
    updated(name: fn () => 'second');

    expect($context->updated['name'])->resolve()->toBe('second')
        ->and($context->updated['email'])->resolve()->toBe('first');
});
