<?php

use Livewire\Volt\CompileContext;
use Livewire\Volt\Exceptions\PlaceholderAlreadyDefinedException;
use function Livewire\Volt\placeholder;

it('may not be defined', function () {
    $context = CompileContext::instance();

    expect($context->placeholder)->toBeNull();
});

it('may be defined with closure', function () {
    $context = CompileContext::instance();

    placeholder(fn () => '<div>foo</div>');

    expect($context->placeholder)->resolve()->toBe('<div>foo</div>');
});

it('may be defined with string', function () {
    $context = CompileContext::instance();

    placeholder('<div>bar</div>');

    expect($context->placeholder)->resolve()->toBe('<div>bar</div>');
});

test('precedence', function () {
    $context = CompileContext::instance();

    placeholder(fn () => 'first');
    placeholder(fn () => 'second');
})->throws(PlaceholderAlreadyDefinedException::class);
