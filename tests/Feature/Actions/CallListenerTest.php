<?php

use Livewire\Volt\Actions\CallListener;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;

it('calls inline listeners on the component', function () {
    $context = CompileContext::make();

    $context->inlineListeners['someEvent'] = fn ($amount) => $amount;

    $component = new class extends Component
    {
    };

    $result = (new CallListener('someEvent'))->execute($context, $component, [1]);

    expect($result)->toBe(1);
});
