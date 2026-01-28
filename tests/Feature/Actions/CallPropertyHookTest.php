<?php

use Livewire\Volt\Actions\CallPropertyHook;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;

it('calls property hooks on the component', function () {
    $context = CompileContext::make();

    $context->updating = ['foo' => fn () => 'bar'];

    $component = new class extends Component {};

    $result = (new CallPropertyHook('updating', 'foo'))->execute($context, $component, []);

    expect($result)->toBe('bar');
});

it('calls array property hooks on the component', function () {
    $context = CompileContext::make();

    $context->updating = ['foo' => fn ($prop) => $prop];

    $component = new class extends Component
    {
    };

    $result = (new CallPropertyHook('updating', 'foo.bar'))->execute($context, $component, []);

    expect($result)->toBe('bar');
});

it('calls array property hooks for deep key changed on the component', function () {
    $context = CompileContext::make();

    $context->updating = ['foo' => fn ($prop) => $prop];

    $component = new class extends Component
    {
    };

    $result = (new CallPropertyHook('updating', 'foo.bar.baz'))->execute($context, $component, []);

    expect($result)->toBe('bar.baz');
});

it('calls sub-key property hooks on the component', function () {
    $context = CompileContext::make();

    $context->updating = ['foo.bar' => fn ($prop) => $prop];

    $component = new class extends Component
    {
    };

    $result = (new CallPropertyHook('updating', 'foo.bar.baz'))->execute($context, $component, []);

    expect($result)->toBe('baz');
});
