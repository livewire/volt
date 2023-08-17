<?php

use Livewire\Volt\CompileContext;
use Livewire\Volt\Property;

use function Livewire\Volt\state;

it('may be empty', function () {
    $context = CompileContext::instance();

    expect($context->state)->toBe([]);
});

it('may have a single nullable value', function () {
    state('name');

    $context = CompileContext::instance();

    $properties = array_map(fn (Property $property) => $property->value, $context->state);

    expect($properties)->toBe([
        'name' => null,
    ]);
});

it('may have a multiple nullable properties', function () {
    state(['name', 'email']);

    $context = CompileContext::instance();

    $properties = array_map(fn (Property $property) => $property->value, $context->state);

    expect($properties)->toBe([
        'name' => null,
        'email' => null,
    ]);
});

it('may have eager properties with key as first argument and value as second', function () {
    $context = CompileContext::instance();

    state('name', 'Nuno');
    state('age', 30);
    state('address', null);
    state('email');

    $properties = array_map(fn (Property $property) => $property->value, $context->state);

    expect($properties)->toBe([
        'name' => 'Nuno',
        'age' => 30,
        'address' => null,
        'email' => null,
    ]);
});

it('may have eager properties with associative array', function () {
    $context = CompileContext::instance();

    state(['name' => 'Nuno', 'email' => 'nuno@laravel.com']);

    $properties = array_map(fn (Property $property) => $property->value, $context->state);

    expect($properties)->toBe([
        'name' => 'Nuno',
        'email' => 'nuno@laravel.com',
    ]);
});

it('may have eager properties with named arguments', function () {
    $context = CompileContext::instance();

    state(name: 'Nuno', email: 'nuno@laravel.com');

    $properties = array_map(fn (Property $property) => $property->value, $context->state);

    expect($properties)->toBe([
        'name' => 'Nuno',
        'email' => 'nuno@laravel.com',
    ]);
});

it('may have lazy properties with associative array', function () {
    $context = CompileContext::instance();

    state(['name' => fn () => 'Nuno', 'email' => 'nuno@laravel.com']);

    $properties = array_map(fn (Property $property) => value($property->value), $context->state);

    expect($properties)->toBe([
        'name' => 'Nuno',
        'email' => 'nuno@laravel.com',
    ]);
});

it('may have lazy properties with named arguments', function () {
    $context = CompileContext::instance();

    state(name: fn () => 'Nuno', email: 'nuno@laravel.com');

    $properties = array_map(fn (Property $property) => value($property->value), $context->state);

    expect($properties)->toBe([
        'name' => 'Nuno',
        'email' => 'nuno@laravel.com',
    ]);
});

it('may have lazy properties with key as first argument and value as second', function () {
    $context = CompileContext::instance();

    state('name', fn () => 'Nuno');
    state('age', fn () => 30);
    state('address', fn () => null);

    $properties = array_map(fn (Property $property) => value($property->value), $context->state);

    expect($properties)->toBe([
        'name' => 'Nuno',
        'age' => 30,
        'address' => null,
    ]);
});

test('precedence', function () {
    $context = CompileContext::instance();

    state(['name' => 'first', 'email' => 'first']);
    state(['name' => 'second']);

    $properties = array_map(fn (Property $property) => $property->value, $context->state);

    expect($properties)->toBe([
        'name' => 'second',
        'email' => 'first',
    ]);
});
