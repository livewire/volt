<?php

use Livewire\Volt\CompileContext;
use Livewire\Volt\Exceptions\WithAlreadyDefinedException;

use function Livewire\Volt\with;

it('may be empty', function () {
    $context = CompileContext::instance();

    expect($context->viewData)->toBeNull();
});

it('may have a single nullable value', function () {
    with('name');

    $context = CompileContext::instance();

    $data = $context->viewData->__invoke();

    expect($data)->toBe([
        'name' => null,
    ]);
});

it('may have a multiple nullable properties', function () {
    with(['name', 'email']);

    $context = CompileContext::instance();

    $data = $context->viewData->__invoke();

    expect($data)->toBe([
        'name' => null,
        'email' => null,
    ]);
});

it('may have eager properties with key as first argument and value as second', function () {
    $context = CompileContext::instance();

    with('email');

    $data = $context->viewData->__invoke();

    expect($data)->toBe([
        'email' => null,
    ]);
});

it('may have eager properties with associative array', function () {
    $context = CompileContext::instance();

    with(['name' => 'Nuno', 'email' => 'nuno@laravel.com']);

    $data = $context->viewData->__invoke();

    expect($data)->toBe([
        'name' => 'Nuno',
        'email' => 'nuno@laravel.com',
    ]);
});

it('may have eager properties with named arguments', function () {
    $context = CompileContext::instance();

    with(name: 'Nuno', email: 'nuno@laravel.com');

    $data = $context->viewData->__invoke();

    expect($data)->toBe([
        'name' => 'Nuno',
        'email' => 'nuno@laravel.com',
    ]);
});

it('may have lazy properties with associative array', function () {
    $context = CompileContext::instance();

    with(['name' => fn () => 'Nuno', 'email' => 'nuno@laravel.com']);

    $data = $context->viewData->__invoke();

    expect($data)->name->resolve()->toBe('Nuno')
        ->email->toBe('nuno@laravel.com');
});

it('may have lazy properties with named arguments', function () {
    $context = CompileContext::instance();

    with(name: fn () => 'Nuno', email: 'nuno@laravel.com');

    $data = $context->viewData->__invoke();

    expect($data)->name->resolve()->toBe('Nuno')
        ->email->toBe('nuno@laravel.com');
});

it('may have lazy properties with key as first argument and value as second', function () {
    $context = CompileContext::instance();

    with('address', fn () => null);

    $data = $context->viewData->__invoke();

    expect($data)
        ->toHaveCount(1)
        ->address->resolve()->toBe(null);
});

test('precedence', function () {
    $context = CompileContext::instance();

    with(['name' => 'first', 'email' => 'first']);
    with(['name' => 'second']);
})->throws(WithAlreadyDefinedException::class);
