<?php

use Livewire\Volt\Actions\ReturnValidationMessages;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;

it('returns the validation messages', function () {
    $context = CompileContext::make();

    $context->messages = [
        'email.required' => 'The Email Address cannot be empty.',
        'email.email' => 'The Email Address format is not valid.',
    ];

    $component = new class extends Component
    {
    };

    $result = (new ReturnValidationMessages)->execute($context, $component, []);

    expect($result)->toBe([
        'email.required' => 'The Email Address cannot be empty.',
        'email.email' => 'The Email Address format is not valid.',
    ]);
});
