<?php

use Livewire\Volt\Actions\CallHook;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;

it('calls hooks on the component', function () {
    $context = CompileContext::make();

    $context->booted = fn () => 'foo';

    $component = new class extends Component
    {
    };

    $result = (new CallHook('booted'))->execute($context, $component, []);

    expect($result)->toBe('foo');
});
