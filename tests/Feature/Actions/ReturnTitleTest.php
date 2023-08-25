<?php

use Livewire\Volt\Actions\ReturnTitle;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;

it('returns the title view', function () {
    $context = CompileContext::make();

    $context->title = 'my title';

    $component = new class extends Component
    {
    };

    $result = (new ReturnTitle)->execute($context, $component, []);

    expect($result)->toBe('my title');
});
