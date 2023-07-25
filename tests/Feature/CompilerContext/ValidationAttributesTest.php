<?php

use Livewire\Volt\CompileContext;
use function Livewire\Volt\rules;

it('may not be defined', function () {
    $context = CompileContext::instance();

    expect($context->validationAttributes)->toBe([]);
});

it('may be defined using associative arrays', function () {
    $context = CompileContext::instance();

    rules()->attributes(['name' => 'full name', 'email' => 'email address']);

    expect($context->validationAttributes)->toBe([
        'name' => 'full name',
        'email' => 'email address',
    ]);
});

it('may be defined using named arguments', function () {
    $context = CompileContext::instance();

    rules()->attributes(name: 'full name', email: 'email address');

    expect($context->validationAttributes)->toBe([
        'name' => 'full name',
        'email' => 'email address',
    ]);
});

test('precedence', function () {
    $context = CompileContext::instance();

    rules()->attributes(['name' => 'first', 'email' => 'first']);
    rules()->attributes(['name' => 'second']);

    expect($context->validationAttributes)->toBe([
        'name' => 'second',
        'email' => 'first',
    ]);
});
