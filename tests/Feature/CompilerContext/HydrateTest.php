<?php

use Livewire\Volt\CompileContext;

use function Livewire\Volt\hydrate;

it('may not be defined', function () {
    $context = CompileContext::instance();

    expect($context->hydrate)->toBeNull();
});

it('may be defined', function () {
    $context = CompileContext::instance();

    hydrate(function () {
        return 'bar';
    });

    expect($context->hydrate)->resolve()->toBe('bar');
});

it('may be defined for a property using associative arrays', function () {
    $context = CompileContext::instance();

    hydrate(['name' => fn () => 'Nuno', 'email' => fn () => 'nuno@laravel.com']);

    expect($context->hydrateProperty['name'])->resolve()->toBe('Nuno')
        ->and($context->hydrateProperty['email'])->resolve()->toBe('nuno@laravel.com');
});

it('may be defined for a property using named arguments', function () {
    $context = CompileContext::instance();

    hydrate(name: fn () => 'Nuno', email: fn () => 'nuno@laravel.com');

    expect($context->hydrateProperty['name'])->resolve()->toBe('Nuno')
        ->and($context->hydrateProperty['email'])->resolve()->toBe('nuno@laravel.com');
});

test('precedence', function () {
    $context = CompileContext::instance();

    hydrate(fn () => 'first');
    hydrate(fn () => 'second');

    hydrate(name: fn () => 'first', email: fn () => 'first');
    hydrate(name: fn () => 'second');

    expect($context->hydrate)->resolve()->toBe('second')
        ->and($context->hydrateProperty['name'])->resolve()->toBe('second')
        ->and($context->hydrateProperty['email'])->resolve()->toBe('first');
});
