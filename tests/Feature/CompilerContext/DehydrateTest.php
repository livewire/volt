<?php

use Livewire\Volt\CompileContext;
use function Livewire\Volt\dehydrate;

it('may not be defined', function () {
    $context = CompileContext::instance();

    expect($context->dehydrate)->toBeNull();
});

it('may be defined', function () {
    $context = CompileContext::instance();

    dehydrate(fn () => 'bar');

    expect($context->dehydrate)->resolve()->toBe('bar');
});

it('may be defined for a property using associative arrays', function () {
    $context = CompileContext::instance();

    dehydrate(['name' => fn () => 'Nuno', 'email' => fn () => 'nuno@laravel.com']);

    expect($context->dehydrateProperty['name'])->resolve()->toBe('Nuno')
        ->and($context->dehydrateProperty['email'])->resolve()->toBe('nuno@laravel.com');
});

it('may be defined for a property using named arguments', function () {
    $context = CompileContext::instance();

    dehydrate(name: fn () => 'Nuno', email: fn () => 'nuno@laravel.com');

    expect($context->dehydrateProperty['name'])->resolve()->toBe('Nuno')
        ->and($context->dehydrateProperty['email'])->resolve()->toBe('nuno@laravel.com');
});

test('precedence', function () {
    $context = CompileContext::instance();

    dehydrate(fn () => 'first');
    dehydrate(fn () => 'second');

    dehydrate(name: fn () => 'first');
    dehydrate(email: fn () => 'first');
    dehydrate(name: fn () => 'second');

    expect($context->dehydrate)->resolve()->toBe('second')
        ->and($context->dehydrateProperty['name'])->resolve()->toBe('second')
        ->and($context->dehydrateProperty['email'])->resolve()->toBe('first');
});
