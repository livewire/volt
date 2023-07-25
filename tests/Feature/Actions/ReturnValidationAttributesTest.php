<?php

use Livewire\Volt\Actions\ReturnValidationAttributes;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;

it('returns the validation attributes', function () {
    $context = CompileContext::make();

    $context->validationAttributes = [
        'email' => 'email address',
    ];

    $component = new class extends Component
    {
    };

    $result = (new ReturnValidationAttributes())->execute($context, $component, []);

    expect($result)->toBe([
        'email' => 'email address',
    ]);
});
