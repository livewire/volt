<?php

use Livewire\Volt\CompileContext;

use function Livewire\Volt\rules;

it('may not be defined', function () {
    $context = CompileContext::instance();

    expect($context->rules)->toBe([]);
});

it('may be defined using associative arrays', function () {
    $context = CompileContext::instance();

    rules(['name' => 'required|min:6', 'email' => 'nullable|email']);

    expect($context->rules)->toBe([
        'name' => 'required|min:6',
        'email' => 'nullable|email',
    ]);
});

it('may be defined using named arguments', function () {
    $context = CompileContext::instance();

    rules(name: 'required|min:6', email: 'nullable|email');

    expect($context->rules)->toBe([
        'name' => 'required|min:6',
        'email' => 'nullable|email',
    ]);
});

it('may be defined using closures', function () {
    $context = CompileContext::instance();

    rules(fn () => ['name' => 'required|min:6', 'email' => 'nullable|email']);

    expect($context->rules)->resolve()->toBe(['name' => 'required|min:6', 'email' => 'nullable|email']);
});

test('precedence', function () {
    $context = CompileContext::instance();

    rules(['name' => 'first', 'email' => 'first']);
    rules(['name' => 'second']);

    expect($context->rules)->toBe([
        'name' => 'second',
        'email' => 'first',
    ]);

    rules(fn () => ['name' => 'third']);

    expect($context->rules)->resolve()->toBe([
        'name' => 'third',
    ]);
});
