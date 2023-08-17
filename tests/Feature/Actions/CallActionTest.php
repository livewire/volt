<?php

use Livewire\Volt\Actions\CallMethod;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;

use function Livewire\Volt\computed;
use function Livewire\Volt\protect;

it('calls actions methods on the component', function () {
    $context = CompileContext::make();

    $context->variables = ['foo' => fn () => 'bar'];

    $component = new class extends Component
    {
    };

    $result = (new CallMethod('foo'))->execute($context, $component, []);

    expect($result)->toBe('bar');
});

it('calls computed methods on the component', function () {
    $context = CompileContext::make();

    $context->variables = ['foo' => computed(fn () => 'bar')];

    $component = new class extends Component
    {
    };

    $result = (new CallMethod('foo'))->execute($context, $component, []);

    expect($result)->toBe('bar');
});

it('calls helpers methods on the component', function () {
    $context = CompileContext::make();

    $context->variables = ['foo' => protect(fn () => 'bar')];

    $component = new class extends Component
    {
    };

    $result = (new CallMethod('foo'))->execute($context, $component, []);

    expect($result)->toBe('bar');
});
