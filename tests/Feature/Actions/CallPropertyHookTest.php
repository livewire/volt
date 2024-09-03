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
