<?php

use Livewire\Volt\CompileContext;

use function Livewire\Volt\rules;

it('may not be defined', function () {
    $context = CompileContext::instance();

    expect($context->messages)->toBe([]);
});

it('may be defined using associative arrays', function () {
    $context = CompileContext::instance();

    rules()->messages(['required' => 'The :attribute field is required.', 'between' => 'The :attribute is not between.']);

    expect($context->messages)->toBe([
        'required' => 'The :attribute field is required.',
        'between' => 'The :attribute is not between.',
    ]);
});

it('may be defined using requiredd arguments', function () {
    $context = CompileContext::instance();

    rules()->messages(required: 'The :attribute field is required.', between: 'The :attribute is not between.');

    expect($context->messages)->toBe([
        'required' => 'The :attribute field is required.',
        'between' => 'The :attribute is not between.',
    ]);
});

test('precedence', function () {
    $context = CompileContext::instance();

    rules()->messages(['required' => 'first', 'between' => 'first']);
    rules()->messages(['required' => 'second']);

    expect($context->messages)->toBe([
        'required' => 'second',
        'between' => 'first',
    ]);
});
