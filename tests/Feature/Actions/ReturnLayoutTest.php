<?php

use Livewire\Volt\Actions\ReturnLayout;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Component;

it('returns the layout view', function () {
    $context = CompileContext::make();

    $context->layout = 'layouts.guest';

    $component = new class extends Component {};

    $result = (new ReturnLayout)->execute($context, $component, []);

    expect($result)->toBe('layouts.guest');
});
