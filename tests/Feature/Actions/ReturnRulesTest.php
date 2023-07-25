<?php

use Livewire\Volt\Actions\ReturnRules;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;

it('returns the validation rules', function () {
    $context = CompileContext::make();

    $context->rules = ['foo'];

    $component = new class extends Component
    {
    };

    $result = (new ReturnRules)->execute($context, $component, []);

    expect($result)->toBe(['foo']);
});
